<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Services;

use App\Repos\User as UserRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;

class Account extends Service
{

    /**
     * @var AuthService
     */
    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function register()
    {
        $service = new RegisterService();

        $account = $service->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterRegister', $this, $user);

        return $user;
    }

    public function login()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['email'], $post['password']);

        $validator->checkIfAllowLogin($user);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function logout()
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

}
