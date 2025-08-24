<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Library\Utils\Password as PasswordUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Account as AccountModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;

class Account extends Validator
{

    public function checkAccount(string $email): AccountModel
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByEmail($email);

        if (!$account) {
            throw new BadRequestException('account.not_found');
        }

        return $account;
    }

    public function checkEmail(string $email): string
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException('account.invalid_email');
        }

        return $email;
    }

    public function checkPassword(string $password): string
    {
        if (!CommonValidator::password($password)) {
            throw new BadRequestException('account.invalid_pwd');
        }

        return $password;
    }

    public function checkConfirmPassword(string $newPassword, string $confirmPassword): void
    {
        if ($newPassword != $confirmPassword) {
            throw new BadRequestException('account.pwd_not_match');
        }
    }

    public function checkOriginPassword(AccountModel $account, string $password): void
    {
        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.origin_pwd_incorrect');
        }
    }

    public function checkLoginPassword(AccountModel $account, string $password): void
    {
        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.login_pwd_incorrect');
        }
    }

    public function checkIfEmailTaken(string $email): void
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByEmail($email);

        if ($account) {
            throw new BadRequestException('account.email_taken');
        }
    }

    public function checkRegisterStatus(): void
    {
        $settings = $this->getSettings('site');

        if ($settings['allow_register'] == 0) {
            throw new BadRequestException('account.register_disabled');
        }
    }

    public function checkUserLogin(string $email, string $password): UserModel
    {
        $account = $this->checkAccount($email);

        if (!PasswordUtil::checkHash($password, $account->salt, $account->password)) {
            throw new BadRequestException('account.login_pwd_incorrect');
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        if ($user->deleted == 1) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkAdminLogin(string $email, string $password): UserModel
    {
        $user = $this->checkUserLogin($email, $password);

        if ($user->admin_role == 0) {
            throw new ForbiddenException('sys.forbidden');
        }

        return $user;
    }

    public function checkIfAllowLogin(UserModel $user): void
    {
        $locked = false;

        if ($user->locked == 1) {
            if ($user->lock_expiry_time == 0) {
                $locked = true;
            } elseif ($user->lock_expiry_time > time()) {
                $locked = true;
            }
        }

        if ($locked) {
            throw new BadRequestException('account.locked');
        }
    }

}
