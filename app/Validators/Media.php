<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Media as MediaModel;
use App\Models\Upload as UploadModel;
use App\Repos\Media as MediaRepo;

class Media extends Validator
{

    public function checkMedia(int $id): MediaModel
    {
        $mediaRepo = new MediaRepo();

        $media = $mediaRepo->findById($id);

        if (!$media) {
            throw new BadRequestException('media.not_found');
        }

        return $media;
    }

    public function checkUpload(int $id): UploadModel
    {
        $validator = new Upload();

        return $validator->checkUpload($id);
    }

}
