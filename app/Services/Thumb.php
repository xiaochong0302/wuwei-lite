<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Models\ThumbLink as ThumbLinkModel;
use App\Repos\ThumbLink as ThumbLinkRepo;
use Exception as Exception;
use Intervention\Image\ImageManager;
use Phalcon\Logger\Logger;

class Thumb extends Service
{

    /**
     * @var ImageManager
     */
    protected ImageManager $manager;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->manager = ImageManager::gd();

        $this->logger = $this->getLogger('thumb');
    }

    public function handle(string $file, string $style): string
    {
        $filePath = $this->getAbsoluteFilePath($file);

        if ($style == 'avatar_160') {
            $thumbPath = $this->handleAvatar160Image($filePath);
        } elseif ($style == 'cover_270') {
            $thumbPath = $this->handleCover270Image($filePath);
        } elseif ($style == 'content_800') {
            $thumbPath = $this->handleContent800Image($filePath);
        } elseif ($style == 'slide_1100') {
            $thumbPath = $this->handleSlide1100Image($filePath);
        } else {
            $thumbPath = $filePath;
        }

        return $this->getRelativeFilePath($thumbPath);
    }

    protected function handleAvatar160Image(string $filePath): string
    {
        $width = 160;
        $height = 160;

        $thumbPath = $this->getThumbPath($filePath, $width, $height);
        $thumbFile = $this->getThumbFileTarget($thumbPath);

        if ($thumbFile) return $thumbFile;

        try {

            $image = $this->manager->read($filePath);

            if ($image->width() > 320) {
                $image->resize($width, $height)->save($thumbPath);
                if (file_exists($thumbPath)) {
                    $this->saveThumbLink($thumbPath, $thumbPath);
                } else {
                    $thumbPath = $filePath;
                }
            } else {
                $this->saveThumbLink($thumbPath, $filePath);
                $thumbPath = $filePath;
            }

        } catch (Exception $e) {

            $this->logger->error($this->formatException('Avatar Thumb Exception', $e));

            $thumbPath = $filePath;
        }

        return $thumbPath;
    }

    protected function handleCover270Image(string $filePath): string
    {
        $width = 270;
        $height = 152;

        $thumbPath = $this->getThumbPath($filePath, $width, $height);
        $thumbFile = $this->getThumbFileTarget($thumbPath);

        if ($thumbFile) return $thumbFile;

        try {

            $image = $this->manager->read($filePath);

            if ($image->width() > 540) {
                $image->resize($width, $height)->save($thumbPath);
                if (file_exists($thumbPath)) {
                    $this->saveThumbLink($thumbPath, $thumbPath);
                } else {
                    $thumbPath = $filePath;
                }
            } else {
                $this->saveThumbLink($thumbPath, $filePath);
                $thumbPath = $filePath;
            }

        } catch (Exception $e) {

            $this->logger->error($this->formatException('Cover Thumb Exception', $e));

            $thumbPath = $filePath;
        }

        return $thumbPath;
    }

    protected function handleContent800Image(string $filePath): string
    {
        $width = 800;
        $height = 600;

        $thumbPath = $this->getThumbPath($filePath, $width, $height);
        $thumbFile = $this->getThumbFileTarget($thumbPath);

        if ($thumbFile) return $thumbFile;

        try {

            $image = $this->manager->read($filePath);

            if ($image->width() > 1280) {
                $height = intval($image->height() * $width / $image->width());
                $image->resize($width, $height)->save($thumbPath);
                if (file_exists($thumbPath)) {
                    $this->saveThumbLink($thumbPath, $thumbPath);
                } else {
                    $thumbPath = $filePath;
                }
            } else {
                $this->saveThumbLink($thumbPath, $filePath);
                $thumbPath = $filePath;
            }

        } catch (Exception $e) {

            $this->logger->error($this->formatException('Content Thumb Exception', $e));

            $thumbPath = $filePath;
        }

        return $thumbPath;
    }

    protected function handleSlide1100Image(string $filePath): string
    {
        $width = 1100;
        $height = 330;

        $thumbPath = $this->getThumbPath($filePath, $width, $height);
        $thumbFile = $this->getThumbFileTarget($thumbPath);

        if ($thumbFile) return $thumbFile;

        try {

            $image = $this->manager->read($filePath);

            if ($image->width() > 1920) {
                $image->resize($width, $height)->save($thumbPath);
                if (file_exists($thumbPath)) {
                    $this->saveThumbLink($thumbPath, $thumbPath);
                } else {
                    $thumbPath = $filePath;
                }
            } else {
                $this->saveThumbLink($thumbPath, $filePath);
                $thumbPath = $filePath;
            }

        } catch (Exception $e) {

            $this->logger->error($this->formatException('Slide Thumb Exception', $e));

            $thumbPath = $filePath;
        }

        return $thumbPath;
    }

    protected function getThumbPath(string $filePath, int $width, int $height): string
    {
        $dirname = pathinfo($filePath, PATHINFO_DIRNAME);
        $filename = pathinfo($filePath, PATHINFO_FILENAME);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return sprintf('%s/%s_%sx%s.%s', $dirname, $filename, $width, $height, $extension);
    }

    protected function getAbsoluteFilePath(string $filePath): string
    {
        return sprintf('%s%s', public_path(), $filePath);
    }

    protected function getRelativeFilePath(string $filePath): string
    {
        return str_replace(public_path(), '', $filePath);
    }

    protected function getThumbFileTarget(string $filePath): string
    {
        $cache = $this->getCache();

        $relativePath = $this->getRelativeFilePath($filePath);

        $crc32 = crc32($relativePath);

        $keyName = sprintf('thumb-%s', $crc32);

        $thumbFile = $cache->get($keyName);

        if ($thumbFile) return $thumbFile;

        $linkRepo = new ThumbLinkRepo();

        $link = $linkRepo->findBySourceCRC32($crc32);

        if ($link) {
            $cache->set($keyName, $link->target_path, 86400);
        }

        return $link ? $link->target_path : '';
    }

    protected function saveThumbLink(string $sourcePath, string $targetPath): ThumbLinkModel
    {
        $thumbLink = new ThumbLinkModel();

        $sourcePath = $this->getRelativeFilePath($sourcePath);
        $targetPath = $this->getRelativeFilePath($targetPath);

        $thumbLink->source_crc32 = crc32($sourcePath);
        $thumbLink->target_crc32 = crc32($targetPath);
        $thumbLink->target_path = $targetPath;
        $thumbLink->source_path = $sourcePath;

        $thumbLink->create();

        return $thumbLink;
    }

    protected function formatException(string $prefix, Exception $e): string
    {
        return $prefix . ':' . kg_json_encode([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
    }

}
