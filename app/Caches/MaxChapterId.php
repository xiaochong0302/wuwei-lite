<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Chapter as ChapterModel;

class MaxChapterId extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 360 * 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'max-chapter-id';
    }

    public function getContent($id = null): int
    {
        $chapter = ChapterModel::findFirst(['order' => 'id DESC']);

        return $chapter->id ?? 0;
    }

}
