<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Caches\AppInfo as AppInfoCache;
use App\Caches\NavTreeList as NavTreeListCache;
use App\Caches\Setting as SettingCache;
use App\Models\MigrationPhalcon as MigrationPhalconModel;
use App\Models\Setting as SettingModel;
use App\Services\Utils\OpCache as OpCacheUtil;
use GuzzleHttp\Client as HttpClient;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Support\HelperFactory;

class UpgradeTask extends Task
{

    public function mainAction(): void
    {
        $this->migrateAction();
        $this->resetAppInfoAction();
        $this->resetSettingAction();
        $this->resetAnnotationAction();
        $this->resetMetadataAction();
        $this->resetVoltAction();
        $this->resetNavAction();
        $this->resetOpcacheAction();
    }

    /**
     * 执行迁移
     *
     * @command: php console.php upgrade migrate
     */
    public function migrateAction(): void
    {
        $tasks = $this->findMigrationTasks();

        $versionList = [];

        if ($tasks->count() > 0) {
            $versionList = kg_array_column($tasks->toArray(), 'version');
        }

        $files = scandir(app_path('Console/Migrations'));

        foreach ($files as $file) {

            if (preg_match('/^V[0-9]+\.php$/', $file)) {

                $version = substr($file, 0, -4);

                if (!in_array($version, $versionList)) {

                    $startTime = time();

                    $className = "\App\Console\Migrations\\{$version}";
                    $obj = new $className();
                    $obj->run();

                    $endTime = time();

                    $task = new MigrationPhalconModel();
                    $task->version = $version;
                    $task->start_time = $startTime;
                    $task->end_time = $endTime;
                    $task->create();

                    echo "------ phalcon migration {$version} ok ------" . PHP_EOL;
                }
            }
        }
    }

    /**
     * 重置应用信息
     *
     * @command: php console.php upgrade reset_app_info
     */
    public function resetAppInfoAction(): void
    {
        $cache = new AppInfoCache();

        $cache->rebuild();

        echo '------ reset app info ok ------' . PHP_EOL;
    }

    /**
     * 重置系统设置
     *
     * @command: php console.php upgrade reset_setting
     */
    public function resetSettingAction(): void
    {
        $rows = SettingModel::query()->columns('section')->distinct(true)->execute();

        foreach ($rows as $row) {
            $cache = new SettingCache();
            $cache->rebuild($row->section);
        }

        echo '------ reset setting ok ------' . PHP_EOL;
    }

    /**
     * 重置注解
     *
     * @command: php console.php upgrade reset_annotation
     */
    public function resetAnnotationAction(): void
    {
        /**
         * fpm的apcu缓存cli模式下无法刷新，通过http调用执行刷新
         */
        $this->resetFpmApcuCache('annotation');

        echo '------ reset annotation ok ------' . PHP_EOL;
    }

    /**
     * 重置元数据
     *
     * @command: php console.php upgrade reset_metadata
     */
    public function resetMetadataAction(): void
    {
        /**
         * fpm的apcu缓存cli模式下无法刷新，通过http调用执行刷新
         */
        $this->resetFpmApcuCache('metadata');

        echo "------ reset metadata ok ------" . PHP_EOL;
    }

    /**
     * 重置opcache
     *
     * @command: php console.php upgrade reset_op_cache {scope}
     */
    public function resetOpCacheAction(string $scope = 'all'): void
    {
        $service = new OpCacheUtil();

        $service->reset($scope);

        /**
         * fpm的opcache缓存cli模式下无法刷新，通过http调用执行刷新
         */
        $this->resetFpmOpCache($scope);

        echo '------ reset opcache ok ------' . PHP_EOL;
    }

    /**
     * 重置模板
     *
     * @command: php console.php upgrade reset_volt
     */
    public function resetVoltAction(): void
    {
        $dir = cache_path('volt');

        foreach (scandir($dir) as $file) {
            if (str_contains($file, '.php')) {
                unlink($dir . '/' . $file);
            }
        }

        echo '------ reset volt ok ------' . PHP_EOL;
    }

    /**
     * 重置导航
     *
     * @command: php console.php upgrade reset_nav
     */
    public function resetNavAction(): void
    {
        $cache = new NavTreeListCache();

        $cache->delete();

        echo '------ reset navigation ok ------' . PHP_EOL;
    }

    protected function resetFpmApcuCache(string $scope = 'all'): void
    {
        $auth = $this->crypt->encrypt($scope);

        $url = sprintf('%s/admin/apcu/cache', kg_setting('site', 'url'));

        $helper = new HelperFactory();

        if (!$helper->startsWith($url, 'http')) return;

        if ($helper->includes($url, '.local')) return;

        $params = [
            'scope' => $scope,
            'auth' => $auth,
        ];

        $client = new HttpClient();

        $client->request('POST', $url, [
            'form_params' => $params,
            'http_errors' => false,
        ]);
    }

    protected function resetFpmOpCache(string $scope = 'all'): void
    {
        $auth = $this->crypt->encrypt($scope);

        $url = sprintf('%s/admin/op/cache', kg_setting('site', 'url'));

        $helper = new HelperFactory();

        if (!$helper->startsWith($url, 'http')) return;

        if ($helper->includes($url, '.local')) return;

        $params = [
            'scope' => $scope,
            'auth' => $auth,
        ];

        $client = new HttpClient();

        $client->request('POST', $url, [
            'form_params' => $params,
            'http_errors' => false,
        ]);
    }

    /**
     * @return ResultsetInterface|MigrationPhalconModel[]
     */
    protected function findMigrationTasks()
    {
        return MigrationPhalconModel::query()->execute();
    }

}
