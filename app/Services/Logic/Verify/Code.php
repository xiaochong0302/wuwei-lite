<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Verify;

use App\Services\Logic\Notice\Email\Verify as EmailVerifyService;
use App\Services\Logic\Service as LogicService;
use App\Validators\Verify as VerifyValidator;

class Code extends LogicService
{

    public function handle(): void
    {
        $post = $this->request->getPost();

        $validator = new VerifyValidator();

        $post['email'] = $validator->checkEmail($post['email']);

        $service = new EmailVerifyService();

        $service->handle($post['email']);
    }

}
