<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseList as CourseListBuilder;
use App\Caches\Course as CourseCache;
use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseRelated as CourseRelatedRepo;
use App\Repos\User as UserRepo;
use App\Services\Category as CategoryService;
use App\Services\Sync\CourseIndex as CourseIndexSync;
use App\Validators\Course as CourseValidator;
use Phalcon\Paginator\RepositoryInterface;

class Course extends Service
{

    public function getLevelTypes(): array
    {
        return CourseModel::levelTypes();
    }

    public function getTeacherOptions(): array
    {
        $userRepo = new UserRepo();

        $teachers = $userRepo->findTeachers();

        if ($teachers->count() == 0) return [];

        $options = [];

        foreach ($teachers as $teacher) {
            $options[] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
            ];
        }

        return $options;
    }

    public function getCategoryOptions(): array
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions();
    }

    public function getStudyExpiryOptions(): array
    {
        return CourseModel::studyExpiryOptions();
    }

    public function getRefundExpiryOptions(): array
    {
        return CourseModel::refundExpiryOptions();
    }

    public function getXmCourses(int $id):array
    {
        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findRelatedCourses($id);

        $courseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $courseIds[] = $course->id;
            }
        }

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s', $item->id, $item->title),
                'value' => $item->id,
                'selected' => in_array($item->id, $courseIds),
            ];
        }

        return $result;
    }

    public function getModules(int $id): array
    {
        $course = $this->findOrFail($id);

        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findAll([
            'parent_id' => 0,
            'course_id' => $course->id,
            'deleted' => $deleted,
        ])->toArray();
    }

    public function getCourses(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    public function getCourse(int $id): CourseModel
    {
        return $this->findOrFail($id);
    }

    public function createCourse(): CourseModel
    {
        $post = $this->request->getPost();

        $validator = new CourseValidator();

        $course = new CourseModel();

        $course->title = $validator->checkTitle($post['title']);

        $course->create();

        return $course;
    }

    public function updateCourse(int $id): CourseModel
    {
        $course = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CourseValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['level'])) {
            $data['level'] = $validator->checkLevel($post['level']);
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['review_enabled'])) {
            $data['review_enabled'] = $validator->checkReviewStatus($post['review_enabled']);
        }

        if (isset($post['comment_enabled'])) {
            $data['comment_enabled'] = $validator->checkCommentStatus($post['comment_enabled']);
        }

        if (isset($post['category_id'])) {
            $data['category_id'] = $validator->checkCategoryId($post['category_id']);
        }

        if (isset($post['teacher_id'])) {
            $data['teacher_id'] = $validator->checkTeacherId($post['teacher_id']);
        }

        if (isset($post['regular_price'])) {
            $data['regular_price'] = $validator->checkRegularPrice($post['regular_price']);
        }

        if (isset($post['vip_price'])) {
            $data['vip_price'] = $validator->checkVipPrice($post['vip_price']);
        }

        if (isset($post['study_expiry'])) {
            $data['study_expiry'] = $validator->checkStudyExpiry($post['study_expiry']);
        }

        if (isset($post['refund_expiry'])) {
            $data['refund_expiry'] = $validator->checkRefundExpiry($post['refund_expiry']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['details'])) {
            $data['details'] = $validator->checkDetails($post['details']);
        }

        if (isset($post['xm_course_ids'])) {
            $this->saveRelatedCourses($course, $post['xm_course_ids']);
        }

        $course->assign($data);

        $course->update();

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    public function deleteCourse(int $id): CourseModel
    {
        $course = $this->findOrFail($id);

        $course->deleted = 1;

        $course->update();

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    public function restoreCourse(int $id): CourseModel
    {
        $course = $this->findOrFail($id);

        $course->deleted = 0;

        $course->update();

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    protected function findOrFail(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    protected function saveRelatedCourses(CourseModel $course, string $xmCourseIds)
    {
        $courseRepo = new CourseRepo();

        $relatedCourses = $courseRepo->findRelatedCourses($course->id);

        $originRelatedIds = [];

        if ($relatedCourses->count() > 0) {
            foreach ($relatedCourses as $relatedCourse) {
                $originRelatedIds[] = $relatedCourse->id;
            }
        }

        $newRelatedIds = $xmCourseIds ? explode(',', $xmCourseIds) : [];
        $addedRelatedIds = array_diff($newRelatedIds, $originRelatedIds);

        $courseRelatedRepo = new CourseRelatedRepo();

        /**
         * 双向关联
         */
        if ($addedRelatedIds) {
            foreach ($addedRelatedIds as $relatedId) {
                if ($relatedId != $course->id) {
                    $record = $courseRelatedRepo->findCourseRelated($course->id, $relatedId);
                    if (!$record) {
                        $courseRelated = new CourseRelatedModel();
                        $courseRelated->course_id = $course->id;
                        $courseRelated->related_id = $relatedId;
                        $courseRelated->create();
                    }
                    $record = $courseRelatedRepo->findCourseRelated($relatedId, $course->id);
                    if (!$record) {
                        $courseRelated = new CourseRelatedModel();
                        $courseRelated->course_id = $relatedId;
                        $courseRelated->related_id = $course->id;
                        $courseRelated->create();
                    }
                }
            }
        }

        $deletedRelatedIds = array_diff($originRelatedIds, $newRelatedIds);

        /**
         * 单向删除
         */
        if ($deletedRelatedIds) {
            $courseRelatedRepo = new CourseRelatedRepo();
            foreach ($deletedRelatedIds as $relatedId) {
                $courseRelated = $courseRelatedRepo->findCourseRelated($course->id, $relatedId);
                if ($courseRelated) {
                    $courseRelated->delete();
                }
            }
        }

        $cache = new CourseRelatedListCache();

        $cache->rebuild($course->id);
    }

    protected function rebuildCourseCache(CourseModel $course): void
    {
        $cache = new CourseCache();

        $cache->rebuild($course->id);
    }

    protected function rebuildCourseIndex(CourseModel $course): void
    {
        $sync = new CourseIndexSync();

        $sync->addItem($course->id);
    }

    protected function handleCourses(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new CourseListBuilder();

            $items = $pager->getItems()->toArray();

            $pipeA = $builder->handleCategories($items);
            $pipeB = $builder->handleTeachers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->setItems($pipeC);
        }

        return $pager;
    }

}
