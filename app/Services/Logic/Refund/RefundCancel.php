<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Refund;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Services\Logic\RefundTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Refund as RefundValidator;

class RefundCancel extends LogicService
{

    use RefundTrait;

    public function handle(string $sn): RefundModel
    {
        $refund = $this->checkRefundBySn($sn);

        $user = $this->getLoginUser();

        $validator = new RefundValidator();

        $validator->checkIfAllowCancel($refund);

        $validator->checkOwner($user->id, $refund->owner_id);

        $refund->status = RefundModel::STATUS_CANCELED;

        $refund->update();

        $refundRepo = new RefundRepo();

        $refundTask = $refundRepo->findLastRefundTask($refund->id);

        if ($refundTask) {
            $refundTask->status = TaskModel::STATUS_CANCELED;
            $refundTask->update();
        }

        return $refund;
    }

}
