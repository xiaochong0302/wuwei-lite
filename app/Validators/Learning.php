<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;

class Learning extends Validator
{

    public function checkRequestId(string $requestId): string
    {
        if (!$requestId) {
            throw new BadRequestException('learning.invalid_request_id');
        }

        return $requestId;
    }

    public function checkIntervalTime(int $intervalTime): int
    {
        $value = $this->filter->sanitize($intervalTime, ['trim', 'int']);

        /**
         * 兼容秒和毫秒
         */
        if ($value > 1000) {
            $value = intval($value / 1000);
        }

        if ($value < 5) {
            throw new BadRequestException('learning.invalid_interval_time');
        }

        return $value;
    }

    public function checkPosition(float $position): float
    {
        $value = $this->filter->sanitize($position, ['trim', 'float']);

        if ($value < 0 || $value > 3 * 3600) {
            throw new BadRequestException('learning.invalid_position');
        }

        return (float)$value;
    }

}
