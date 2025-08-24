<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User;

use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class UserInfo extends LogicService
{

    use UserTrait;

    public function handle(int $id): array
    {
        $user = $this->checkUserCache($id);

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'locked' => $user->locked,
            'deleted' => $user->deleted,
            'active_time' => $user->active_time,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
        ];
    }

}
