<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Comment as CommentModel;
use App\Validators\Comment as CommentValidator;

trait CommentTrait
{

    protected function checkComment($id): CommentModel
    {
        $validator = new CommentValidator();

        return $validator->checkComment($id);
    }

}
