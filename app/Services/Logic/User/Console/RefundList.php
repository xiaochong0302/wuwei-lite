<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Builders\RefundList as RefundListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Refund as RefundRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;
use App\Validators\Refund as RefundValidator;
use Phalcon\Paginator\RepositoryInterface;

class RefundList extends LogicService
{

    use UserTrait;

    public function handle(): RepositoryInterface
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $validator = new RefundValidator();

        if (!empty($params['status'])) {
            $params['status'] = $validator->checkStatus($params['status']);
        }

        $params['owner_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    protected function handleRefunds(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new RefundListBuilder();

        $refunds = $pager->getItems()->toArray();

        $orders = $builder->getOrders($refunds);

        $items = [];

        foreach ($refunds as $refund) {

            $order = $orders[$refund['order_id']] ?? new \stdClass();

            $me = $builder->handleMeInfo($refund);

            $items[] = [
                'sn' => $refund['sn'],
                'subject' => $refund['subject'],
                'amount' => (float)$refund['amount'],
                'status' => $refund['status'],
                'apply_note' => $refund['apply_note'],
                'review_note' => $refund['review_note'],
                'create_time' => $refund['create_time'],
                'update_time' => $refund['update_time'],
                'order' => $order,
                'me' => $me,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
