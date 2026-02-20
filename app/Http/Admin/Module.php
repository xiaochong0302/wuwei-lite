<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin;

use App\Library\Mvc\View as MyView;
use App\Plugins\Locale as LocalePlugin;
use App\Services\Auth\Admin as AdminAuth;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(?DiInterface $container = null)
    {

    }

    public function registerServices(DiInterface $container)
    {
        $container->setShared('locale', function () {
            $locale = new LocalePlugin();
            return $locale->getTranslator('admin');
        });

        $container->setShared('view', function () {
            $view = new MyView();
            $view->setViewsDir(__DIR__ . '/Views');
            $view->registerEngines([
                '.volt' => 'volt',
            ]);
            return $view;
        });

        $container->setShared('auth', function () {
            return new AdminAuth();
        });
    }

}
