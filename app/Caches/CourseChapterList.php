<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Builders\CourseChapterList as CourseChapterListBuilder;

class CourseChapterList extends Cache
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
        return "course-chapter-list-{$id}";
    }

    public function getContent($id = null): array
    {
        $builder = new CourseChapterListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
