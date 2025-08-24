<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Models\Role as RoleModel;
use App\Repos\Role as RoleRepo;
use App\Validators\Role as RoleValidator;

class Role extends Service
{

    public function getAuthNodes(): array
    {
        $authNode = new AuthNode();

        return $authNode->getNodes();
    }

    public function getRoles(): array
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $roleRepo = new RoleRepo();

        return $roleRepo->findAll([
            'deleted' => $deleted
        ])->toArray();
    }

    public function getRole(int $id): RoleModel
    {
        return $this->findOrFail($id);
    }

    public function createRole(): RoleModel
    {
        $post = $this->request->getPost();

        $validator = new RoleValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['summary'] = $validator->checkSummary($post['summary']);
        $data['type'] = RoleModel::TYPE_CUSTOM;

        $role = new RoleModel();

        $role->assign($data);

        $role->create();

        return $role;
    }

    public function updateRole(int $id): RoleModel
    {
        $role = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RoleValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['summary'] = $validator->checkSummary($post['summary']);

        if (isset($post['routes'])) {
            $data['routes'] = $validator->checkRoutes($post['routes']);
            $data['routes'] = $this->handleRoutes($data['routes']);
        }

        $role->assign($data);

        $role->update();

        return $role;
    }

    public function deleteRole(int $id): RoleModel
    {
        $role = $this->findOrFail($id);

        if ($role->type == RoleModel::TYPE_SYSTEM) {
            return $role;
        }

        $role->deleted = 1;

        $role->update();

        return $role;
    }

    public function restoreRole(int $id): RoleModel
    {
        $role = $this->findOrFail($id);

        $role->deleted = 0;

        $role->update();

        return $role;
    }

    protected function findOrFail(int $id): RoleModel
    {
        $validator = new RoleValidator();

        return $validator->checkRole($id);
    }

    /**
     * 处理路由权限（补充关联权限）
     *
     * 新增操作 => 补充列表权限
     * 修改操作 => 补充列表权限
     * 删除操作 => 补充还原权限
     * 搜索操作 => 补充列表权限
     *
     * @param array $routes
     * @return array
     */
    protected function handleRoutes(array $routes): array
    {
        if (count($routes) == 0) {
            return [];
        }

        $list = [];

        foreach ($routes as $route) {
            $list [] = $route;
            if (strpos($route, '.add')) {
                $list[] = str_replace('.add', '.create', $route);
                $list[] = str_replace('.add', '.list', $route);
            } elseif (strpos($route, '.edit')) {
                $list[] = str_replace('.edit', '.update', $route);
                $list[] = str_replace('.edit', '.list', $route);
            } elseif (strpos($route, '.delete')) {
                $list[] = str_replace('.delete', '.restore', $route);
                $list[] = str_replace('.delete', '.batch_delete', $route);
            } elseif (strpos($route, '.moderate')) {
                $list[] = str_replace('.moderate', '.batch_moderate', $route);
            } elseif (strpos($route, '.search')) {
                $list[] = str_replace('.search', '.list', $route);
            }
        }

        if (in_array('admin.course.list', $routes)) {
            $list[] = 'admin.course.modules';
            $list[] = 'admin.course.resources';
            $list[] = 'admin.chapter.lessons';
        }

        if (array_intersect(['admin.course.add', 'admin.course.edit'], $routes)) {
            $list[] = 'admin.course.import';
            $list[] = 'admin.chapter.add';
            $list[] = 'admin.chapter.edit';
            $list[] = 'admin.chapter.create';
            $list[] = 'admin.chapter.update';
            $list[] = 'admin.chapter.content';
            $list[] = 'admin.resource.create';
            $list[] = 'admin.resource.update';
            $list[] = 'admin.media.transcode';
            $list[] = 'admin.media.delete';
        }

        if (in_array('admin.course.delete', $routes)) {
            $list[] = 'admin.chapter.delete';
            $list[] = 'admin.chapter.restore';
            $list[] = 'admin.resource.delete';
            $list[] = 'admin.resource.restore';
        }

        if (in_array('admin.course.users', $routes)) {
            $list[] = 'admin.course.add_user';
            $list[] = 'admin.course.create_user';
            $list[] = 'admin.course.search_user';
        }

        if (in_array('admin.user.show', $routes)) {
            $list[] = 'admin.course.learnings';
            $list[] = 'admin.user.courses';
            $list[] = 'admin.user.onlines';
        }

        $list = array_unique($list);

        return array_values($list);
    }

}
