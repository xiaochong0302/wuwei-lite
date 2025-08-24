<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Audit as AuditModel;
use App\Repos\Audit as AuditRepo;
use Phalcon\Paginator\RepositoryInterface;

class Audit extends Service
{

    public function getAudits(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $auditRepo = new AuditRepo();

        return $auditRepo->paginate($params, $sort, $page, $limit);
    }

    public function getAudit(int $id): AuditModel
    {
        $auditRepo = new AuditRepo();

        return $auditRepo->findById($id);
    }

}
