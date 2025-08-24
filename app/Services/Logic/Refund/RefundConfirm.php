<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Refund;

use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Refund;

class RefundConfirm extends LogicService
{

    use OrderTrait;

    public function handle(string $sn): array
    {
        $order = $this->checkOrderBySn($sn);

        $service = new Refund();

        return $service->preview($order);
    }

}
