<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Annotations\Adapter\Apcu as ApcuAdapter;
use Phalcon\Annotations\Adapter\Memory as MemoryAdapter;
use Phalcon\Config\Config;

class Annotation extends Provider
{

    protected string $serviceName = 'annotations';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $annotations = new MemoryAdapter();

            } else {

                $annotations = new ApcuAdapter([
                    'prefix' => $config->path('annotation.prefix'),
                    'lifetime' => $config->path('annotation.lifetime'),
                ]);
            }

            return $annotations;
        });
    }

}
