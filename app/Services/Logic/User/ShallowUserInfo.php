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

class ShallowUserInfo extends LogicService
{

    use UserTrait;

    public function handle(int $id): array
    {
        $user = $this->checkUserCache($id);

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'vip' => $user->vip,
            'title' => $user->title,
            'about' => $user->about,
        ];
    }

}
