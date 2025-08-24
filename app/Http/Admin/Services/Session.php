<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Models\User as UserModel;
use App\Services\Auth\Admin as AdminAuth;
use App\Services\Auth\Home as HomeAuth;
use App\Validators\Account as AccountValidator;

class Session extends Service
{

    /**
     * @var AdminAuth
     */
    protected AdminAuth $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function login(): void
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkAdminLogin($post['email'], $post['password']);

        $validator->checkIfAllowLogin($user);

        $this->auth->saveAuthInfo($user);

        $this->loginHome($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function logout(): void
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

    protected function loginHome(UserModel $user): void
    {
        $auth = new HomeAuth();

        $auth->saveAuthInfo($user);
    }

}
