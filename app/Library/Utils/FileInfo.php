<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

class FileInfo
{

    public static function isVideo(string $mime): bool
    {
        $case1 = self::isSecure($mime);

        $case2 = str_contains($mime, 'video');

        return $case1 && $case2;
    }

    public static function isAudio(string $mime): bool
    {
        $case1 = self::isSecure($mime);

        $case2 = str_contains($mime, 'audio');

        return $case1 && $case2;
    }

    public static function isImage(string $mime): bool
    {
        $case1 = self::isSecure($mime);

        $case2 = str_contains($mime, 'image');

        return $case1 && $case2;
    }

    public static function isSecure(string $mime): bool
    {
        return in_array($mime, self::getMimeTypes());
    }

    public static function getMimeType(string $file): string|false
    {
        $info = new \finfo(FILEINFO_MIME_TYPE);

        return $info->file($file);
    }

    public static function getMimeTypeByExt(string $ext): ?string
    {
        $mimeTypes = self::getMimeTypes();

        return $mimeTypes[$ext] ?? null;
    }

    public static function getMimeTypes(): array
    {
        return [
            'aac' => 'audio/aac',
            'ogg' => 'audio/ogg',
            'wav' => 'audio/wav',
            'mp3' => 'audio/mpeg',
            'weba' => 'audio/webm',
            'm4a' => 'audio/x-m4a',
            'wma' => 'audio/x-ms-wma',

            'mp4' => 'video/mp4',
            '3gp' => 'video/3gpp',
            'mpeg' => 'video/mpeg',
            'webm' => 'video/webm',
            'flv' => 'video/x-flv',
            'avi' => 'video/x-msvideo',
            'mkv' => 'video/x-matroska',
            'wmv' => 'video/x-ms-wmv',

            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'ico' => 'image/x-icon',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'psd' => 'image/vnd.adobe.photoshop',

            'rar' => 'application/vnd.rar',
            'tar' => 'application/x-tar',
            '7z' => 'application/x-7z-compressed',
            'bz' => 'application/x-bzip',
            'bz2' => 'application/x-bzip2',
            'gz' => 'application/gzip',
            'zip' => 'application/zip',

            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'json' => 'application/json',
            'xml' => 'application/xml',

            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'swf' => 'application/x-shockwave-flash',
            'vsd' => 'application/vnd.visio',
            'rtf' => 'application/rtf',

            'ttf' => 'font/ttf',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
    }

}
