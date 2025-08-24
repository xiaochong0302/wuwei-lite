<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\OrderList as OrderListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Http\Admin\Services\Traits\OrderSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Validators\Order as OrderValidator;
use Phalcon\Paginator\RepositoryInterface;

class Order extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;

    public function getItemTypes(): array
    {
        return OrderModel::itemTypes();
    }

    public function getStatusTypes(): array
    {
        return OrderModel::statusTypes();
    }

    public function getPaymentTypes(): array
    {
        return OrderModel::paymentTypes();
    }

    public function getOrders(): RepositoryInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);

        if (!empty($params['order_id'])) {
            $params['id'] = $params['order_id'];
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function getStatusHistory(int $id)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findStatusHistory($id);
    }

    public function getRefunds(string $sn)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findRefunds($sn);
    }

    public function getUser(int $userId): UserModel
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($userId);
    }

    public function getAccount(int $userId): AccountModel
    {
        $accountRepo = new AccountRepo();

        return $accountRepo->findById($userId);
    }

    public function getOrder(int $id): OrderModel
    {
        return $this->findOrFail($id);
    }

    protected function findOrFail(int $id): OrderModel
    {
        $validator = new OrderValidator();

        return $validator->checkOrderById($id);
    }

    protected function handleOrders(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new OrderListBuilder();

            $items = $pager->getItems()->toArray();

            $pipeA = $builder->handleItems($items);
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->setItems($pipeC);
        }

        return $pager;
    }

}
