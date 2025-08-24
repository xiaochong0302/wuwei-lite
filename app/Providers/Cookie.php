<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Http\Response\Cookies as PhCookies;

class Cookie extends Provider
{

    protected string $serviceName = 'cookies';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {

            $cookies = new PhCookies();

            $cookies->useEncryption(true);

            return $cookies;
        });
    }

}
