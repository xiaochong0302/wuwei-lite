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

class Media extends Storage
{

    /**
     * 查询上传文件（秒传检查）
     *
     * @return array
     */
    public function queryFile(): array
    {
        $md5 = $this->request->getQuery('md5');

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        $result = ['exists' => 0];

        if ($upload) {
            $result = ['exists' => 1, 'upload' => $upload];
        }

        return $result;
    }

    /**
     * 查询分片文件（秒传检查）
     *
     * @return array
     */
    public function queryChunk(): array
    {
        $query = $this->request->getQuery();

        $chunkFile = sprintf('%s/%s.%s', tmp_path(), $query['md5'], $query['chunk']);

        $chunkSize = $query['end'] - $query['start'];

        $result = ['existed' => 0];

        if (file_exists($chunkFile) && filesize($chunkFile) == $chunkSize) {
            $result = ['existed' => 1];
        }

        return $result;
    }

    /**
     * 上传分片
     */
    public function uploadChunk(): void
    {
        $md5 = $this->request->getPost('md5');
        $chunk = $this->request->getPost('chunk', 'int', 0);

        $chunkFile = sprintf('%s/%s.%s', tmp_path(), $md5, $chunk);

        if ($this->request->hasFiles()) {
            $files = $this->request->getUploadedFiles(true);
            $file = $files[0];
            $file->moveTo($chunkFile);
        }
    }

    /**
     * 合并媒体分片
     *
     * @return UploadModel|bool
     */
    public function mergeMediaChunks(): UploadModel|bool
    {
        $prefix = sprintf('/upload/media/%s/%s', date('Y'), date('m'));

        $saveDir = $this->mkSaveDir($prefix);

        return $this->mergeChunks($saveDir, UploadModel::TYPE_MEDIA);
    }

    /**
     * 删除加密媒体文件
     *
     * @param string $key
     * @return void
     */
    public function removeEncryptMedia(string $key): void
    {
        $dirname = pathinfo($key, PATHINFO_DIRNAME);
        $filename = pathinfo($key, PATHINFO_FILENAME);

        list($quality) = explode('.', $filename);

        $pattern = sprintf('%s/%s*', public_path($dirname), $quality);

        foreach (glob($pattern) as $filename) {
            unlink($filename);
        }
    }

    /**
     * 合并分片
     *
     * @param string $saveDir
     * @param int $uploadType
     * @param bool $rename
     * @return UploadModel|bool
     */
    protected function mergeChunks(string $saveDir, int $uploadType, bool $rename = true): UploadModel|bool
    {
        $post = $this->request->getPost();

        $extension = $this->getFileExtension($post['name']);

        $tmpFile = sprintf('%s/%s', tmp_path(), $this->generateFileName($extension));

        $prefix = tmp_path($post['md5']);

        $files = glob("{$prefix}.*");

        for ($i = 0; $i < count($files); $i++) {
            $chunkFile = sprintf('%s.%s', $prefix, $i);
            $content = file_get_contents($chunkFile);
            file_put_contents($tmpFile, $content, FILE_APPEND);
        }

        if (filesize($tmpFile) == $post['size']) {

            $name = $rename ? $this->generateFileName($extension) : $post['name'];

            $targetFile = sprintf('%s/%s', $saveDir, $name);

            rename($tmpFile, $targetFile);

            foreach ($files as $file) {
                unlink($file);
            }

            $upload = new UploadModel();

            $upload->type = $uploadType;
            $upload->path = $this->getRelativeFilePath($targetFile);
            $upload->mime = mime_content_type($targetFile);
            $upload->size = $post['size'];
            $upload->name = $post['name'];
            $upload->md5 = $post['md5'];

            $upload->create();

            return $upload;
        }

        return false;
    }

}
