<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;

class Upload extends Validator
{

    public function checkUpload(int $id): UploadModel
    {
        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findById($id);

        if (!$upload) {
            throw new BadRequestException('upload.not_found');
        }

        return $upload;
    }

}
