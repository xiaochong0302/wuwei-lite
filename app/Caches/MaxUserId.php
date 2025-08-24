<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\User as UserModel;

class MaxUserId extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 360 * 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'max-user-id';
    }

    public function getContent($id = null): int
    {
        $user = UserModel::findFirst(['order' => 'id DESC']);

        return $user->id ?? 0;
    }

}
