<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Library\Logger as AppLogger;

class Logger extends Provider
{

    protected string $serviceName = 'logger';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {

            $logger = new AppLogger();

            return $logger->getInstance('common');
        });
    }

}
