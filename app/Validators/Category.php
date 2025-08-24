<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;

class Category extends Validator
{

    public function checkCategoryCache(int $id): CategoryModel
    {
        $this->checkId($id);

        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkCategory(int $id): CategoryModel
    {
        $this->checkId($id);

        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkId(int $id): void
    {
        $maxIdCache = new MaxCategoryIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('category.not_found');
        }
    }

    public function checkParent(int $id): CategoryModel
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new BadRequestException('category.parent_not_found');
        }

        return $category;
    }

    public function checkName(string $name): string
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('category.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('category.name_too_long');
        }

        return $value;
    }

    public function checkPriority(int $priority): int
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('category.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('category.invalid_publish_status');
        }

        return $status;
    }

    public function checkDeleteAbility(CategoryModel $category)
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll([
            'parent_id' => $category->id,
            'deleted' => 0,
        ]);

        if ($categories->count() > 0) {
            throw new BadRequestException('category.has_child_node');
        }
    }

}
