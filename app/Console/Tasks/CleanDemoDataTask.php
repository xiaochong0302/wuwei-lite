<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Account as AccountModel;
use App\Repos\User as UserRepo;
use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;

class CleanDemoDataTask extends Task
{

    public function mainAction(): void
    {
        if ($this->isDemoEnv()) {

            $this->truncateTables();
            $this->createRootUser();
            $this->cleanSearchIndex();
            $this->cleanCache();

        } else {

            echo '------ access denied ------' . PHP_EOL;
        }
    }

    protected function truncateTables(): void
    {
        echo '------ start truncate tables ------' . PHP_EOL;

        $excludeTables = [
            'kg_migration', 'kg_migration_task',
            'kg_setting', 'kg_nav', 'kg_page',
        ];

        $tables = $this->db->listTables();

        foreach ($tables as $table) {
            if (!in_array($table, $excludeTables)) {
                $this->db->execute("TRUNCATE TABLE {$table}");
            }
        }

        echo '------ end truncate tables ------' . PHP_EOL;
    }

    protected function createRootUser(): void
    {
        echo '------ start create root user ------' . PHP_EOL;

        $account = new AccountModel();

        $account->assign([
            'id' => 10000,
            'email' => '10000@163.com',
            'password' => '1a1e4568f1a3740b8853a8a16e29bc87',
            'salt' => 'MbZWxN3L',
            'create_time' => time(),
        ]);

        $account->create();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $user->assign([
            'admin_role' => 1,
            'edu_role' => 2,
        ]);

        $user->update();

        echo '------ end create root user ------' . PHP_EOL;
    }

    protected function cleanCache(): void
    {
        $util = new IndexPageCacheUtil();
        $util->rebuild();

        $categoryListCache = new CategoryListCache();
        $categoryListCache->rebuild();

        $categoryTreeListCache = new CategoryTreeListCache();
        $categoryTreeListCache->rebuild();
    }

    protected function cleanSearchIndex(): void
    {
        $courseIndexTask = new CourseIndexTask();
        $courseIndexTask->cleanAction();
    }

    protected function isDemoEnv(): bool
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        return (bool)$user;
    }

}
