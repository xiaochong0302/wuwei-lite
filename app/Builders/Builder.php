<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;

class Builder extends Injectable
{

    public function objects(array $items): mixed
    {
        return kg_array_object($items);
    }

    protected function getShallowUserByIds(array $ids): array
    {
        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($ids);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
