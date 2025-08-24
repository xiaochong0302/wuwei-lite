<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Utils;

use App\Models\Course as CourseModel;
use App\Services\Service as AppService;

class CourseScore extends AppService
{

    public function handle(CourseModel $course): void
    {
        $score = $this->calculateCourseScore($course);

        $course->score = $score;

        $course->update();
    }

    protected function calculateCourseScore(CourseModel $course): float
    {
        $weight = [
            'factor1' => 0.1,
            'factor2' => 0.25,
            'factor3' => 0.25,
            'factor4' => 0.15,
            'factor5' => 0.25,
        ];

        $items = [];

        if ($course->featured == 1) {
            $items['factor1'] = 7 * $weight['factor1'];
        }

        if ($course->user_count > 0) {
            $items['factor2'] = log($course->user_count) * $weight['factor2'];
        }

        if ($course->favorite_count > 0) {
            $items['factor3'] = log($course->favorite_count) * $weight['factor3'];
        }

        if ($course->review_count > 0 && $course->rating > 0) {
            $items['factor4'] = log($course->review_count * $course->rating) * $weight['factor4'];
        }

        $sumCount = $course->lesson_count + $course->resource_count;

        if ($sumCount > 0) {
            $items['factor5'] = log($sumCount) * $weight['factor5'];
        }

        $score = array_sum($items) / log(time() - $course->create_time);

        return round($score, 4);
    }

}
