<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Http;

use Phalcon\Http\ResponseInterface;

class Response extends \Phalcon\Http\Response
{

    public function setJsonContent(mixed $content, int $jsonOptions = 0, int $depth = 512): ResponseInterface
    {
        $jsonOptions = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

        return parent::setJsonContent($content, $jsonOptions, $depth);
    }

}
