<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Page as PageService;

/**
 * @RoutePrefix("/admin/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/list", name="admin.page.list")
     */
    public function listAction()
    {
        $pageService = new PageService();

        $pager = $pageService->getPages();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.page.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/add", name="admin.page.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.page.create")
     */
    public function createAction()
    {
        $pageService = new PageService();

        $page = $pageService->createPage();

        $location = $this->url->get([
            'for' => 'admin.page.edit',
            'id' => $page->id,
        ]);

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.page.edit")
     */
    public function editAction($id)
    {
        $pageService = new PageService();

        $page = $pageService->getPage($id);

        $this->view->setVar('page', $page);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.page.update")
     */
    public function updateAction($id)
    {
        $pageService = new PageService();

        $pageService->updatePage($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.page.delete")
     */
    public function deleteAction($id)
    {
        $pageService = new PageService();

        $pageService->deletePage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.page.restore")
     */
    public function restoreAction($id)
    {
        $pageService = new PageService();

        $pageService->restorePage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
