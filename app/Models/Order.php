<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Order extends Model
{

    /**
     * 支付平台
     */
    const PAYMENT_PAYPAL = 1; // paypal
    const PAYMENT_STRIPE = 2; // stripe

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待支付
    const STATUS_PAID = 2; // 已支付
    const STATUS_DELIVERING = 3; // 发货中
    const STATUS_FINISHED = 4; // 已完成
    const STATUS_CLOSED = 5; // 已关闭
    const STATUS_REFUNDED = 6; // 已退款

    /**
     * paypal特有属性
     *
     * @var array
     */
    public array $_paypal_info = [
        'order_id' => '',
        'capture_id' => '',
        'refund_id' => '',
    ];

    /**
     * stripe特有属性
     *
     * @var array
     */
    public array $_stripe_info = [
        'session_id' => '',
        'payment_intent_id' => '',
        'refund_id' => '',
    ];

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

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
     * 用户编号
     *
     * @var int
     */
    public int $owner_id = 0;

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
     * 条目信息
     *
     * @var array|string
     */
    public string|array $item_info = [];

    /**
     * 优惠券编号
     *
     * @var int
     */
    public int $coupon_id = 0;

    /**
     * 优惠券信息
     *
     * @var array|string
     */
    public string|array $coupon_info = [];

    /**
     * 支付类型
     *
     * @var int
     */
    public int $payment_type = 0;

    /**
     * 支付信息
     *
     * @var array|string
     */
    public string|array $payment_info = [];

    /**
     * 终端类型
     *
     * @var int
     */
    public int $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public string $client_ip = '';

    /**
     * 终端国家
     *
     * @var string
     */
    public string $client_country = '';

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

        $this->setSource('kg_order');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->sn = $this->getOrderSn();

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

        if (is_array($this->coupon_info)) {
            $this->coupon_info = kg_json_encode($this->coupon_info);
        }

        if (is_array($this->payment_info)) {
            $this->payment_info = kg_json_encode($this->payment_info);
        }
    }

    public function afterSave(): void
    {
        if ($this->hasUpdated('status')) {
            $orderStatus = new OrderStatus();
            $orderStatus->order_id = $this->id;
            $orderStatus->status = $this->getSnapshotData()['status'];
            $orderStatus->create();
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }

        if (is_string($this->coupon_info)) {
            $this->coupon_info = json_decode($this->coupon_info, true);
        }

        if (is_string($this->payment_info)) {
            $this->payment_info = json_decode($this->payment_info, true);
        }
    }

    public static function itemTypes(): array
    {
        return KgSale::itemTypes();
    }

    public static function paymentTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::PAYMENT_PAYPAL => $locale->query('payment_paypal'),
            self::PAYMENT_STRIPE => $locale->query('payment_stripe'),
        ];
    }

    public static function statusTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::STATUS_PENDING => $locale->query('status_pending'),
            self::STATUS_PAID => $locale->query('status_paid'),
            self::STATUS_DELIVERING => $locale->query('status_delivering'),
            self::STATUS_FINISHED => $locale->query('status_finished'),
            self::STATUS_CLOSED => $locale->query('status_closed'),
            self::STATUS_REFUNDED => $locale->query('status_refunded'),
        ];
    }

    protected function getOrderSn(): string
    {
        $sn = sprintf('OD-%s-%s', date('YmdHis'), rand(1000, 9999));

        $order = self::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        if (!$order) return $sn;

        return $this->getOrderSn();
    }

}
