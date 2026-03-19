<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Common as CommonService;
use App\Services\Storage\Image as ImageStorageService;
use App\Validators\Validator as AppValidator;

/**
 * @RoutePrefix("/upload")
 */
class UploadController extends Controller
{

    public function initialize()
    {
        $commonService = new CommonService();

        $authInfo = $commonService->getAuthInfo();

        $validator = new AppValidator();

        $validator->checkAuthInfo($authInfo);
    }

    /**
     * @Post("/avatar/img", name="home.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new ImageStorageService();

        $file = $service->uploadAvatarImage();

        if (!$file) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        $data = [
            'id' => $file->id,
            'name' => $file->name,
            'url' => $file->path,
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

}
