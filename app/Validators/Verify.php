<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Services\Verify as VerifyService;

class Verify extends Validator
{

    public function checkEmail(string $email): string
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException('verify.invalid_email');
        }

        return $email;
    }

    public function checkEmailCode(string $email, string $code): void
    {
        if (empty($code)) {
            throw new BadRequestException('verify.invalid_email_code');
        }

        $service = new VerifyService();

        $result = $service->checkEmailCode($email, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_email_code');
        }
    }

}
