<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Storage\Adapter\Redis as RedisAdapter;
use Phalcon\Storage\SerializerFactory;

class Redis extends Provider
{

    protected string $serviceName = 'redis';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $serializerFactory = new SerializerFactory();

            $options = [
                'defaultSerializer' => 'redis_igbinary',
                'host' => $config->path('redis.host'),
                'port' => $config->path('redis.port'),
                'auth' => $config->path('redis.auth'),
                'index' => $config->path('redis.index'),
            ];

            $storage = new RedisAdapter($serializerFactory, $options);

            return $storage->getAdapter();
        });
    }

}
