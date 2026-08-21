<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

class MainTask extends Task
{

    public function mainAction(): void
    {
        $version = [
            'phalcon' => phpversion('phalcon'),
            'zephir_parser' => phpversion('zephir_parser'),
        ];

        echo "You are now flying with Phalcon CLI!" . PHP_EOL;
        echo "Phalcon version: {$version['phalcon']}" . PHP_EOL;
        echo "Zephir parser version: {$version['zephir_parser']}" . PHP_EOL;
    }

}
