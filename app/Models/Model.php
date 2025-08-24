<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class Model extends \Phalcon\Mvc\Model
{

    public function initialize(): void
    {
        $this->setup([
            'notNullValidations' => false,
        ]);

        $this->useDynamicUpdate(true);
    }

}
