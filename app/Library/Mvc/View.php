<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Mvc;

use Phalcon\Mvc\View as PhView;

class View extends PhView
{

    public function setVars(array $params, $merge = true): PhView
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->handleVar($param);
        }

        return parent::setVars($params, $merge);
    }

    public function setVar($key, $value): PhView
    {
        $value = $this->handleVar($value);

        return parent::setVar($key, $value);
    }

    protected function handleVar($var)
    {
        /**
         * 分页数据
         */
        if (is_object($var) && method_exists($var, 'getTotalItems')) {
            $items = $var->getItems();
            if (is_array($items)) {
                $items = kg_array_object($items);
                $var->setItems($items);
            }
        } elseif (is_array($var)) {
            $var = kg_array_object($var);
        }

        return $var;
    }

}
