<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Review as ReviewRepo;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class ReviewList extends LogicService
{

    public function handle(): RepositoryInterface
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new ReviewListBuilder();

        $reviews = $pager->getItems()->toArray();

        $courses = $builder->getCourses($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $course = $courses[$review['course_id']] ?? null;

            $items[] = [
                'id' => $review['id'],
                'content' => $review['content'],
                'reply' => $review['reply'],
                'rating' => $review['rating'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'course' => $course,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
