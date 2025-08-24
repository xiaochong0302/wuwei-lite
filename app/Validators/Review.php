<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Repos\Review as ReviewRepo;

class Review extends Validator
{

    public function checkReview(int $id): ReviewModel
    {
        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findById($id);

        if (!$review) {
            throw new BadRequestException('review.not_found');
        }

        return $review;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkContent(string $content): string
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('review.content_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('review.content_too_long');
        }

        return $value;
    }

    public function checkRating(int $rating): int
    {
        if ($rating < 1 || $rating > 5) {
            throw new BadRequestException('review.invalid_rating');
        }

        return $rating;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!array_key_exists($status, ReviewModel::publishTypes())) {
            throw new BadRequestException('review.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfAllowEdit(ReviewModel $review): void
    {
        $case = time() - $review->create_time > 7 * 86400;

        if ($case) {
            throw new BadRequestException('review.edit_not_allowed');
        }
    }

}
