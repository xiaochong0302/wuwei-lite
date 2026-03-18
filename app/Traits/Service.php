<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Caches\Setting as SettingCache;
use App\Library\Logger as AppLogger;
use Phalcon\Cache\CacheInterface;
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Logger\Logger;
use Redis;

trait Service
{

    /**
     * 获取Config
     *
     * @return Config
     */
    protected function getConfig(): Config
    {
        return Di::getDefault()->getShared('config');
    }

    /**
     * 获取Cache
     *
     * @return CacheInterface
     */
    protected function getCache(): CacheInterface
    {
        return Di::getDefault()->getShared('cache');
    }

    /**
     * 获取Redis
     *
     * @return Redis
     */
    protected function getRedis(): Redis
    {
        return Di::getDefault()->getShared('redis');
    }

    /**
     * 获取Logger
     *
     * @param string $channel
     * @return Logger
     */
    protected function getLogger(string $channel = 'common'): Logger
    {
        $logger = new AppLogger();

        return $logger->getInstance($channel);
    }

    /**
     * 获取某组配置项
     *
     * @param string $section
     * @return array
     */
    protected function getSettings(string $section): array
    {
        $cache = new SettingCache();

        return $cache->get($section);
    }

}
