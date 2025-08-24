<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\User as UserService;
use App\Models\Role as RoleModel;

/**
 * @RoutePrefix("/admin/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/search", name="admin.user.search")
     */
    public function searchAction()
    {
        $userService = new UserService();

        $eduRoles = $userService->getEduRoleTypes();
        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('edu_roles', $eduRoles);
        $this->view->setVar('admin_roles', $adminRoles);
    }

    /**
     * @Get("/list", name="admin.user.list")
     */
    public function listAction()
    {
        $userService = new UserService();

        $pager = $userService->getUsers();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.user.show")
     */
    public function showAction($id)
    {
        $userService = new UserService();

        $account = $userService->getAccount($id);
        $user = $userService->getUser($id);

        $eduRoles = $userService->getEduRoleTypes();
        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('edu_roles', $eduRoles);
        $this->view->setVar('admin_roles', $adminRoles);
        $this->view->setVar('account', $account);
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/add", name="admin.user.add")
     */
    public function addAction()
    {
        $userService = new UserService();

        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('admin_roles', $adminRoles);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.user.edit")
     */
    public function editAction($id)
    {
        $userService = new UserService();

        $user = $userService->getUser($id);
        $account = $userService->getAccount($id);
        $adminRoles = $userService->getAdminRoles();

        $defaultAvatar = kg_cos_user_avatar_url(null);

        if ($user->admin_role == RoleModel::ROLE_ROOT) {
            return $this->response->redirect(['for' => 'admin.user.list']);
        }

        $this->view->setVar('user', $user);
        $this->view->setVar('account', $account);
        $this->view->setVar('admin_roles', $adminRoles);
        $this->view->setVar('default_avatar', $defaultAvatar);
    }

    /**
     * @Post("/create", name="admin.user.create")
     */
    public function createAction()
    {
        $adminRole = $this->request->getPost('admin_role', 'int', 0);

        if ($adminRole == RoleModel::ROLE_ROOT) {
            return $this->response->redirect(['action' => 'list']);
        }

        $userService = new UserService();

        $userService->createUser();

        $content = [
            'location' => $this->url->get(['for' => 'admin.user.list']),
            'msg' => $this->locale->query('created_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.user.update")
     */
    public function updateAction($id)
    {
        $adminRole = $this->request->getPost('admin_role', 'int', 0);

        if ($adminRole == RoleModel::ROLE_ROOT) {
            return $this->response->redirect(['action' => 'list']);
        }

        $type = $this->request->getPost('type', 'string', 'user');

        $userService = new UserService();

        if ($type == 'user') {
            $userService->updateUser($id);
        } else {
            $userService->updateAccount($id);
        }

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
