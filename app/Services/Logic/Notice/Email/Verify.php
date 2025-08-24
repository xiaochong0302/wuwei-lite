<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice\Email;

use App\Services\Mailer;
use App\Services\Verify as VerifyService;

class Verify extends Mailer
{

    /**
     * @var string
     */
    protected string $templateId = 'verify';

    public function handle(string $email): bool
    {
        $minutes = 5;

        $verify = new VerifyService();

        $code = $verify->getEmailCode($email, 60 * $minutes);

        $subject = $this->getSubject($this->templateId);

        $placeholders = [
            'verify.code' => $code,
            'verify.minutes' => $minutes,
        ];

        $content = $this->getContent($this->templateId, $placeholders);

        return $this->send($email, $subject, $content);
    }

}
