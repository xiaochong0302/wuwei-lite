<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Resource as ResourceService;

/**
 * @RoutePrefix("/admin/resource")
 */
class ResourceController extends Controller
{

    /**
     * @Post("/create", name="admin.resource.create")
     */
    public function createAction()
    {
        $resourceService = new ResourceService();

        $resourceService->createResource();

        $content = [
            'msg' => $this->locale->query('uploaded_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.resource.update")
     */
    public function updateAction($id)
    {
        $resourceService = new ResourceService();

        $resourceService->updateResource($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.resource.delete")
     */
    public function deleteAction($id)
    {
        $resourceService = new ResourceService();

        $resourceService->deleteResource($id);

        $content = [
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
