<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Review as ReviewModel;
use App\Repos\Review as ReviewRepo;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class ReviewList extends LogicService
{

    use CourseTrait;

    public function handle(int $id): RepositoryInterface
    {
        $course = $this->checkCourseCache($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $course->id,
            'published' => ReviewModel::PUBLISH_APPROVED,
            'deleted' => 0,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new ReviewListBuilder();

        $reviews = $pager->getItems()->toArray();

        $users = $builder->getUsers($reviews);

        $meMappings = $this->getMeMappings($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $owner = $users[$review['owner_id']] ?? null;

            $me = $meMappings[$review['id']];

            $items[] = [
                'id' => $review['id'],
                'rating' => $review['rating'],
                'content' => $review['content'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

    protected function getMeMappings(array $reviews): array
    {
        $user = $this->getCurrentUser(true);

        $likeRepo = new ReviewLikeRepo();

        $likedIds = [];

        if ($user->id > 0) {
            $likes = $likeRepo->findByUserId($user->id)
                ->filter(function ($like) {
                    if ($like->deleted == 0) {
                        return $like;
                    }
                });
            $likedIds = array_column($likes, 'review_id');
        }

        $result = [];

        foreach ($reviews as $review) {
            $result[$review['id']] = [
                'logged' => $user->id > 0 ? 1 : 0,
                'liked' => in_array($review['id'], $likedIds) ? 1 : 0,
                'owned' => $review['owner_id'] == $user->id ? 1 : 0,
            ];
        }

        return $result;
    }

}
