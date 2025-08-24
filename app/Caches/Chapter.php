<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;

class Chapter extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return "chapter-{$id}";
    }

    public function getContent($id = null): ?ChapterModel
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        return $chapter ?: null;
    }

}
