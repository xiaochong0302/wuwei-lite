<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services\Traits;

use App\Repos\Order as OrderRepo;

trait OrderSearchTrait
{

    protected function handleOrderSearchParams(array $params): array
    {
        /**
         * 兼容订单编号或订单序号查询
         */
        if (!empty($params['order_id']) && strlen($params['order_id']) > 10) {

            $orderRepo = new OrderRepo();

            $order = $orderRepo->findBySn($params['order_id']);

            $params['order_id'] = $order ? $order->id : -1000;
        }

        return $params;
    }

}
