<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Role as RoleService;

/**
 * @RoutePrefix("/admin/role")
 */
class RoleController extends Controller
{

    /**
     * @Get("/list", name="admin.role.list")
     */
    public function listAction()
    {
        $roleService = new RoleService();

        $roles = $roleService->getRoles();

        $this->view->setVar('roles', $roles);
    }

    /**
     * @Get("/add", name="admin.role.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.role.create")
     */
    public function createAction()
    {
        $roleService = new RoleService();

        $role = $roleService->createRole();

        $location = $this->url->get([
            'for' => 'admin.role.edit',
            'id' => $role->id,
        ]);

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.role.edit")
     */
    public function editAction($id)
    {
        $roleService = new RoleService();

        $role = $roleService->getRole($id);
        $authNodes = $roleService->getAuthNodes();

        $this->view->setVar('role', $role);
        $this->view->setVar('auth_nodes', $authNodes);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.role.update")
     */
    public function updateAction($id)
    {
        $roleService = new RoleService();

        $roleService->updateRole($id);

        $content = [
            'location' => $this->url->get(['for' => 'admin.role.list']),
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.role.delete")
     */
    public function deleteAction($id)
    {
        $roleService = new RoleService();

        $roleService->deleteRole($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.role.restore")
     */
    public function restoreAction($id)
    {
        $roleService = new RoleService();

        $roleService->restoreRole($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
