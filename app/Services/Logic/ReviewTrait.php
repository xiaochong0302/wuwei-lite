<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Review as ReviewModel;
use App\Validators\Review as ReviewValidator;

trait ReviewTrait
{

    protected function checkReview(int $id): ReviewModel
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

}
