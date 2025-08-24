<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Library\CsrfToken as MyCsrfToken;

class CsrfToken extends Provider
{

    protected string $serviceName = 'csrfToken';

    public function register():void
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyCsrfToken();
        });
    }

}
