<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Chapter;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Comment\ListTrait;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class CommentList extends LogicService
{

    use ChapterTrait;
    use ListTrait;

    public function handle(int $id): RepositoryInterface
    {
        $chapter = $this->checkChapterCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['chapter_id'] = $chapter->id;
        //$params['published'] = CommentModel::PUBLISH_APPROVED;
        $params['parent_id'] = 0;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
