<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\User as UserModel;

abstract class PayCalculator
{

    /**
     * @var float 商品总价
     */
    protected float $totalAmount = 0.00;

    /**
     * @var float 支付金额
     */
    protected float $payAmount = 0.00;

    /**
     * @var float 会员优惠金额
     */
    protected float $vipDiscountAmount = 0.00;

    /**
     * @var float 其它优惠金额（比如组合销售）
     */
    protected float $otherDiscountAmount = 0.00;

    /**
     * @var float 总优惠金额
     */
    protected float $totalDiscountAmount = 0.00;

    public function getTotalAmount(): float
    {
        return round($this->totalAmount, 2);
    }

    public function getPayAmount(): float
    {
        return round($this->payAmount, 2);
    }

    public function getTotalDiscountAmount(): float
    {
        return round($this->totalDiscountAmount, 2);
    }

    public function getVipDiscountAmount(): float
    {
        return round($this->vipDiscountAmount, 2);
    }

    public function getOtherDiscountAmount(): float
    {
        return round($this->otherDiscountAmount, 2);
    }

    abstract public function handleNormalPay(UserModel $user): void;

}
