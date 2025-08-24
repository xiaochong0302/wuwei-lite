<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class Task extends Model
{

    /**
     * 任务类型
     */
    const TYPE_DELIVER = 1; // 发货
    const TYPE_REFUND = 2; // 退款

    const TYPE_CREATE_HLS_WATERMARK = 11; // 创建HLS水印
    const TYPE_REMOVE_HLS_WATERMARK = 12; // 删除HLS水印
    const TYPE_TRANS_STANDARD = 13; // 标准转码
    const TYPE_TRANS_ENCRYPT = 14; // 加密转码

    const TYPE_NOTICE_ORDER_FINISH = 21; // 订单完成通知
    const TYPE_NOTICE_REFUND_FINISH = 22; // 退款完成通知
    const TYPE_NOTICE_ACCOUNT_LOGIN = 23; // 帐号登录通知
    const TYPE_NOTICE_STUDENT_LIVE = 24; // 直播学员通知
    const TYPE_NOTICE_TEACHER_LIVE = 25; // 直播讲师通知
    const TYPE_NOTICE_REVIEW_REMIND = 26; // 评价提醒通知

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 10; // 高
    const PRIORITY_MIDDLE = 20; // 中
    const PRIORITY_LOW = 30; // 低

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待定
    const STATUS_FINISHED = 2; // 完成
    const STATUS_CANCELED = 3; // 取消
    const STATUS_FAILED = 4; // 失败

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 条目编号
     *
     * @var int
     */
    public int $item_id = 0;

    /**
     * 条目类型
     *
     * @var int
     */
    public int $item_type = 0;

    /**
     * 条目内容
     *
     * @var array|string
     */
    public string|array $item_info = [];

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = self::PRIORITY_LOW;

    /**
     * 状态标识
     *
     * @var int
     */
    public int $status = self::STATUS_PENDING;

    /**
     * 锁定标识
     *
     * @var int
     */
    public int $locked = 0;

    /**
     * 重试次数
     *
     * @var int
     */
    public int $try_count = 0;

    /**
     * 最大重试次数
     *
     * @var int
     */
    public int $max_try_count = 3;

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

        $this->setSource('kg_task');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function beforeSave(): void
    {
        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

}
