<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends LogicService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle(int $id): void
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourseCache($review->course_id);

        $user = $this->getLoginUser(true);

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $review->deleted = 1;

        $review->update();

        $this->recountCourseReviews($course);
        $this->updateCourseRating($course);

        $this->eventsManager->fire('Review:afterDelete', $this, $review);
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

}
