<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Traits\Service as ServiceTrait;

class Task extends \Phalcon\Cli\Task
{

    use ServiceTrait;

    protected function normalPrint(string $text): void
    {
        echo "\033[34m {$text} \033[0m" . PHP_EOL;
    }

    protected function successPrint(string $text): void
    {
        echo "\033[32m {$text} \033[0m" . PHP_EOL;
    }

    protected function errorPrint(string $text): void
    {
        echo "\033[31m {$text} \033[0m" . PHP_EOL;
    }

    protected function getTaskLockKey(string $key = null): string
    {
        $key = $key ? sprintf('cli-%s', $key) : get_called_class();

        return md5($key);
    }

}
