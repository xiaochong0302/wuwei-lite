<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class Validator extends Injectable
{

    use ServiceTrait;

    public function checkAuthUser(int $userId): void
    {
        if (empty($userId)) {
            throw new UnauthorizedException('sys.unauthorized');
        }
    }

    public function checkOwner(int $userId, int $ownerId): void
    {
        if ($userId != $ownerId) {
            throw new ForbiddenException('sys.forbidden');
        }
    }

}
