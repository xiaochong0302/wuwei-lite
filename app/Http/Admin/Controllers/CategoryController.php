<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Category as CategoryService;

/**
 * @RoutePrefix("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * @Get("/list", name="admin.category.list")
     */
    public function listAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $categoryService = new CategoryService();

        $parent = $categoryService->getParentCategory($parentId);

        if ($parent->id > 0) {
            $categories = $categoryService->getChildCategories($parentId);
        } else {
            $categories = $categoryService->getTopCategories();
        }

        $this->view->setVar('parent', $parent);
        $this->view->setVar('categories', $categories);
    }

    /**
     * @Get("/add", name="admin.category.add")
     */
    public function addAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $categoryService = new CategoryService();

        $parent = $categoryService->getParentCategory($parentId);

        $topCategories = $categoryService->getTopCategories();

        $this->view->setVar('parent', $parent);
        $this->view->setVar('top_categories', $topCategories);
    }

    /**
     * @Post("/create", name="admin.category.create")
     */
    public function createAction()
    {
        $categoryService = new CategoryService();

        $category = $categoryService->createCategory();

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id],
        );

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.category.edit")
     */
    public function editAction($id)
    {
        $categoryService = new CategoryService();

        $category = $categoryService->getCategory($id);

        $this->view->setVar('category', $category);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.category.update")
     */
    public function updateAction($id)
    {
        $categoryService = new CategoryService();

        $category = $categoryService->getCategory($id);

        $categoryService->updateCategory($id);

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id],
        );

        $msg = $this->locale->query('updated_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.category.delete")
     */
    public function deleteAction($id)
    {
        $categoryService = new CategoryService();

        $categoryService->deleteCategory($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.category.restore")
     */
    public function restoreAction($id)
    {
        $categoryService = new CategoryService();

        $categoryService->restoreCategory($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
