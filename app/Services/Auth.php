<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Models\User as UserModel;

abstract class Auth extends Service
{

    abstract function saveAuthInfo(UserModel $user): array;

    abstract function getAuthInfo(): array;

    abstract function clearAuthInfo(): void;

}
