<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\UserList as UserListBuilder;
use App\Caches\User as UserCache;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Validators\Account as AccountValidator;
use App\Validators\User as UserValidator;
use Phalcon\Paginator\RepositoryInterface;

class User extends Service
{

    use AccountSearchTrait;

    public function getEduRoleTypes(): array
    {
        return UserModel::eduRoleTypes();
    }

    public function getAdminRoles(): array
    {
        $roleRepo = new RoleRepo();

        return $roleRepo->findAll(['deleted' => 0])->toArray();
    }

    public function getUsers(): RepositoryInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $userRepo = new UserRepo();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    public function getUser(int $id): UserModel
    {
        return $this->findOrFail($id);
    }

    public function getAccount(int $id): AccountModel
    {
        $accountRepo = new AccountRepo();

        return $accountRepo->findById($id);
    }

    public function createUser(): UserModel
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $email = $accountValidator->checkEmail($post['email']);
        $password = $accountValidator->checkPassword($post['password']);

        $accountValidator->checkIfEmailTaken($email);

        $userValidator = new UserValidator();

        $eduRole = $userValidator->checkEduRole($post['edu_role']);
        $adminRole = $userValidator->checkAdminRole($post['admin_role']);

        try {

            $this->db->begin();

            $account = new AccountModel();

            $salt = PasswordUtil::salt();
            $password = PasswordUtil::hash($password, $salt);

            $account->email = $email;
            $account->salt = $salt;
            $account->password = $password;

            if ($account->create() === false) {
                throw new \RuntimeException('Create Account Failed');
            }

            $user = $this->findOrFail($account->id);

            $user->admin_role = $adminRole;
            $user->edu_role = $eduRole;

            if ($user->update() === false) {
                throw new \RuntimeException('Update User Failed');
            }

            $this->db->commit();

            if ($adminRole > 0) {
                $this->recountRoleUsers($adminRole);
            }

            $this->rebuildUserCache($user);

            return $user;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('http');

            $logger->error('Create User Error ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

    public function updateUser(int $id): UserModel
    {
        $user = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new UserValidator();

        $data = [];

        if (isset($post['avatar'])) {
            $data['avatar'] = $validator->checkAvatar($post['avatar']);
        }

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
            if ($post['name'] != $user->name) {
                $validator->checkIfNameTaken($post['name']);
            }
        }

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (isset($post['edu_role'])) {
            $data['edu_role'] = $validator->checkEduRole($post['edu_role']);
        }

        if (isset($post['admin_role'])) {
            $data['admin_role'] = $validator->checkAdminRole($post['admin_role']);
        }

        if (isset($post['vip'])) {
            $data['vip'] = $validator->checkVipStatus($post['vip']);
        }

        if (isset($post['locked'])) {
            $data['locked'] = $validator->checkLockStatus($post['locked']);
        }

        if (!empty($post['vip_expiry_time'])) {
            $data['vip_expiry_time'] = $validator->checkVipExpiryTime($post['vip_expiry_time']);
            if ($data['vip_expiry_time'] < time()) {
                $data['vip'] = 0;
            }
        }

        if (!empty($post['lock_expiry_time'])) {
            $data['lock_expiry_time'] = $validator->checkLockExpiryTime($post['lock_expiry_time']);
            if ($data['lock_expiry_time'] < time()) {
                $data['locked'] = 0;
            }
        }

        $oldAdminRole = $user->admin_role;

        $user->assign($data);

        $user->update();

        if ($oldAdminRole > 0) {
            $this->recountRoleUsers($oldAdminRole);
        }

        if ($user->admin_role > 0) {
            $this->recountRoleUsers($user->admin_role);
        }

        $this->rebuildUserCache($user);

        return $user;
    }

    public function updateAccount(int $id): AccountModel
    {
        $post = $this->request->getPost();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($id);

        $validator = new AccountValidator();

        $data = [];

        if (!empty($post['email'])) {
            $data['email'] = $validator->checkEmail($post['email']);
            if ($post['email'] != $account->email) {
                $validator->checkIfEmailTaken($post['email']);
            }
        }

        if (!empty($post['password'])) {
            $post['password'] = $validator->checkPassword($post['password']);
            $data['salt'] = PasswordUtil::salt();
            $data['password'] = PasswordUtil::hash($post['password'], $data['salt']);
        }

        $account->assign($data);

        $account->update();

        return $account;
    }

    protected function findOrFail(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    protected function rebuildUserCache(UserModel $user): void
    {
        $cache = new UserCache();

        $cache->rebuild($user->id);
    }

    protected function recountRoleUsers(int $roleId): void
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($roleId);

        if (!$role) return;

        $userCount = $roleRepo->countUsers($roleId);

        $role->user_count = $userCount;

        $role->update();
    }

    protected function handleUsers(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new UserListBuilder();

            $items = $pager->getItems()->toArray();

            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->handleAccounts($pipeA);
            $pipeC = $builder->handleAdminRoles($pipeB);
            $pipeD = $builder->handleEduRoles($pipeC);
            $pipeE = $builder->objects($pipeD);

            $pager->setItems($pipeE);
        }

        return $pager;
    }

}
