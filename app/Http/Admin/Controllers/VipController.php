<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Vip as VipService;

/**
 * @RoutePrefix("/admin/vip")
 */
class VipController extends Controller
{

    /**
     * @Get("/list", name="admin.vip.list")
     */
    public function listAction()
    {
        $vipService = new VipService();

        $pager = $vipService->getVips();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.vip.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.vip.create")
     */
    public function createAction()
    {
        $vipService = new VipService();

        $vipService->createVip();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('created_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.vip.edit")
     */
    public function editAction($id)
    {
        $vipService = new VipService();

        $vip = $vipService->getVip($id);

        $this->view->setVar('vip', $vip);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.vip.update")
     */
    public function updateAction($id)
    {
        $vipService = new VipService();

        $vipService->updateVip($id);

        $content = [
            'location' => $this->url->get(['for' => 'admin.vip.list']),
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.vip.delete")
     */
    public function deleteAction($id)
    {
        $vipService = new VipService();

        $vipService->deleteVip($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.vip.restore")
     */
    public function restoreAction($id)
    {
        $vipService = new VipService();

        $vipService->restoreVip($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
