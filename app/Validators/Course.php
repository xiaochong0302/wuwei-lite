<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\Course as CourseCache;
use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class Course extends Validator
{

    public function checkCourseCache(int $id): CourseModel
    {
        $this->checkId($id);

        $courseCache = new CourseCache();

        $course = $courseCache->get($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkCourse(int $id): CourseModel
    {
        $this->checkId($id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkId(int $id): void
    {
        $maxIdCache = new MaxCourseIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('course.not_found');
        }
    }

    public function checkCategoryId(int $id): int
    {
        if ($id > 0) {
            $validator = new Category();
            $category = $validator->checkCategory($id);
            return $category->id;
        } else {
            return 0;
        }
    }

    public function checkTeacherId(int $id): int
    {
        if ($id > 0) {
            $validator = new User();
            $user = $validator->checkTeacher($id);
            return $user->id;
        } else {
            return 0;
        }
    }

    public function checkLevel(int $level): int
    {
        $list = CourseModel::levelTypes();

        if (!array_key_exists($level, $list)) {
            throw new BadRequestException('course.invalid_level');
        }

        return $level;
    }

    public function checkCover(string $cover): string
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('course.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('course.title_too_short');
        }

        if ($length > 120) {
            throw new BadRequestException('course.title_too_long');
        }

        return $value;
    }

    public function checkDetails(string $details): string
    {
        $value = $this->filter->sanitize($details, ['trim']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('course.details_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 500) {
            throw new BadRequestException('course.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords(string $keywords): string
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('course.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkRegularPrice(float $price): float
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_regular_price');
        }

        return $value;
    }

    public function checkVipPrice(float $price): float
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_vip_price');
        }

        return $value;
    }

    public function checkStudyExpiry(int $expiry): int
    {
        $options = CourseModel::studyExpiryOptions();

        if (!in_array($expiry, $options)) {
            throw new BadRequestException('course.invalid_study_expiry');
        }

        return $expiry;
    }

    public function checkRefundExpiry(int $expiry): int
    {
        $options = CourseModel::refundExpiryOptions();

        if (!in_array($expiry, $options)) {
            throw new BadRequestException('course.invalid_refund_expiry');
        }

        return $expiry;
    }

    public function checkFeatureStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_feature_status');
        }

        return $status;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_publish_status');
        }

        return $status;
    }

    public function checkHumanVerifyStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_human_verify_status');
        }

        return $status;
    }

    public function checkReviewStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_review_status');
        }

        return $status;
    }

    public function checkCommentStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_comment_status');
        }

        return $status;
    }

    public function checkIfDuplicate(string $title): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findByTitle($title);

        if ($course && $course->deleted == 0) {
            throw new BadRequestException('course.duplicate');
        }
    }

    public function checkOutlineTemplate(string $file): void
    {
        if (!file_exists($file)) {
            throw new BadRequestException('course.outline_tpl_not_existed');
        }
    }

}
