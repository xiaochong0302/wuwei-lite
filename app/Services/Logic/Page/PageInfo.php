<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Page;

use App\Models\Page as PageModel;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\PageTrait;
use App\Services\Logic\Service as LogicService;

class PageInfo extends LogicService
{

    use PageTrait;
    use ContentTrait;

    public function handle(int $id): array
    {
        $page = $this->checkPage($id);

        $this->incrPageViewCount($page);

        $this->eventsManager->fire('Page:afterView', $this, $page);

        return $this->handlePage($page);
    }

    protected function handlePage(PageModel $page): array
    {
        $content = $this->handleContent($page->content);

        return [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'summary' => $page->summary,
            'keywords' => $page->keywords,
            'content' => $content,
            'published' => $page->published,
            'deleted' => $page->deleted,
            'view_count' => $page->view_count,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

    protected function incrPageViewCount(PageModel $page): void
    {
        $page->view_count += 1;

        $page->update();
    }

}
