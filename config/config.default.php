<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use Phalcon\Logger\AbstractLogger;

$config = [];

/**
 * Runtime environment (dev|test|pro)
 */
$config['env'] = 'pro';

/**
 * Secret key
 */
$config['key'] = 'mlq7jQ1Py8kTdW9m';

/**
 * Cluster ID (used to distinguish backend nodes)
 */
$config['server_id'] = 'server-01';

/**
 * Log level
 */
$config['log']['level'] = AbstractLogger::INFO;

/**
 * Log trace
 */
$config['log']['trace'] = false;

/**
 * Website root URL, must end with "/"
 */
$config['base_uri'] = '/';

/**
 * Storage root URL
 */
$config['storage_base_uri'] = 'http:127.0.0.1';

/**
 * Static resources root URL, must end with "/"
 */
$config['static_base_uri'] = '/static/';

/**
 * Static resources version
 */
$config['static_version'] = '202504080830';

/**
 * Database hostname
 */
$config['db']['host'] = 'mysql';

/**
 * Database port
 */
$config['db']['port'] = 3306;

/**
 * Database name
 */
$config['db']['dbname'] = 'wuwei';

/**
 * Database username
 */
$config['db']['username'] = 'wuwei';

/**
 * Database password
 */
$config['db']['password'] = '1qaz2wsx3edc';

/**
 * Database encoding
 */
$config['db']['charset'] = 'utf8mb4';

/**
 * Redis hostname
 */
$config['redis']['host'] = 'redis';

/**
 * Redis port
 */
$config['redis']['port'] = 6379;

/**
 * Redis database index
 */
$config['redis']['index'] = 0;

/**
 * Redis password
 */
$config['redis']['auth'] = '1qaz2wsx3edc';

/**
 * redis timeout（seconds）
 */
$config['redis']['timeout'] = 5;

/**
 * redis read_timeout（seconds）
 */
$config['redis']['read_timeout'] = 30;

/**
 * cookie会话有效期（秒），当为0时，表示浏览器会话
 */
$config['session']['cookie_lifetime'] = 0;

/**
 * session会话有效期（秒），不能比cookie会话有效期小
 */
$config['session']['lifetime'] = 24 * 3600;

/**
 * Session prefix
 */
$config['session']['prefix'] = 'kg-session-';

/**
 * Metadata validity period (seconds)
 */
$config['metadata']['lifetime'] = 7 * 86400;

/**
 * Metadata prefix
 */
$config['metadata']['prefix'] = 'kg-metadata-';

/**
 * Annotation validity period (seconds)
 */
$config['annotation']['lifetime'] = 7 * 86400;

/**
 * Annotation prefix
 */
$config['annotation']['prefix'] = 'kg-annotation-';

/**
 * api令牌有效期（秒）
 */
$config['api_token']['lifetime'] = 7 * 86400;

/**
 * api令牌前缀
 */
$config['api_token']['prefix'] = 'kg-api-token-';

/**
 * CsrfToken validity period (seconds)
 */
$config['csrf_token']['lifetime'] = 86400;

/**
 * Allow Cross-Origin
 */
$config['cors']['enabled'] = true;

/**
 * Allowed cross-origin domains (array|string)
 */
$config['cors']['allow_origin'] = '*';

/**
 * Allowed cross-origin headers (array|string)
 */
$config['cors']['allow_headers'] = '*';

/**
 * Allowed cross-origin methods
 */
$config['cors']['allow_methods'] = ['GET', 'POST', 'OPTIONS'];

/**
 * PayPal's payment configuration (for development/testing, only valid when env=sandbox, higher priority)
 */
$config['payment']['paypal'] = [
    'env' => 'live',
    'enabled' => 1,
    'client_id' => '',
    'client_secret' => '',
    'webhook_id' => '',
    'service_rate' => 5,
];

/**
 * Stripe payment configuration (for development/testing, only valid when env=sandbox, higher priority)
 */
$config['payment']['stripe'] = [
    'env' => 'live',
    'enabled' => 1,
    'api_key' => '',
    'webhook_secret' => '',
    'service_rate' => 5,
];

return $config;
