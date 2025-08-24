<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class Seo
{

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $keywords = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var string
     */
    protected string $titleSeparator = ' - ';

    /**
     * @var string
     */
    protected string $keywordSeparator = ',';

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setKeywords(string|array $keywords): void
    {
        if (is_array($keywords)) {
            $keywords = implode($this->keywordSeparator, $keywords);
        }

        $this->keywords = $keywords;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTitleSeparator(string $titleSeparator): void
    {
        $this->titleSeparator = $titleSeparator;
    }

    public function appendTitle(string|array $text): void
    {
        $append = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $this->title . $this->titleSeparator . $append;
    }

    public function prependTitle(string|array $text): void
    {
        $prepend = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $prepend . $this->titleSeparator . $this->title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTitleSeparator(): string
    {
        return $this->titleSeparator;
    }

    public function getKeywordSeparator(): string
    {
        return $this->keywordSeparator;
    }

}
