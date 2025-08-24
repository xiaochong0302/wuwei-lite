<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Review;

use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;
use App\Traits\Client as ClientTrait;
use App\Validators\Review as ReviewValidator;

trait ReviewDataTrait
{

    use ClientTrait;

    protected function handlePostData(array $post): array
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new ReviewValidator();

        $data['content'] = $validator->checkContent($post['content']);
        $data['rating'] = $validator->checkRating($post['rating']);

        return $data;
    }

    protected function recountCourseReviews(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->update();
    }

    protected function updateCourseRating(int $courseId): void
    {
        $service = new CourseStatService();

        $service->updateRating($courseId);
    }

}
