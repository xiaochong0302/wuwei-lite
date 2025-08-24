<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Services\CourseStat as CourseStatService;
use App\Validators\Review as ReviewValidator;
use Phalcon\Paginator\RepositoryInterface;

class Review extends Service
{

    use AccountSearchTrait;

    public function getPublishTypes(): array
    {
        return ReviewModel::publishTypes();
    }

    public function getReviews(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function getReview(int $id): ReviewModel
    {
        return $this->findOrFail($id);
    }

    public function updateReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

        $course = $this->findCourse($review->course_id);

        $post = $this->request->getPost();

        $validator = new ReviewValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['rating'])) {
            $data['rating'] = $validator->checkRating($post['rating']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            $this->recountCourseReviews($course);
        }

        $review->assign($data);

        $review->update();

        $this->updateCourseRating($course);

        $this->eventsManager->fire('Review:afterUpdate', $this, $review);

        return $review;
    }

    public function deleteReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

        $review->deleted = 1;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);

        $this->eventsManager->fire('Review:afterReview', $this, $review);
    }

    public function restoreReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

        $review->deleted = 0;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);

        $this->eventsManager->fire('Review:afterRestore', $this, $review);
    }

    public function batchModerate(): void
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        foreach ($reviews as $review) {

            if ($type == 'approve') {

                $review->published = ReviewModel::PUBLISH_APPROVED;

                $review->update();

            } elseif ($type == 'reject') {

                $review->published = ReviewModel::PUBLISH_REJECTED;

                $review->update();
            }

            $course = $this->findCourse($review->course_id);

            $this->recountCourseReviews($course);
            $this->updateCourseRating($course);
        }
    }

    public function batchDelete(): void
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        foreach ($reviews as $review) {

            $review->deleted = 1;

            $review->update();

            $course = $this->findCourse($review->course_id);

            $this->recountCourseReviews($course);
            $this->updateCourseRating($course);
        }
    }

    protected function findOrFail(int $id): ReviewModel
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

    protected function findCourse(int $id): CourseModel
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($id);
    }

    protected function recountCourseReviews(CourseModel $course): void
    {
        $courseRepo = new CourseRepo();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->update();
    }

    protected function updateCourseRating(CourseModel $course): void
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

    protected function handleReviews(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
