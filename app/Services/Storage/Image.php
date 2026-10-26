<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Storage;

use App\Library\Utils\FileInfo as FileInfo;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;
use App\Services\Storage;

class Image extends Storage
{

    /**
     * 上传远程内容图片
     *
     * @param string $url
     * @return UploadModel|false
     */
    public function uploadRemoteContentImage(string $url): UploadModel|false
    {
        if (!CommonValidator::url($url)) {
            return false;
        }

        if (str_starts_with($url, $this->getBaseUrl())) {
            return false;
        }

        $contents = file_get_contents($url);

        if ($contents === false) {
            return false;
        }

        $md5 = FileInfo::getContentMd5($contents);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        if (!$upload) {

            $path = parse_url($url, PHP_URL_PATH);
            $originName = pathinfo($path, PATHINFO_BASENAME);

            $extension = $this->getImageExtension($originName);
            $fileName = $this->generateFileName($extension);

            $dir = sprintf('/upload/img/content/%s/%s', date('y'), date('m'));

            $saveDir = $this->mkSaveDir($dir);

            $destFile = $saveDir . '/' . $fileName;

            if (file_put_contents($destFile, $contents) === false) {
                return false;
            }

            $upload = new UploadModel();

            $upload->name = $originName;
            $upload->mime = FileInfo::getMimeTypeByExt($extension);
            $upload->size = FileInfo::getFileSize($destFile);
            $upload->path = $this->getRelativeFilePath($destFile);
            $upload->type = UploadModel::TYPE_CONTENT_IMG;
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
