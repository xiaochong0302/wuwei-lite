<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Refund extends Model
{

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待处理
    const STATUS_CANCELED = 2; // 已取消
    const STATUS_APPROVED = 3; // 已审核
    const STATUS_REJECTED = 4; // 已拒绝
    const STATUS_FINISHED = 5; // 已完成
    const STATUS_FAILED = 6; // 已失败

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $owner_id = 0;

    /**
     * 订单编号
     *
     * @var int
     */
    public int $order_id = 0;

    /**
     * 序号
     *
     * @var string
     */
    public string $sn = '';

    /**
     * 主题
     *
     * @var string
     */
    public string $subject = '';

    /**
     * 金额
     *
     * @var float
     */
    public float $amount = 0.00;

    /**
     * 货币
     *
     * @var string
     */
    public string $currency = 'USD';

    /**
     * 状态类型
     *
     * @var int
     */
    public int $status = self::STATUS_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 申请备注
     *
     * @var string
     */
    public string $apply_note = '';

    /**
     * 审核备注
     *
     * @var string
     */
    public string $review_note = '';

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

        $this->setSource('kg_refund');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->sn = $this->getRefundSn();

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function afterSave(): void
    {
        if ($this->hasUpdated('status')) {
            $refundStatus = new RefundStatus();
            $refundStatus->refund_id = $this->id;
            $refundStatus->status = $this->getSnapshotData()['status'];
            $refundStatus->create();
        }
    }

    public static function statusTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::STATUS_PENDING => $locale->query('status_pending'),
            self::STATUS_CANCELED => $locale->query('status_canceled'),
            self::STATUS_APPROVED => $locale->query('status_approved'),
            self::STATUS_REJECTED => $locale->query('status_rejected'),
            self::STATUS_FINISHED => $locale->query('status_finished'),
            self::STATUS_FAILED => $locale->query('status_failed'),
        ];
    }

    protected function getRefundSn(): string
    {
        $sn = sprintf('RD-%s-%s', date('YmdHis'), rand(1000, 9999));

        $order = self::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        if (!$order) return $sn;

        return $this->getRefundSn();
    }

}
