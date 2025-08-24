<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

$config = require __DIR__ . '/config/config.php';

return [
    'version_order' => 'creation',
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'kg_migration_phinx',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => $config['db']['host'],
            'port' => $config['db']['port'],
            'name' => $config['db']['dbname'],
            'user' => $config['db']['username'],
            'pass' => $config['db']['password'],
            'charset' => $config['db']['charset'],
        ],
    ],

];
