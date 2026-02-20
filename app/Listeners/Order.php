<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use Phalcon\Events\Event as PhEvent;

class Order extends Listener
{

    public function afterPay(PhEvent $event, object $source, OrderModel $order): void
    {
        try {

            $this->db->begin();

            $order->status = OrderModel::STATUS_DELIVERING;
            $order->update();

            $task = new TaskModel();

            $task->item_id = $order->id;
            $task->item_type = TaskModel::TYPE_DELIVER;
            $task->create();

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger();

            $logger->error('Order:afterPay Event Error: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));
        }
    }

}
