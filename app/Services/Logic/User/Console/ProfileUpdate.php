<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Caches\User as UserCache;
use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\User as UserValidator;

class ProfileUpdate extends LogicService
{

    public function handle(): UserModel
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserValidator();

        $data = [];

        if (!empty($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
            if ($data['name'] != $user->name) {
                $validator->checkIfNameTaken($data['name']);
            }
        }

        if (!empty($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (!empty($post['avatar'])) {
            $data['avatar'] = $validator->checkAvatar($post['avatar']);
        }

        $user->assign($data);

        $user->update();

        $this->rebuildUserCache($user->id);

        return $user;
    }

    protected function rebuildUserCache(int $id): void
    {
        $cache = new UserCache();

        $cache->rebuild($id);
    }

}
