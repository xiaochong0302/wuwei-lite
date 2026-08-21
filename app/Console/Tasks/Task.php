<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Traits\Service as ServiceTrait;
use Phalcon\Events\Manager as PhEventsManager;

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

    protected function infoPrint(string $text): void
    {
        echo "\033[36m {$text} \033[0m" . PHP_EOL;
    }

    protected function getTaskLockKey(string $key = null): string
    {
        $key = $key ? sprintf('cli-%s', $key) : get_called_class();

        return md5($key);
    }

    /**
     * Cli中的 getEventsManager()方法获取的对象为 null，需要从容器中获取
     */
    protected function getPhEventsManager(): PhEventsManager
    {
        return $this->getDI()->getShared('eventsManager');
    }

}
