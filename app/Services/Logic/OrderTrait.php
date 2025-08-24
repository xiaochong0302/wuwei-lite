<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Validators\Order as OrderValidator;

trait OrderTrait
{

    protected function checkOrderById($id)
    {
        $validator = new OrderValidator();

        return $validator->checkOrderById($id);
    }

    protected function checkOrderBySn($sn)
    {
        $validator = new OrderValidator();

        return $validator->checkOrderBySn($sn);
    }

}
