<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Traits\Auth as AuthTrait;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class Service extends Injectable
{

    use AuthTrait;
    use ServiceTrait;

}
