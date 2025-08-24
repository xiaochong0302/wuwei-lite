<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Utils;

use App\Services\Service as AppService;
use Phalcon\Storage\Adapter\Apcu as ApcuStorage;
use Phalcon\Storage\SerializerFactory;

class ApcuCache extends AppService
{

    public function reset(string $scope = 'all'): void
    {
        if ($scope == 'annotation') {
            $this->clearAnnotationCache();
        } elseif ($scope == 'metadata') {
            $this->clearMetadataCache();
        } else {
            $this->clearAnnotationCache();
            $this->clearMetadataCache();
        }
    }

    protected function clearAnnotationCache(): void
    {
        $config = $this->getConfig();

        $prefix = sprintf('_phan%s', $config->path('annotation.prefix'));

        $this->clearCache($prefix);
    }

    protected function clearMetadataCache(): void
    {
        $config = $this->getConfig();

        $prefix = $config->path('metadata.prefix');

        $this->clearCache($prefix);
    }

    protected function clearCache(string $prefix): void
    {
        $apc = $this->getApcuStorage($prefix);

        $apc->clear();
    }

    protected function getApcuStorage(string $prefix = ''): ApcuStorage
    {
        $serializerFactory = new SerializerFactory();

        $options = ['prefix' => $prefix];

        return new ApcuStorage($serializerFactory, $options);
    }

}
