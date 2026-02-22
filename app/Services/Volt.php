<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\Course as CourseModel;
use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Review as ReviewModel;
use App\Services\Logic\Url\ShareUrl as ShareUrlService;
use Phalcon\Config\Config;
use Phalcon\Di\Di;

class Volt
{

    public static function config(string $path, $defaultValue = null): mixed
    {
        return kg_config($path, $defaultValue);
    }

    public static function setting(string $section, string $key = null, $defaultValue = null): mixed
    {
        return kg_setting($section, $key, $defaultValue);
    }

    public static function substr(string $str, int $start, int $length, string $suffix = '...'): string
    {
        return kg_substr($str, $start, $length, $suffix);
    }

    public static function duration(int $time): string
    {
        return kg_duration($time);
    }

    public static function arrayObject(array $array): mixed
    {
        return kg_objectify($array);
    }

    public static function fullUrl($uri, $args = null): string
    {
        return kg_full_url($uri, $args);
    }

    public static function anonymous(string $str): string
    {
        return kg_anonymous($str);
    }

    public static function humanNumber(int $number): string
    {
        return kg_human_number($number);
    }

    public static function humanSize(int $bytes): string
    {
        return kg_human_size($bytes);
    }

    public static function humanPrice(float $price): string
    {
        return kg_human_price($price);
    }

    public static function timeAgo(int $time): string
    {
        $locale = Di::getDefault()->getShared('locale');

        $diff = time() - $time;

        if ($diff > 365 * 86400) {
            return date('Y-m-d', $time);
        } elseif ($diff > 30 * 86400) {
            return $locale->query('ago_month_x', ['x' => floor($diff / 30 / 86400)]);
        } elseif ($diff > 7 * 86400) {
            return $locale->query('ago_week_x', ['x' => floor($diff / 7 / 86400)]);
        } elseif ($diff > 86400) {
            return $locale->query('ago_day_x', ['x' => floor($diff / 86400)]);
        } elseif ($diff > 3600) {
            return $locale->query('ago_hour_x', ['x' => floor($diff / 3600)]);
        } elseif ($diff > 60) {
            return $locale->query('ago_minute_x', ['x' => floor($diff / 60)]);
        } else {
            return $locale->query('ago_second_x', ['x' => $diff]);
        }
    }

    public static function iconLink(string $path, bool $local = true, string $version = null): string
    {
        $href = self::staticUrl($path, $local, $version);

        return sprintf('<link rel="shortcut icon" href="%s">', $href);
    }

    public static function cssLink(string $path, bool $local = true, string $version = null): string
    {
        $href = self::staticUrl($path, $local, $version);

        return sprintf('<link rel="stylesheet" type="text/css" href="%s">', $href);
    }

    public static function jsInclude(string $path, bool $local = true, string $version = null): string
    {
        $src = self::staticUrl($path, $local, $version);

        return sprintf('<script type="text/javascript" src="%s"></script>', $src);
    }

    public static function staticUrl(string $path, bool $local = true, string $version = null): string
    {
        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $baseUri = rtrim($config->get('static_base_uri'), '/');
        $path = ltrim($path, '/');
        $url = $local ? $baseUri . '/' . $path : $path;
        $version = $version ?: $config->get('static_version');

        if ($version) {
            $url .= '?v=' . $version;
        }

        return $url;
    }

    public static function shareUrl(string $type, int $id, int $referer = 0): string
    {
        $service = new ShareUrlService();

        return $service->handle($type, $id, $referer);
    }

    public static function commentStatus(int $type): string
    {
        $list = CommentModel::publishTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function reviewStatus(int $type): string
    {
        $list = ReviewModel::publishTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function orderStatus(int $type): string
    {
        $list = OrderModel::statusTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function refundStatus(int $type): string
    {
        $list = RefundModel::statusTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function courseLevel(int $type): string
    {
        $list = CourseModel::levelTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function chapterModel(int $type): string
    {
        $list = ChapterModel::modelTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function saleItemType(int $type): string
    {
        $list = KgSaleModel::itemTypes();

        return $list[$type] ?? 'N/A';
    }

    public static function paymentType(int $type): string
    {
        $list = OrderModel::paymentTypes();

        return $list[$type] ?? 'N/A';
    }

}
