<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

$config = require __DIR__ . '/config.php';

use Phalcon\Config\Config;

return new Config([
    'database' => [
        'adapter' => 'mysql',
        'host' => $config['db']['host'],
        'username' => $config['db']['username'],
        'password' => $config['db']['password'],
        'dbname' => $config['db']['dbname'],
        'charset' => $config['db']['charset'],
    ],
    'application' => [
        'logInDb' => true,
        'migrationsDir' => 'db/migrations',
        'migrationsTsBased' => true,
        'exportDataFromTables' => [],
    ],
]);
