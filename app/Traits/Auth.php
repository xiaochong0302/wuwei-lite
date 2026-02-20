<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Caches\User as UserCache;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Validators\Validator as AppValidator;
use Phalcon\Di\Di;

trait Auth
{

    public function getCurrentUser(bool $cache = false): UserModel
    {
        $authUser = $this->getAuthUser();

        if (!$authUser) {
            return $this->getGuestUser();
        }

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authUser['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authUser['id']);
        }

        return $user;
    }

    public function getLoginUser(bool $cache = false): UserModel
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser['id']);

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authUser['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authUser['id']);
        }

        return $user;
    }

    protected function getGuestUser(): UserModel
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';
        $user->avatar = kg_cos_user_avatar_url('');

        return $user;
    }

    protected function getAuthUser(): ?array
    {
        /**
         * @var AuthService $auth
         */
        $auth = Di::getDefault()->get('auth');

        return $auth->getAuthInfo();
    }

}
