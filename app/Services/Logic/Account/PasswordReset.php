<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class PasswordReset extends LogicService
{

    public function handle(): AccountModel
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkAccount($post['email']);
        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkEmailCode($post['email'], $post['verify_code']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;
        $account->update();

        return $account;
    }

}
