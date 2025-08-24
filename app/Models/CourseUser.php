<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;

class CourseUser extends Model
{

    /**
     * 来源类型
     */
    const JOIN_TYPE_FREE = 1; // 免费
    const JOIN_TYPE_TRIAL = 2; // 试听
    const JOIN_TYPE_PURCHASE = 3; // 付费
    const JOIN_TYPE_VIP = 4; // 畅学
    const JOIN_TYPE_MANUAL = 5; // 分配
    const JOIN_TYPE_TEACHER = 6; // 教师

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $user_id = 0;

    /**
     * 来源类型
     *
     * @var int
     */
    public int $join_type = 0;

    /**
     * 过期时间
     *
     * @var int
     */
    public int $expiry_time = 0;

    /**
     * 学习时长（秒）
     *
     * @var int
     */
    public int $duration = 0;

    /**
     * 学习进度（％）
     *
     * @var int
     */
    public int $progress = 0;

    /**
     * 评价标识
     *
     * @var int
     */
    public int $reviewed = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public int $active_time = 0;

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

        $this->setSource('kg_course_user');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public static function sourceTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::JOIN_TYPE_FREE => $locale->query('cu_join_type_free'),
            self::JOIN_TYPE_TRIAL => $locale->query('cu_join_type_trial'),
            self::JOIN_TYPE_PURCHASE => $locale->query('cu_join_type_purchase'),
            self::JOIN_TYPE_VIP => $locale->query('cu_join_type_vip'),
            self::JOIN_TYPE_MANUAL => $locale->query('cu_join_type_manual'),
            self::JOIN_TYPE_TEACHER => $locale->query('cu_join_type_teacher'),
        ];
    }

}
