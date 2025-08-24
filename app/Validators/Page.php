<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Page as PageModel;
use App\Repos\Page as PageRepo;

class Page extends Validator
{

    public function checkPage(int $id): PageModel
    {
        $pageRepo = new PageRepo();

        $page = $pageRepo->findById($id);

        if (!$page) {
            throw new BadRequestException('page.not_found');
        }

        return $page;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('page.title_too_short');
        }

        if ($length > 120) {
            throw new BadRequestException('page.title_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('page.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords(string $keywords): string
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 120) {
            throw new BadRequestException('page.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkContent(string $content): string
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('page.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('page.invalid_publish_status');
        }

        return $status;
    }

}
