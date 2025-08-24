<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Refund as RefundService;

/**
 * @RoutePrefix("/admin/refund")
 */
class RefundController extends Controller
{

    /**
     * @Get("/search", name="admin.refund.search")
     */
    public function searchAction()
    {
        $refundService = new RefundService();

        $statusTypes = $refundService->getStatusTypes();

        $this->view->setVar('status_types', $statusTypes);
    }

    /**
     * @Get("/list", name="admin.refund.list")
     */
    public function listAction()
    {
        $refundService = new RefundService();

        $pager = $refundService->getRefunds();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.refund.show")
     */
    public function showAction($id)
    {
        $refundService = new RefundService();

        $refund = $refundService->getRefund($id);
        $order = $refundService->getOrder($refund->order_id);
        $account = $refundService->getAccount($refund->owner_id);
        $user = $refundService->getUser($refund->owner_id);

        $this->view->setVar('refund', $refund);
        $this->view->setVar('order', $order);
        $this->view->setVar('account', $account);
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/status/history", name="admin.refund.status_history")
     */
    public function statusHistoryAction($id)
    {
        $refundService = new RefundService();

        $statusHistory = $refundService->getStatusHistory($id);

        $this->view->pick('refund/status_history');
        $this->view->setVar('status_history', $statusHistory);
    }

    /**
     * @Post("/{id:[0-9]+}/review", name="admin.refund.review")
     */
    public function reviewAction($id)
    {
        $refundService = new RefundService;

        $refundService->reviewRefund($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('submitted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
