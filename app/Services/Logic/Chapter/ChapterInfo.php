<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Course\CourseUserTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ChapterInfo extends LogicService
{

    /**
     * @var CourseModel
     */
    protected CourseModel $course;

    /**
     * @var UserModel
     */
    protected UserModel $user;

    use CourseTrait;
    use ChapterTrait;
    use CourseUserTrait;
    use ChapterUserTrait;

    public function handle(int $id): array
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $this->course = $course;

        $user = $this->getCurrentUser();

        $this->user = $user;

        $this->setCourseUser($course, $user);
        $this->handleCourseUser($course, $user);

        $this->setChapterUser($chapter, $user);
        $this->handleChapterUser($chapter, $user);

        $result = $this->handleChapter($chapter, $user);

        $this->eventsManager->fire('Chapter:afterView', $this, $chapter);

        return $result;
    }

    protected function handleChapter(ChapterModel $chapter, UserModel $user): array
    {
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($this->course, $chapter);

        /**
         * 无内容查看权限，过滤掉相关内容
         */
        if (!$this->ownedChapter) {
            if ($chapter->model == ChapterModel::MODEL_VIDEO) {
                $result['play_urls'] = [];
            } elseif ($chapter->model == ChapterModel::MODEL_ARTICLE) {
                $result['content'] = '';
            }
        }

        $result['course'] = $service->handleCourseInfo($this->course);

        $result['me'] = $this->handleMeInfo($chapter, $user);

        return $result;
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user): void
    {
        if ($user->id == 0) return;

        if ($this->joinedCourse) return;

        if (!$this->ownedCourse) return;

        $joinType = $this->getFreeJoinType($course, $user);

        $courseUser = $this->createCourseUser($course, $user, 0, $joinType);

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $this->recountCourseUsers($course);
    }

    protected function handleChapterUser(ChapterModel $chapter, UserModel $user): void
    {
        if ($user->id == 0) return;

        if (!$this->joinedCourse) return;

        if (!$this->ownedChapter) return;

        if ($this->joinedChapter) return;

        $chapterUser = new ChapterUserModel();

        $chapterUser->course_id = $chapter->course_id;
        $chapterUser->chapter_id = $chapter->id;
        $chapterUser->user_id = $user->id;

        $chapterUser->create();

        $this->chapterUser = $chapterUser;

        $this->joinedChapter = true;

        $this->incrChapterUserCount($chapter);
    }

    protected function handleMeInfo(ChapterModel $chapter, UserModel $user): array
    {
        $me = [
            'manager' => 0,
            'position' => 0,
            'logged' => 0,
            'joined' => 0,
            'owned' => 0,
            'liked' => 0,
        ];

        if ($user->id > 0) {

            $me['logged'] = 1;

            if ($this->joinedChapter) {
                $me['joined'] = 1;
            }

            if ($this->ownedChapter) {
                $me['owned'] = 1;
            }

            $likeRepo = new ChapterLikeRepo();

            $like = $likeRepo->findChapterLike($chapter->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            if ($this->course->teacher_id == $user->id) {
                $me['manager'] = 1;
            }

            if ($this->chapterUser) {
                $me['position'] = $this->chapterUser->position;
            }
        }

        return $me;
    }

    protected function incrChapterUserCount(ChapterModel $chapter): void
    {
        $chapter->user_count += 1;

        $chapter->update();

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->user_count += 1;

        $parent->update();
    }

}
