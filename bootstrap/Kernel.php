<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace Bootstrap;

use Phalcon\Application\AbstractApplication;
use Phalcon\Autoload\Loader;
use Phalcon\Config\Config;
use Phalcon\Di\Di;

abstract class Kernel
{

    /**
     * @var Di
     */
    protected Di $di;

    /**
     * @var AbstractApplication
     */
    protected AbstractApplication $app;

    /**
     * @var Loader
     */
    protected Loader $loader;

    protected function initAppEnv(): void
    {
        require __DIR__ . '/Helper.php';
    }

    protected function registerSettings(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $timezone = kg_setting('site', 'timezone', 'Asia/Shanghai');

        ini_set('date.timezone', $timezone);

        if ($config->get('env') == ENV_DEV) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL ^ E_DEPRECATED);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }

    abstract public function handle(): void;

    abstract protected function registerLoaders(): void;

    abstract protected function registerServices(): void;

    abstract protected function registerErrorHandler(): void;

}
