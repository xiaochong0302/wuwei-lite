<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Review as ReviewValidator;

class ReviewUpdate extends LogicService
{

    use CourseTrait;
    use ReviewTrait;
    use ReviewDataTrait;

    public function handle(int $id): ReviewModel
    {
        $post = $this->request->getPost();

        $review = $this->checkReview($id);

        $user = $this->getLoginUser(true);

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $validator->checkIfAllowEdit($review);

        $data = $this->handlePostData($post);

        $data['published'] = ReviewModel::PUBLISH_PENDING;

        $review->assign($data);

        $review->update();

        $this->eventsManager->fire('Review:afterUpdate', $this, $review);

        return $review;
    }

}
