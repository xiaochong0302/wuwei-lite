<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Repos\Account as AccountRepo;
use App\Models\Account as AccountModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;

class PasswordUpdate extends LogicService
{

    public function handle():AccountModel
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        $accountValidator = new AccountValidator();

        $accountValidator->checkOriginPassword($account, $post['origin_password']);

        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $accountValidator->checkConfirmPassword($post['new_password'], $post['confirm_password']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;

        $account->update();

        return $account;
    }

}
