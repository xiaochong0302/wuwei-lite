<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Mvc;

use Phalcon\Mvc\View as PhView;
use Phalcon\Paginator\Repository as PagerRepository;

class View extends PhView
{

    public function setVars(array $params, bool $merge = true): static
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->handleVar($param);
        }

        return parent::setVars($params, $merge);
    }

    public function setVar(string $key, mixed $value): static
    {
        $value = $this->handleVar($value);

        return parent::setVar($key, $value);
    }

    protected function handleVar(mixed $var): mixed
    {
        if ($var instanceof PagerRepository) {
            $items = kg_objectify($var->getItems());
            $var->setItems($items);
        } elseif (is_array($var)) {
            $var = kg_objectify($var);
        }

        return $var;
    }

}
