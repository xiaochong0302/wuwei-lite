<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Utils;

use App\Services\Service as AppService;
use Phalcon\Support\HelperFactory;

class OpCache extends AppService
{

    public function reset(string $scope = 'all'): void
    {
        $rootPath = root_path();

        $helper = new HelperFactory();

        if ($scope == 'diff') {
            exec('git diff --name-only HEAD~ HEAD', $files);
            foreach ($files as $file) {
                if ($helper->endsWith($file, '.php')) {
                    $filename = sprintf('%s/%s', $rootPath, $file);
                    opcache_invalidate($filename, true);
                }
            }
        } elseif ($scope == 'all') {
            opcache_reset();
        }
    }

}
