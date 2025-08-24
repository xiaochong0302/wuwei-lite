<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use Phalcon\Support\HelperFactory;

trait ContentTrait
{

    protected function handleCover(string $coverUrl): string
    {
        $helper = new HelperFactory();

        if (!$helper->startsWith($coverUrl, 'http')) {
            return sprintf('%s%s', kg_cos_url(), $coverUrl);
        }

        return $coverUrl;
    }

    protected function handleContent(string $content): string
    {
        $content = kg_parse_markdown($content);

        return $this->handleUploadUrl($content);
    }

    protected function handleUploadUrl(string $content): string
    {
        $cosUrl = kg_cos_url();

        return preg_replace('/src="\/upload\/(.*?)"/', 'src="' . $cosUrl . '/upload/$1"', $content);
    }

}
