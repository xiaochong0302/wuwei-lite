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
 * Token validity period (seconds)
 */
$config['token']['lifetime'] = 7 * 86400;

/**
 * Session validity period (seconds)
 */
$config['session']['lifetime'] = 86400;

/**
 * Session prefix
 */
$config['session']['prefix'] = 'ww-session-';

/**
 * Metadata validity period (seconds)
 */
$config['metadata']['lifetime'] = 7 * 86400;

/**
 * Metadata prefix
 */
$config['metadata']['prefix'] = 'ww-metadata-';

/**
 * Annotation validity period (seconds)
 */
$config['annotation']['lifetime'] = 7 * 86400;

/**
 * Annotation prefix
 */
$config['annotation']['prefix'] = 'ww-annotation-';

/**
 * CsrfToken validity period (seconds)
 */
$config['csrf_token']['lifetime'] = 86400;

/**
 * Allow Cross-Origin
 */
$config['cors']['enabled'] = true;

/**
 * Allowed cross-origin domains (string|array)
 */
$config['cors']['allow_origin'] = '*';

/**
 * Allowed cross-origin headers (string|array)
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
