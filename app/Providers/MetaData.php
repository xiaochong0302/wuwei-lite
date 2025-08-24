<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Cache\AdapterFactory;
use Phalcon\Config\Config;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;
use Phalcon\Mvc\Model\MetaData\Apcu as ApcuMetaData;
use Phalcon\Storage\SerializerFactory;

class MetaData extends Provider
{

    protected string $serviceName = 'modelsMetadata';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $metaData = new MemoryMetaData();

            } else {

                $serializerFactory = new SerializerFactory();

                $adapterFactory = new AdapterFactory($serializerFactory);

                $options = [
                    'prefix' => $config->path('metadata.prefix'),
                    'lifetime' => $config->path('metadata.lifetime'),
                ];

                $metaData = new ApcuMetaData($adapterFactory, $options);
            }

            return $metaData;
        });
    }

}
