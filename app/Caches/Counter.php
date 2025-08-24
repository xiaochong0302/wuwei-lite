<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use Phalcon\Di\Injectable;
use Redis;

abstract class Counter extends Injectable
{

    /**
     * @var Redis
     */
    protected Redis $redis;

    public function __construct()
    {
        $this->redis = $this->getDI()->getShared('redis');
    }

    public function get(string|int $id): mixed
    {
        $key = $this->getKey($id);

        $content = $this->redis->hGetAll($key);

        if (!$this->redis->exists($key)) {

            $content = $this->getContent($id);
            $lifetime = $this->getLifetime();

            $this->redis->hMSet($key, $content);
            $this->redis->expire($key, $lifetime);
        }

        return $content;
    }

    public function delete(string|int $id = null): int
    {
        $key = $this->getKey($id);

        return $this->redis->del($key);
    }

    public function rebuild(string|int $id = null): mixed
    {
        $this->delete($id);

        return $this->get($id);
    }

    public function hGet(string|int $id, string $hashKey): false|string
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hGet($key, $hashKey);
    }

    public function hDel(string|int $id, string $hashKey): bool|int
    {
        $key = $this->getKey($id);

        return $this->redis->hDel($key, $hashKey);
    }

    public function hIncrBy(string|int $id, string $hashKey, int $value = 1): int
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hIncrBy($key, $hashKey, $value);
    }

    public function hDecrBy(string|int $id, string $hashKey, int $value = 1): int
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hIncrBy($key, $hashKey, 0 - $value);
    }

    /**
     * 获取缓存有效期
     *
     * @return int
     */
    abstract public function getLifetime(): int;

    /**
     * 获取键值
     *
     * @param mixed $id
     * @return string
     */
    abstract public function getKey(string|int $id = null): string;

    /**
     * 获取原始内容
     *
     * @param mixed $id
     * @return mixed
     */
    abstract public function getContent(string|int $id = null): mixed;

}
