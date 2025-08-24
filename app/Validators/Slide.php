<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;

class Slide extends Validator
{

    public function checkSlide(int $id): SlideModel
    {
        $slideRepo = new SlideRepo();

        $slide = $slideRepo->findById($id);

        if (!$slide) {
            throw new BadRequestException('slide.not_found');
        }

        return $slide;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('slide.title_too_short');
        }

        if ($length > 120) {
            throw new BadRequestException('slide.title_too_long');
        }

        return $value;
    }

    public function checkCover(string $cover): string
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('slide.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkLink(string $url): string
    {
        $value = $this->filter->sanitize($url, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_link');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('slide.summary_too_long');
        }

        return $value;
    }

    public function checkPriority(int $priority): int
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('slide.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('slide.invalid_publish_status');
        }

        return $status;
    }

}
