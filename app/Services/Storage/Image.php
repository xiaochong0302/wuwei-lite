<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Storage;

use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;
use App\Services\Storage;

class Image extends Storage
{

    /**
     * 上传远程内容图片
     *
     * @param string $url
     * @return UploadModel|bool
     */
    public function uploadRemoteContentImage(string $url): UploadModel|bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $originalName = pathinfo($path, PATHINFO_BASENAME);

        $fileName = $this->generateFileName($extension);

        $dir = sprintf('/upload/img/content/%s/%s', date('y'), date('m'));

        $this->mkSaveDir($dir);

        $filePath = $dir . '/' . $fileName;

        $contents = file_get_contents($url);

        if (file_put_contents($filePath, $contents) === false) {
            return false;
        }

        $md5 = md5_file($filePath);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        if (!$upload) {

            $upload = new UploadModel();

            $upload->name = $originalName;
            $upload->mime = mime_content_type($filePath);
            $upload->size = filesize($filePath);
            $upload->type = UploadModel::TYPE_CONTENT_IMG;
            $upload->path = $filePath;
            $upload->md5 = $md5;

            $upload->create();
        }

        return $upload;
    }

    /**
     * 上传内容图片
     *
     * @return UploadModel|null
     */
    public function uploadContentImage(): ?UploadModel
    {
        $prefix = sprintf('/upload/img/content/%s/%s', date('Y'), date('m'));

        return $this->upload($prefix, self::MIME_IMAGE, UploadModel::TYPE_CONTENT_IMG);
    }

    /**
     * 上传封面图片
     *
     * @return UploadModel|null
     */
    public function uploadCoverImage(): ?UploadModel
    {
        $prefix = sprintf('/upload/img/cover/%s/%s', date('Y'), date('m'));

        return $this->upload($prefix, self::MIME_IMAGE, UploadModel::TYPE_COVER_IMG);
    }

    /**
     * 上传头像图片
     *
     * @return UploadModel|null
     */
    public function uploadAvatarImage(): ?UploadModel
    {
        $prefix = sprintf('/upload/img/avatar/%s/%s', date('Y'), date('m'));

        return $this->upload($prefix, self::MIME_IMAGE, UploadModel::TYPE_AVATAR_IMG);
    }

    /**
     * 上传图标图片
     *
     * @return UploadModel|null
     */
    public function uploadIconImage(): ?UploadModel
    {
        return $this->upload('/upload/img/icon', self::MIME_IMAGE, UploadModel::TYPE_ICON_IMG);
    }

}
