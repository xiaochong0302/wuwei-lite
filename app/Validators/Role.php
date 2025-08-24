<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Role as RoleModel;
use App\Repos\Role as RoleRepo;

class Role extends Validator
{

    public function checkRole(int $id): RoleModel
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($id);

        if (!$role) {
            throw new BadRequestException('role.not_found');
        }

        return $role;
    }

    public function checkName(string $name): string
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('role.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('role.name_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('role.summary_too_long');
        }

        return $value;
    }

    public function checkRoutes(array $routes): array
    {
        if (empty($routes)) {
            throw new BadRequestException('role.routes_required');
        }

        return array_values($routes);
    }

}
