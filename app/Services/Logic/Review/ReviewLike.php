<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Models\ReviewLike as ReviewLikeModel;
use App\Models\User as UserModel;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ReviewLike extends LogicService
{

    use ReviewTrait;

    public function handle(int $id): array
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser(true);

        $validator = new UserLimitValidator();

        $validator->checkDailyReviewLikeLimit($user);

        $likeRepo = new ReviewLikeRepo();

        $reviewLike = $likeRepo->findReviewLike($review->id, $user->id);

        if (!$reviewLike) {

            $reviewLike = new ReviewLikeModel();

            $reviewLike->review_id = $review->id;
            $reviewLike->user_id = $user->id;

            $reviewLike->create();

        } else {

            $reviewLike->deleted = $reviewLike->deleted == 1 ? 0 : 1;

            $reviewLike->update();
        }

        $this->incrUserDailyReviewLikeCount($user);

        if ($reviewLike->deleted == 0) {

            $action = 'do';

            $this->incrReviewLikeCount($review);

            $this->eventsManager->fire('Review:afterLike', $this, $review);

        } else {

            $action = 'undo';

            $this->decrReviewLikeCount($review);

            $this->eventsManager->fire('Review:afterUndoLike', $this, $review);
        }

        return [
            'action' => $action,
            'count' => $review->like_count,
        ];
    }

    protected function incrReviewLikeCount(ReviewModel $review): void
    {
        $review->like_count += 1;

        $review->update();
    }

    protected function decrReviewLikeCount(ReviewModel $review): void
    {
        if ($review->like_count > 0) {
            $review->like_count -= 1;
            $review->update();
        }
    }

    protected function incrUserDailyReviewLikeCount(UserModel $user): void
    {
        $this->eventsManager->fire('UserDailyCounter:incrReviewLikeCount', $this, $user);
    }

}
