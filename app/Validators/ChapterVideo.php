<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Models\Media as MediaModel;

class ChapterVideo extends Validator
{

    public function checkMedia(int $id): MediaModel
    {
        $validator = new Media();

        return $validator->checkMedia($id);
    }

}
