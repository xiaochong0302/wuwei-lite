<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\MaxUserId as MaxUserIdCache;
use App\Caches\User as UserCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;

class User extends Validator
{

    public function checkUserCache(int $id): UserModel
    {
        $this->checkId($id);

        $userCache = new UserCache();

        $user = $userCache->get($id);

        if (!$user) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkUser(int $id): UserModel
    {
        $this->checkId($id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        if (!$user) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkTeacher($id)
    {
        $validator = new User();

        $user = $validator->checkUser($id);

        if ($user->edu_role != UserModel::EDU_ROLE_TEACHER) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkId(int $id): void
    {
        $maxUserIdCache = new MaxUserIdCache();

        $maxUserId = $maxUserIdCache->get();

        if ($id < 1 || $id > $maxUserId) {
            throw new BadRequestException('user.not_found');
        }
    }

    public function checkName(string $name): string
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('user.name_too_short');
        }

        if ($length > 15) {
            throw new BadRequestException('user.name_too_long');
        }

        return $value;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('user.title_too_long');
        }

        return $value;
    }

    public function checkAbout(string $about): string
    {
        $value = $this->filter->sanitize($about, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 1000) {
            throw new BadRequestException('user.about_too_long');
        }

        return $value;
    }

    public function checkAvatar(string $avatar): string
    {
        $value = $this->filter->sanitize($avatar, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('user.invalid_avatar');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkEduRole(int $value): int
    {
        $list = UserModel::eduRoleTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException('user.invalid_edu_role');
        }

        return $value;
    }

    public function checkAdminRole(int $value): int
    {
        if (!$value) return 0;

        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($value);

        if (!$role || $role->deleted == 1) {
            throw new BadRequestException('user.invalid_admin_role');
        }

        return $role->id;
    }

    public function checkVipStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_vip_status');
        }

        return $status;
    }

    public function checkVipExpiryTime(string $expiryTime): int
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_vip_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkLockStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_lock_status');
        }

        return $status;
    }

    public function checkLockExpiryTime(string $expiryTime): int
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_lock_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkIfNameTaken(string $name): void
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByName($name);

        if ($user) {
            throw new BadRequestException('user.name_taken');
        }
    }

}
