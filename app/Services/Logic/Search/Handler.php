<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Search;

use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

abstract class Handler extends LogicService
{

    abstract function search(): RepositoryInterface;

    abstract function getHotQuery(int $limit, string $type): array;

    abstract function getRelatedQuery(string $query, int $limit): array;

    protected function handleKeywords(string $str): string
    {
        return kg_substr($str, 0, 50, '');
    }

}
