<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Slide as SlideService;

/**
 * @RoutePrefix("/admin/slide")
 */
class SlideController extends Controller
{

    /**
     * @Get("/list", name="admin.slide.list")
     */
    public function listAction()
    {
        $slideService = new SlideService();

        $pager = $slideService->getSlides();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.slide.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/add", name="admin.slide.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.slide.create")
     */
    public function createAction()
    {
        $slideService = new SlideService();

        $slide = $slideService->createSlide();

        $location = $this->url->get([
            'for' => 'admin.slide.edit',
            'id' => $slide->id,
        ]);

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.slide.edit")
     */
    public function editAction($id)
    {
        $slideService = new SlideService();

        $slide = $slideService->getSlide($id);

        $this->view->setVar('slide', $slide);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.slide.update")
     */
    public function updateAction($id)
    {
        $slideService = new SlideService();

        $slideService->updateSlide($id);

        $content = [
            'location' => $this->url->get(['for' => 'admin.slide.list']),
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.slide.delete")
     */
    public function deleteAction($id)
    {
        $slideService = new SlideService();

        $slideService->deleteSlide($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.slide.restore")
     */
    public function restoreAction($id)
    {
        $slideService = new SlideService();

        $slideService->restoreSlide($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
