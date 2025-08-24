<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\User as UserModel;
use App\Models\Vip as VipModel;

class VipPayCalculator extends PayCalculator
{

    /**
     * @var VipModel
     */
    protected VipModel $vip;

    public function __construct(VipModel $vip)
    {
        $this->vip = $vip;
    }

    public function handleNormalPay(UserModel $user): void
    {
        $this->totalAmount = $this->vip->price;

        $this->payAmount = $this->vip->price;
    }

}
