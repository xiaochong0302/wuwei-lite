<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Library\Utils\FileInfo;
use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;
use InvalidArgumentException;
use Phalcon\Support\HelperFactory;
use RuntimeException;

class Storage extends Service
{

    /**
     * mime类型
     */
    const MIME_IMAGE = 'image';
    const MIME_VIDEO = 'video';
    const MIME_AUDIO = 'audio';

    /**
     * 获取基准URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $baseUri = kg_config('storage_base_uri');

        $helper = new HelperFactory();

        if (!$helper->startsWith($baseUri, 'http')) {
            $baseUri = kg_setting('site', 'url');
        }

        return rtrim($baseUri, '/');
    }

    /**
     *  获取图片URL
     *
     * @param string $key
     * @param string|null $style
     * @return string
     */
    public function getImageUrl(string $key, ?string $style = null): string
    {
        $style = $style ?: '';

        return $this->getBaseUrl() . $key . $style;
    }

    /**
     * 上传临时文件
     *
     * @return array|bool
     */
    public function uploadTempFile(): array|bool
    {
        if ($this->request->hasFiles()) {

            $files = $this->request->getUploadedFiles(true);

            $list = [];

            foreach ($files as $file) {
                $ext = $this->getFileExtension($file->getName());
                $dot = $ext ? '.' : '';
                $name = sprintf('%s%s%s', kg_uniqid(), $dot, $ext);
                $destination = tmp_path($name);
                $file->moveTo($destination);
                $list[] = [
                    'name' => $file->getName(),
                    'type' => $file->getType(),
                    'size' => $file->getSize(),
                    'path' => $destination,
                ];
            }

            return $list[0] ?: false;
        }

        return false;
    }

    /**
     * 创建目录
     *
     * @param string $path
     * @return string
     */
    public function mkSaveDir(string $path): string
    {
        /**
         * 兼容性处理
         */
        $path = $this->getRelativeFilePath($path);

        $dir = sprintf('%s%s', public_path(), $path);

        if (!is_dir($dir)) {
            if (mkdir($dir, 0777, true) === false) {
                $message = sprintf('mkdir: "%s" failed', $dir);
                throw new RuntimeException($message);
            }
            file_put_contents($dir . '/' . 'index.html', 'Access Denied');
        }

        return $dir;
    }

    /**
     * 删除文件
     *
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool
    {
        $filename = public_path($key);

        return unlink($filename);
    }

    /**
     * 上传文件
     *
     * @param string $prefix
     * @param string $mimeType
     * @param int $uploadType
     * @param string|null $fileName
     * @return UploadModel|bool
     */
    protected function upload(string $prefix, string $mimeType, int $uploadType, string $fileName = null): UploadModel|bool
    {
        $list = [];

        $saveDir = $this->mkSaveDir($prefix);

        if ($this->request->hasFiles()) {

            $files = $this->request->getUploadedFiles(true);

            $uploadRepo = new UploadRepo();

            foreach ($files as $file) {

                if (!$this->checkFile($file->getRealType(), $mimeType)) {
                    $message = sprintf('MimeType: "%s" not in secure whitelist', $file->getRealType());
                    throw new InvalidArgumentException($message);
                }

                $md5 = md5_file($file->getTempName());

                $upload = $uploadRepo->findByMd5($md5);

                if (!$upload) {

                    $extension = $this->getFileExtension($file->getName());

                    if (empty($fileName)) {
                        $keyName = $this->generateFileName($extension);
                    } else {
                        $keyName = $fileName;
                    }

                    $destination = $saveDir . '/' . $keyName;

                    $data = [
                        'name' => $file->getName(),
                        'mime' => $file->getRealType(),
                        'size' => $file->getSize(),
                        'path' => $this->getRelativeFilePath($destination),
                        'type' => $uploadType,
                        'md5' => $md5,
                    ];

                    $file->moveTo($destination);

                    $upload = new UploadModel();
                    $upload->assign($data);
                    $upload->create();
                }

                $list[] = $upload;
            }
        }

        return $list[0] ?: false;
    }

    /**
     * 检查上传文件
     *
     * @param string $mime
     * @param string $alias
     * @return bool
     */
    protected function checkFile(string $mime, string $alias): bool
    {
        return match ($alias) {
            self::MIME_IMAGE => FileInfo::isImage($mime),
            self::MIME_VIDEO => FileInfo::isVideo($mime),
            self::MIME_AUDIO => FileInfo::isAudio($mime),
            default => FileInfo::isSecure($mime),
        };
    }

    /**
     * 生成文件存储名
     *
     * @param string $extension
     * @return string
     */
    protected function generateFileName(string $extension): string
    {
        $name = uniqid();

        $dot = $extension ? '.' : '';

        return sprintf('%s%s%s', $name, $dot, $extension);
    }

    /**
     * 获取文件扩展名
     *
     * @param string $filename
     * @return string
     */
    protected function getFileExtension(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return strtolower($extension);
    }

    /**
     * 获取文件相对路径
     *
     * @param string $path
     * @return string
     */
    protected function getRelativeFilePath(string $path): string
    {
        $path = str_replace(public_path(), '', $path);

        return rtrim($path, '/');
    }

}
