<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Models\Media as MediaModel;
use App\Repos\Upload as UploadRepo;
use App\Services\Storage\Media as MediaStorageService;
use App\Validators\Media as MediaValidator;

class Media extends Service
{

    public function delete(int $id): MediaModel
    {
        $media = $this->findOrFail($id);

        $this->deleteOriginMedia($media);

        return $this->findOrFail($id);
    }

    protected function deleteOriginMedia(MediaModel $media): void
    {
        if (empty($media->file_origin)) return;

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findById($media->upload_id);

        $storage = new MediaStorageService();

        $storage->remove($upload->path);

        $media->file_origin = [];

        $media->update();

        $upload->deleted = 1;

        $upload->update();
    }

    protected function findOrFail(int $id): MediaModel
    {
        $validator = new MediaValidator();

        return $validator->checkMedia($id);
    }

}
