<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Slide as SlideModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexSlideList extends Cache
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
        return 'index-slide-list';
    }

    public function getContent($id = null): array
    {
        $limit = 10;

        $slides = $this->findSlides($limit);

        if ($slides->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($slides as $slide) {
            $result[] = [
                'id' => $slide->id,
                'title' => $slide->title,
                'cover' => $slide->cover,
                'link' => $slide->link,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|SlideModel[]
     */
    public function findSlides(int $limit = 10)
    {
        return SlideModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
