<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use Phalcon\Mvc\Router\Annotations as Router;

$router = new Router(false);

$router->removeExtraSlashes(true);

$router->setDefaultNamespace('App\Http\Home\Controllers');

$router->notFound([
    'module' => 'home',
    'controller' => 'error',
    'action' => 'show404',
]);

$modules = ['api', 'home', 'admin'];

foreach ($modules as $module) {
    $moduleName = ucfirst($module);
    $files = scandir(app_path('Http/' . $moduleName . '/Controllers'));
    foreach ($files as $file) {
        if (preg_match('/^\w+Controller\.php$/', $file)) {
            $className = str_replace('Controller.php', '', $file);
            $router->addModuleResource($module, 'App\Http\\' . $moduleName . '\Controllers\\' . $className);
        }
    }
}

return $router;
