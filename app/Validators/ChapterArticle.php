<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;

class ChapterArticle extends Validator
{

    public function checkContent(string $content): string
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('chapter_article.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('chapter_article.content_too_long');
        }

        return $value;
    }

}
