<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\Order as OrderModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;

class OrderCancel extends LogicService
{

    use OrderTrait;

    public function handle(string $sn): OrderModel
    {
        $order = $this->checkOrderBySn($sn);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->owner_id);

        $validator->checkIfAllowCancel($order);

        $order->status = OrderModel::STATUS_CLOSED;

        $order->update();

        return $order;
    }

}
