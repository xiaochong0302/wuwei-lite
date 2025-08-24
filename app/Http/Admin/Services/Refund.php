<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\RefundList as RefundListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Http\Admin\Services\Traits\OrderSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Validators\Refund as RefundValidator;
use Phalcon\Paginator\RepositoryInterface;

class Refund extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;

    public function getStatusTypes(): array
    {
        return RefundModel::statusTypes();
    }

    public function getRefunds(): RepositoryInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    public function getRefund(int $id): RefundModel
    {
        return $this->findOrFail($id);
    }

    public function getStatusHistory(int $id)
    {
        $refundRepo = new RefundRepo();

        return $refundRepo->findStatusHistory($id);
    }

    public function getOrder(int $orderId): OrderModel
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findById($orderId);
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

    public function reviewRefund(int $id): RefundModel
    {
        $refund = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RefundValidator();

        $validator->checkIfAllowReview($refund);

        $refund->status = $validator->checkReviewStatus($post['review_status']);
        $refund->review_note = $validator->checkReviewNote($post['review_note']);

        try {

            $this->db->begin();

            $refund->update();

            if ($refund->status == RefundModel::STATUS_APPROVED) {

                $task = new TaskModel();

                $task->item_id = $refund->id;
                $task->item_type = TaskModel::TYPE_REFUND;
                $task->priority = TaskModel::PRIORITY_HIGH;
                $task->status = TaskModel::STATUS_PENDING;

                $task->create();
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('refund');

            $logger->error('Refund Review Exception ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'refund' => ['id' => $refund->id],
                ]));

            throw new \RuntimeException('sys.rollback');
        }

        return $refund;
    }

    protected function findOrFail(int $id): RefundModel
    {
        $validator = new RefundValidator();

        return $validator->checkRefundById($id);
    }

    protected function handleRefunds(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new RefundListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
