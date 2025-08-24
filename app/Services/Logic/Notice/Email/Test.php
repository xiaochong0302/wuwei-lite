<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice\Email;

use App\Services\Mailer;

class Test extends Mailer
{
    /**
     * @var string
     */
    protected string $templateId = 'test';

    public function handle(string $email): bool
    {
        $subject = $this->getSubject($this->templateId);

        $content = $this->getContent($this->templateId);

        return $this->send($email, $subject, $content);
    }

}
