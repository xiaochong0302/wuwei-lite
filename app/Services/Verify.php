<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use Phalcon\Support\Helper\Str\Random;
use Phalcon\Support\HelperFactory;

class Verify extends Service
{

    public function getEmailCode(string $email, int $lifetime = 300): string
    {
        $helper = new HelperFactory();

        $code = $helper->random(Random::RANDOM_NUMERIC, 6);

        $cache = $this->getCache();

        $key = $this->getEmailCacheKey($email);

        $cache->set($key, $code, $lifetime);

        return $code;
    }

    public function checkEmailCode(string $email, string $code): bool
    {
        $cache = $this->getCache();

        $key = $this->getEmailCacheKey($email);

        $value = $cache->get($key);

        if (empty($value)) return false;

        return $code == $value;
    }

    protected function getEmailCacheKey(string $email): string
    {
        return sprintf("verify-email-%s", crc32($email));
    }

}
