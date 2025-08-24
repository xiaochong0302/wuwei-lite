<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Package as PackageService;

/**
 * @RoutePrefix("/admin/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/list", name="admin.package.list")
     */
    public function listAction()
    {
        $packageService = new PackageService();

        $pager = $packageService->getPackages();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.package.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/add", name="admin.package.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.package.create")
     */
    public function createAction()
    {
        $packageService = new PackageService();

        $package = $packageService->createPackage();

        $location = $this->url->get([
            'for' => 'admin.package.edit',
            'id' => $package->id,
        ]);

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.package.edit")
     */
    public function editAction($id)
    {
        $packageService = new PackageService();

        $package = $packageService->getPackage($id);
        $xmCourses = $packageService->getXmCourses($id);

        $this->view->setVar('package', $package);
        $this->view->setVar('xm_courses', $xmCourses);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.package.update")
     */
    public function updateAction($id)
    {
        $packageService = new PackageService();

        $packageService->updatePackage($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.package.delete")
     */
    public function deleteAction($id)
    {
        $packageService = new PackageService();

        $packageService->deletePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.package.restore")
     */
    public function restoreAction($id)
    {
        $packageService = new PackageService();

        $packageService->restorePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
