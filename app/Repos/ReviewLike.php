<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\ReviewLike as ReviewLikeModel;
use Phalcon\Mvc\Model\Row;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ReviewLike extends Repository
{

    /**
     * @param int $reviewId
     * @param int $userId
     * @return ReviewLikeModel|Row|null
     */
    public function findReviewLike($reviewId, $userId)
    {
        return ReviewLikeModel::findFirst([
            'conditions' => 'review_id = :review_id: AND user_id = :user_id:',
            'bind' => ['review_id' => $reviewId, 'user_id' => $userId],
        ]);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ReviewLikeModel[]
     */
    public function findByUserId($userId)
    {
        return ReviewLikeModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->execute();
    }

}
