<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;

class KgClient
{

    /**
     * 客户端类型
     */
    const TYPE_PC = 1;
    const TYPE_H5 = 2;
    const TYPE_APP = 3;

    public static function types(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::TYPE_PC => $locale->query('client_type_pc'),
            self::TYPE_H5 => $locale->query('client_type_h5'),
            self::TYPE_APP => $locale->query('client_type_app'),
        ];
    }

}
