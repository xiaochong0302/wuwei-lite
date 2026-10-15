<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Http;

class Request extends \Phalcon\Http\Request
{

    public function isApi(): bool
    {
        if ($this->hasHeader('X-Platform')) {
            return true;
        }

        $url = $this->get('_url');

        if (!empty($url) && str_contains($url, '/api')) {
            return true;
        }

        return false;
    }

    public function getPost(?string $name = null, mixed $filters = null, mixed $defaultValue = null, bool $notAllowEmpty = false, bool $noRecursive = false): mixed
    {
        if ($this->isJson()) {
            $data = $this->getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        } else {
            $data = parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        }

        return $data;
    }

}
