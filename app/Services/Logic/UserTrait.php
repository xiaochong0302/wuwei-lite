<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\User as UserModel;
use App\Validators\User as UserValidator;

trait UserTrait
{

    protected function checkUser(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    protected function checkUserCache(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUserCache($id);
    }

}
