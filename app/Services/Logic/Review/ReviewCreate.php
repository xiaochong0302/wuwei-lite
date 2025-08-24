<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Review;

use App\Models\CourseUser as CourseUserModel;
use App\Models\Review as ReviewModel;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\CourseUser as CourseUserValidator;

class ReviewCreate extends LogicService
{

    use CourseTrait;
    use ReviewTrait;
    use ReviewDataTrait;

    public function handle(): ReviewModel
    {
        $post = $this->request->getPost();

        $course = $this->checkCourseCache($post['course_id']);

        $user = $this->getLoginUser(true);

        $validator = new CourseUserValidator();

        $validator->checkIfReviewEnabled($course->id);

        $courseUser = $validator->checkCourseUser($course->id, $user->id);

        $validator->checkIfReviewed($course->id, $user->id);

        $data = $this->handlePostData($post);

        $data['course_id'] = $course->id;
        $data['owner_id'] = $user->id;
        $data['published'] = ReviewModel::PUBLISH_PENDING;

        $review = new ReviewModel();

        $review->assign($data);

        $review->create();

        $this->updateCourseUserReview($courseUser);

        $this->eventsManager->fire('Review:afterCreate', $this, $review);

        return $review;
    }

    protected function updateCourseUserReview(CourseUserModel $courseUser): void
    {
        $courseUser->reviewed = 1;

        $courseUser->update();
    }

}
