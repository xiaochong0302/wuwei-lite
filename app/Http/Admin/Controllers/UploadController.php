<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Repos\Upload as UploadRepo;
use App\Services\Storage as StorageService;
use App\Services\Storage\Image as ImageStorageService;
use App\Services\Storage\Media as MediaStorageService;
use App\Services\Storage\Resource as ResourceStorageService;
use App\Validators\Validator as AppValidator;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    public function initialize()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser->id);
    }

    /**
     * @Post("/avatar/img", name="admin.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new ImageStorageService();

        $upload = $service->uploadAvatarImage();

        if (!$upload) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        $data = ['url' => $upload->path];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/icon/img", name="admin.upload.icon_img")
     */
    public function uploadIconImageAction()
    {
        $service = new ImageStorageService();

        $upload = $service->uploadIconImage();

        if (!$upload) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        $data = ['url' => $upload->path];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/cover/img", name="admin.upload.cover_img")
     */
    public function uploadCoverImageAction()
    {
        $service = new ImageStorageService();

        $upload = $service->uploadCoverImage();

        if (!$upload) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        $data = ['url' => $upload->path];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/vditor/img", name="admin.upload.vidtor_img")
     */
    public function uploadVditorImageAction()
    {
        $service = new ImageStorageService();

        $upload = $service->uploadContentImage();

        if (!$upload) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        $data = ['url' => $upload->path];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/vditor/img/remote", name="admin.upload.remote_vditor_img")
     */
    public function uploadRemoteVditorImageAction()
    {
        $originalUrl = $this->request->getPost('url', ['trim', 'string']);

        $service = new ImageStorageService();

        $upload = $service->uploadRemoteContentImage($originalUrl);

        $newUrl = $originalUrl;

        if ($upload) {
            $newUrl = $upload->path;
        }

        $data = [
            'url' => $newUrl,
            'originalURL' => $originalUrl,
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/tmp/file", name="admin.upload.tmp_file")
     */
    public function uploadTmpFileAction()
    {
        $service = new StorageService();

        $file = $service->uploadTempFile();

        if (!$file) {
            return $this->jsonError([
                'msg' => $this->locale->query('upload_failed'),
            ]);
        }

        return $this->jsonSuccess(['file' => $file]);
    }

    /**
     * @Post("/chunk", name="admin.upload.chunk")
     */
    public function uploadChunkAction()
    {
        $service = new MediaStorageService();

        $service->uploadChunk();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/media/chunks/merge", name="admin.upload.merge_media_chunks")
     */
    public function mergeMediaChunksAction()
    {
        $service = new MediaStorageService();

        $upload = $service->mergeMediaChunks();

        return $this->jsonSuccess(['upload' => $upload]);
    }

    /**
     * @Post("/resource/chunks/merge", name="admin.upload.merge_resource_chunks")
     */
    public function mergeResourceChunksAction()
    {
        $service = new ResourceStorageService();

        $upload = $service->mergeResourceChunks();

        return $this->jsonSuccess(['upload' => $upload]);
    }

    /**
     * @Get("/file/query", name="admin.upload.query_file")
     */
    public function queryFileAction()
    {
        $service = new MediaStorageService();

        $content = $service->queryFile();

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/chunk/query", name="admin.upload.query_chunk")
     */
    public function queryChunkAction()
    {
        $service = new MediaStorageService();

        $content = $service->queryChunk();

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/info", name="admin.upload.info")
     */
    public function infoAction()
    {
        $md5 = $this->request->getQuery('md5');

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        return $this->jsonSuccess(['upload' => $upload]);
    }

}
