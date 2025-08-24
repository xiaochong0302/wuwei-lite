<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Validators\Refund as RefundValidator;

trait RefundTrait
{

    protected function checkRefundById($id)
    {
        $validator = new RefundValidator();

        return $validator->checkRefundById($id);
    }

    protected function checkRefundBySn($sn)
    {
        $validator = new RefundValidator();

        return $validator->checkRefundBySn($sn);
    }

}
