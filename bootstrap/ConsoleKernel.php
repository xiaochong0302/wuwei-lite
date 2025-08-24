<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace Bootstrap;

use App\Providers\Cache as CacheProvider;
use App\Providers\CliDispatcher as DispatcherProvider;
use App\Providers\Config as ConfigProvider;
use App\Providers\Crypt as CryptProvider;
use App\Providers\Database as DatabaseProvider;
use App\Providers\EventsManager as EventsManagerProvider;
use App\Providers\Logger as LoggerProvider;
use App\Providers\MetaData as MetaDataProvider;
use App\Providers\Provider as AppProvider;
use App\Providers\Redis as RedisProvider;
use Phalcon\Autoload\Loader;
use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Support\HelperFactory;

class ConsoleKernel extends Kernel
{

    public function __construct()
    {
        $this->di = new Cli();
        $this->app = new Console();
        $this->loader = new Loader();

        $this->initAppEnv();
        $this->registerLoaders();
        $this->registerServices();
        $this->registerSettings();
        $this->registerErrorHandler();
    }

    public function handle(): void
    {
        $this->app->setDI($this->di);

        $options = getopt('', ['task:', 'action:']);

        if (!empty($options['task']) && !empty($options['action'])) {

            $this->app->handle($options);

        } else {

            $options = [];

            foreach ($_SERVER['argv'] as $k => $arg) {
                if ($k == 1) {
                    $options['task'] = $this->handleTaskName($arg);
                } elseif ($k == 2) {
                    $options['action'] = $this->handleActionName($arg);
                } elseif ($k >= 3) {
                    $options['params'][] = $arg;
                }
            }

            $this->app->handle($options);

            echo PHP_EOL . PHP_EOL;
        }
    }

    protected function registerLoaders(): void
    {
        $this->loader->setNamespaces([
            'App' => app_path(),
            'Bootstrap' => bootstrap_path(),
        ]);

        $this->loader->setFiles([
            vendor_path('autoload.php'),
            app_path('Library/Helper.php'),
        ]);

        $this->loader->register();
    }

    protected function registerServices(): void
    {
        $providers = [
            ConfigProvider::class,
            CacheProvider::class,
            CryptProvider::class,
            DispatcherProvider::class,
            DatabaseProvider::class,
            EventsManagerProvider::class,
            LoggerProvider::class,
            MetaDataProvider::class,
            RedisProvider::class,
        ];

        foreach ($providers as $provider) {

            /**
             * @var AppProvider $service
             */
            $service = new $provider($this->di);

            $service->register();
        }
    }

    protected function registerErrorHandler(): void
    {
        new ConsoleErrorHandler();
    }

    protected function handleTaskName($name): string
    {
        $helper = new HelperFactory();

        return $helper->uncamelize($name);
    }

    protected function handleActionName($name): string
    {
        $helper = new HelperFactory();

        $name = $helper->uncamelize($name);

        return lcfirst($helper->camelize($name));
    }

}
