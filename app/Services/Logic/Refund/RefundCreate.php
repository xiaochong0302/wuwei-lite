<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Refund;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Refund as RefundService;
use App\Validators\Order as OrderValidator;
use App\Validators\Refund as RefundValidator;

class RefundCreate extends LogicService
{

    use OrderTrait;

    public function handle(): RefundModel
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->owner_id);

        $validator->checkIfAllowRefund($order);

        $refundService = new RefundService();

        $preview = $refundService->preview($order);

        $refundAmount = $preview['refund_amount'];

        $validator = new RefundValidator();

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $validator->checkAmount($order->amount, $refundAmount);

        try {

            $this->db->begin();

            $refund = new RefundModel();

            $refund->subject = $order->subject;
            $refund->amount = $refundAmount;
            $refund->currency = $order->currency;
            $refund->apply_note = $applyNote;
            $refund->order_id = $order->id;
            $refund->owner_id = $user->id;
            $refund->status = RefundModel::STATUS_APPROVED;

            if ($refund->create() === false) {
                throw new \RuntimeException('Create Refund Failed');
            }

            $task = new TaskModel();

            $task->item_id = $refund->id;
            $task->item_type = TaskModel::TYPE_REFUND;
            $task->priority = TaskModel::PRIORITY_MIDDLE;
            $task->status = TaskModel::STATUS_PENDING;

            if ($task->create() === false) {
                throw new \RuntimeException('Create Refund Task Failed');
            }

            $this->db->commit();

            return $refund;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('refund');

            $logger->error('Create Refund Exception ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
