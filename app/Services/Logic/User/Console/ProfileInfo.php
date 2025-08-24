<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;

class ProfileInfo extends LogicService
{

    public function handle(): array
    {
        $user = $this->getLoginUser();

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'locked' => $user->locked,
            'edu_role' => $user->edu_role,
            'admin_role' => $user->admin_role,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
        ];
    }

}
