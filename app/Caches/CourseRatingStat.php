<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;

class CourseRatingStat extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return "course-rating-stat-{$id}";
    }

    public function getContent($id = null): array
    {
        $courseRepo = new CourseRepo();

        $reviews = $courseRepo->findReviews($id);

        if ($reviews->count() == 0) {
            return [];
        }

        return $this->handleContent($reviews);
    }

    /**
     * @param $reviews ReviewModel[]
     * @return array
     */
    protected function handleContent($reviews): array
    {
        $result = [
            'star1' => ['count' => 0, 'rate' => 0],
            'star2' => ['count' => 0, 'rate' => 0],
            'star3' => ['count' => 0, 'rate' => 0],
            'star4' => ['count' => 0, 'rate' => 0],
            'star5' => ['count' => 0, 'rate' => 0],
        ];

        $total = 0;

        foreach ($reviews as $review) {
            $key = "star{$review->rating}";
            if (isset($result[$key])) {
                $result[$key]['count']++;
                $total++;
            }
        }

        foreach ($result as $key => $value) {
            $result[$key]['rate'] = round(100 * $value['count'] / $total, 2);
        }

        return $result;
    }

}
