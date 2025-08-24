<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Vip;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class UserList extends LogicService
{

    public function handle(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['vip'] = 1;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $userRepo = new UserRepo();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $users = $pager->getItems()->toArray();

        $baseUrl = kg_cos_url();

        $items = [];

        foreach ($users as $user) {

            $user['avatar'] = $baseUrl . $user['avatar'];

            $items[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'title' => $user['title'],
                'about' => $user['about'],
                'vip' => $user['vip'],
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
