<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Services\Sync\CourseIndex as CourseIndexSync;
use App\Services\Sync\CourseScore as CourseScoreSync;
use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Support\HelperFactory;

class Course extends Model
{

    /**
     * 级别
     */
    const LEVEL_JUNIOR = 1; // 初级
    const LEVEL_MEDIUM = 2; // 中级
    const LEVEL_SENIOR = 3; // 高级

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public string $cover = '';

    /**
     * 简介
     *
     * @var string
     */
    public string $summary = '';

    /**
     * slug
     *
     * @var string
     */
    public string $slug = '';

    /**
     * 关键字
     *
     * @var string
     */
    public string $keywords = '';

    /**
     * 详情
     *
     * @var string
     */
    public string $details = '';

    /**
     * 分类编号
     *
     * @var int
     */
    public int $category_id = 0;

    /**
     * 教师编号
     *
     * @var int
     */
    public int $teacher_id = 0;

    /**
     * 学习期限（月）
     *
     * @var int
     */
    public int $study_expiry = 12;

    /**
     * 退款期限（天）
     *
     * @var int
     */
    public int $refund_expiry = 7;

    /**
     * 用户评价
     *
     * @var float
     */
    public float $rating = 5.00;

    /**
     * 综合得分
     *
     * @var float
     */
    public float $score = 0.00;

    /**
     * 日常价格
     *
     * @var float
     */
    public float $regular_price = 0.00;

    /**
     * 会员价格
     *
     * @var float
     */
    public float $vip_price = 0.00;

    /**
     * 难度级别
     *
     * @var int
     */
    public int $level = self::LEVEL_JUNIOR;

    /**
     * 推荐标识
     *
     * @var int
     */
    public int $featured = 0;

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 开启真人验证
     *
     * @var int
     */
    public int $human_verify_enabled = 0;

    /**
     * 开启评价
     *
     * @var int
     */
    public int $review_enabled = 1;

    /**
     * 开启评论
     *
     * @var int
     */
    public int $comment_enabled = 1;

    /**
     * 套餐数
     *
     * @var int
     */
    public int $package_count = 0;

    /**
     * 资源数
     *
     * @var int
     */
    public int $resource_count = 0;

    /**
     * 学员数
     *
     * @var int
     */
    public int $user_count = 0;

    /**
     * 课时数
     *
     * @var int
     */
    public int $lesson_count = 0;

    /**
     * 评价数
     *
     * @var int
     */
    public int $review_count = 0;

    /**
     * 收藏数
     *
     * @var int
     */
    public int $favorite_count = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_course');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        if (time() - $this->update_time > 3 * 3600) {
            $sync = new CourseIndexSync();
            $sync->addItem($this->id);

            $sync = new CourseScoreSync();
            $sync->addItem($this->id);
        }

        $this->update_time = time();
    }

    /**
     * 注意：beforeSave在beforeCreate和beforeUpdate之前调用
     */
    public function beforeSave(): void
    {
        $helper = new HelperFactory();

        $this->slug = kg_parse_slug($this->title);

        if (empty($this->cover)) {
            $this->cover = kg_default_course_cover_path();
        } elseif ($helper->startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (empty($this->summary)) {
            $this->summary = kg_parse_summary($this->details);
        }
    }

    public function afterCreate(): void
    {
        $cache = new MaxCourseIdCache();

        $cache->rebuild();
    }

    public function afterFetch(): void
    {
        $helper = new HelperFactory();

        if (!$helper->startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_course_cover_url($this->cover);
        }
    }

    public static function getCoverPath($url): string
    {
        $helper = new HelperFactory();

        if ($helper->startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function levelTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::LEVEL_JUNIOR => $locale->query('course_level_junior'),
            self::LEVEL_MEDIUM => $locale->query('course_level_medium'),
            self::LEVEL_SENIOR => $locale->query('course_level_senior'),
        ];
    }

    public static function sortTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            'latest' => $locale->query('course_sort_latest'),
            'rating' => $locale->query('course_sort_rating'),
            'popular' => $locale->query('course_sort_popular'),
            'featured' => $locale->query('course_sort_featured'),
            'free' => $locale->query('course_sort_free'),
        ];
    }

    public static function studyExpiryOptions(): array
    {
        return [1, 3, 6, 12, 24, 36, 48];
    }

    public static function refundExpiryOptions(): array
    {
        return [1, 7, 14, 30];
    }

}
