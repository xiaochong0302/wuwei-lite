<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use Phalcon\Cache\CacheInterface;
use Phalcon\Di\Injectable;

abstract class Cache extends Injectable
{

    /**
     * @var CacheInterface
     */
    protected CacheInterface $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->getShared('cache');
    }

    /**
     * 获取缓存内容
     */
    public function get(string|int $id = null): mixed
    {
        $key = $this->getKey($id);

        if (!$this->cache->has($key)) {
            $content = $this->getContent($id);
            $lifetime = $this->getLifetime();
            $this->cache->set($key, $content, $lifetime);
        } else {
            $content = $this->cache->get($key);
        }

        return $content;
    }

    /**
     * 删除缓存内容
     */
    public function delete(string|int $id = null): bool
    {
        $key = $this->getKey($id);

        return $this->cache->delete($key);
    }

    /**
     * 重建缓存内容
     */
    public function rebuild(string|int $id = null): mixed
    {
        $this->delete($id);

        return $this->get($id);
    }

    /**
     * 获取缓存有效期
     */
    abstract public function getLifetime(): int;

    /**
     * 获取键值
     */
    abstract public function getKey(string|int $id = null): string;

    /**
     * 获取原始内容
     */
    abstract public function getContent(string|int $id = null): mixed;

}
