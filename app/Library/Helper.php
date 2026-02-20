<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use App\Caches\Setting as SettingCache;
use App\Library\Utils\FileInfo;
use App\Models\KgSale as KgSaleModel;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Storage as StorageService;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Phalcon\Config\Config;
use Phalcon\Di\Di;

/**
 * 获取字符长度
 *
 * @param string $str
 * @return int
 */
function kg_strlen(string $str): int
{
    return mb_strlen($str, 'utf-8');
}

/**
 * 字符截取
 *
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $suffix
 * @return string
 */
function kg_substr(string $str, int $start, int $length, string $suffix = '...'): string
{
    $result = mb_substr($str, $start, $length, 'utf-8');

    return $str == $result ? $str : $result . $suffix;
}

/**
 * 从数组获取随机值
 *
 * @param array $array
 * @param int $amount
 * @return mixed
 */
function kg_array_rand(array $array, int $amount = 1): mixed
{
    $max = count($array);

    if ($amount > $max) {
        $amount = $max;
    }

    $keys = array_rand($array, $amount);

    if ($amount == 1) {
        return $array[$keys];
    }

    $result = [];

    foreach ($keys as $key) {
        $result[] = $array[$key];
    }

    return $result;
}

/**
 * 占位替换
 *
 * @param string $str
 * @param array $placeholders
 * @return string
 */
function kg_ph_replace(string $str, array $placeholders = []): string
{
    if (empty($placeholders)) return $str;

    foreach ($placeholders as $key => $value) {
        $str = str_replace('{' . $key . '}', $value, $str);
        $str = str_replace('%' . $key . '%', $value, $str);
    }

    return $str;
}

/**
 * 批量插入SQL
 *
 * @param string $table
 * @param array $rows
 * @return string|false
 */
function kg_batch_insert_sql(string $table, array $rows = []): string|false
{
    if (count($rows) == 0) return false;

    $fields = implode(',', array_keys($rows[0]));

    $values = [];

    foreach ($rows as $row) {
        $items = array_map(function ($item) {
            return sprintf("'%s'", htmlspecialchars($item, ENT_QUOTES));
        }, $row);
        $values[] = sprintf('(%s)', implode(',', $items));
    }

    $values = implode(',', $values);

    return sprintf("INSERT INTO %s (%s) VALUES %s", $table, $fields, $values);
}

/**
 * uniqid封装
 *
 * @param string $prefix
 * @param bool $more
 * @return string
 */
function kg_uniqid(string $prefix = '', bool $more = false): string
{
    $prefix = $prefix ?: rand(1000, 9999);

    return uniqid($prefix, $more);
}

/**
 * json_encode(不转义斜杠和中文)
 *
 * @param mixed $data
 * @return string|false
 */
function kg_json_encode($data): string|false
{
    $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

    return json_encode($data, $options);
}

/**
 * 返回数组中指定的一列
 *
 * @param array $rows
 * @param string|int|null $columnKey
 * @param string|int|null $indexKey
 * @return array
 */
function kg_array_column(array $rows, $columnKey, $indexKey = null): array
{
    $result = array_column($rows, $columnKey, $indexKey);

    return array_unique($result);
}

/**
 * 多维数组 array_unique
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function kg_array_unique_multi(array $array, string $key): array
{
    $uniqueArray = [];
    $encounteredKeys = [];

    foreach ($array as $item) {
        if (!isset($item[$key])) {
            continue;
        }
        if (!in_array($item[$key], $encounteredKeys)) {
            $uniqueArray[] = $item;
            $encounteredKeys[] = $item[$key];
        }
    }

    return $uniqueArray;
}

/**
 * 数组转对象
 *
 * @param array $array
 * @return array|object
 */
function kg_array_object(array $array): array|object
{
    return json_decode(json_encode($array));
}

/**
 * 下载文件
 *
 * @param string $filePath
 * @return void
 */
function kg_download(string $filePath): void
{
    $basename = pathinfo($filePath, PATHINFO_BASENAME);
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeType = FileInfo::getMimeTypeByExt($ext);

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment;filename="' . $basename . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Cache-Control: max-age=0');

    if (ob_get_level()) {
        ob_end_clean();
    }

    readfile($filePath);

    flush();
}

/**
 * 获取站点基准URL
 *
 * @return string
 */
function kg_site_url(): string
{
    $scheme = filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

    return sprintf('%s://%s', $scheme, $host);
}

/**
 * 获取站点设置
 *
 * @param string $section
 * @param string|null $key
 * @param mixed $defaultValue
 * @return mixed
 */
function kg_setting(string $section, string $key = null, $defaultValue = null): mixed
{
    $cache = new SettingCache();

    $settings = $cache->get($section);

    if (!$key) return $settings;

    return $settings[$key] ?? $defaultValue;
}

/**
 * 获取站点配置
 *
 * @param string $path
 * @param mixed $defaultValue
 * @return mixed
 */
function kg_config(string $path, $defaultValue = null): mixed
{
    /**
     * @var Config $config
     */
    $config = Di::getDefault()->getShared('config');

    return $config->path($path, $defaultValue);
}

/**
 * 获取时长
 *
 * @param int $time
 * @return string
 */
function kg_duration(int $time): string
{
    $result = [
        'h' => '00',
        'm' => '00',
        's' => '00',
    ];

    if ($time > 0) {

        $hours = floor($time / 3600);
        $minutes = floor(($time - $hours * 3600) / 60);
        $seconds = $time % 60;

        if ($hours > 0) {
            $result['h'] = sprintf('%02d', $hours);
        }

        if ($minutes > 0) {
            $result['m'] = sprintf('%02d', $minutes);
        }

        if ($seconds > 0) {
            $result['s'] = sprintf('%02d', $seconds);
        }
    }

    if ($result['h'] == '00') {
        unset($result['h']);
    }

    return implode(':', $result);
}


/**
 * 匿名字符串
 *
 * @param string $str
 * @return string
 */
function kg_anonymous(string $str): string
{
    $length = mb_strlen($str);

    if (str_contains($str, '@')) {
        $start = 3;
        $end = mb_stripos($str, '@');
    } else {
        $start = ceil($length / 4);
        $end = $length - $start - 1;
    }

    $list = [];

    for ($i = 0; $i < $length; $i++) {
        $list[] = ($i < $start || $i > $end) ? mb_substr($str, $i, 1) : '*';
    }

    return join('', $list);
}

/**
 * 友好数字格式化
 *
 * @param int $number
 * @return string
 */
function kg_human_number(int $number): string
{
    if ($number > 1000000) {
        $result = round($number / 1000000, 1) . 'm';
    } elseif ($number > 1000) {
        $result = round($number / 1000, 1) . 'k';
    } else {
        $result = $number;
    }

    return $result;
}

/**
 * 友好尺寸格式化
 *
 * @param int $bytes
 * @return string
 */
function kg_human_size(int $bytes): string
{
    if (!$bytes) return '0 KB';

    $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    $exp = floor(log($bytes) / log(1024));

    return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
}

/**
 * 友好价格格式化
 *
 * @param float $price
 * @return string
 */
function kg_human_price(float $price): string
{
    static $currency, $currencies;

    if (!$currency) {
        $currency = kg_setting('site', 'currency', 'USD');
    }

    if (!$currencies) {
        $currencies = KgSaleModel::currencies();
    }

    return sprintf('%s%0.2f', $currencies[$currency]['symbol'], $price);
}

/**
 * 获取默认用户头像路径
 *
 * @return string
 */
function kg_default_user_avatar_path(): string
{
    return '/upload/img/default/avatar.png';
}

/**
 * 获取默认分类图标路径
 *
 * @return string
 */
function kg_default_category_icon_path()
{
    return '/upload/img/default/category.png';
}

/**
 * 获取默认课程封面路径
 *
 * @return string
 */
function kg_default_course_cover_path(): string
{
    return '/upload/img/default/course.png';
}

/**
 * 获取默认套餐封面路径
 *
 * @return string
 */
function kg_default_package_cover_path(): string
{
    return '/upload/img/default/package.png';
}

/**
 * 获取默认轮播封面路径
 *
 * @return string
 */
function kg_default_slide_cover_path(): string
{
    return '/upload/img/default/slide.png';
}

/**
 * 获取默认会员封面路径
 *
 * @return string
 */
function kg_default_vip_cover_path()
{
    return '/img/default/vip.png';
}

/**
 * 获取存储基准URL
 *
 * @return string
 */
function kg_cos_url(): string
{
    $service = new StorageService();

    return $service->getBaseUrl();
}

/**
 * 获取存储图片URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_img_url(string $path, ?string $style = null): string
{
    if (empty($style)) $style = '';

    if (str_starts_with($path, 'http')) return $path;

    return sprintf('%s%s%s', kg_cos_url(), $path, $style);
}

/**
 * 获取用户头像URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_user_avatar_url(string $path, ?string $style = null): string
{
    $path = $path ?: kg_default_user_avatar_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取课程封面URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_course_cover_url(string $path, ?string $style = null): string
{
    $path = $path ?: kg_default_course_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取套餐封面URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_package_cover_url(string $path, ?string $style = null)
{
    $path = $path ?: kg_default_package_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取轮播封面URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_slide_cover_url(string $path, ?string $style = null): string
{
    $path = $path ?: kg_default_course_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取会员封面URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_vip_cover_url(string $path, ?string $style = null)
{
    $path = $path ?: kg_default_vip_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 清除存储图片处理样式
 *
 * @param string $path
 * @return string
 */
function kg_cos_img_style_trim(string $path): string
{
    return preg_replace('/!\w+/', '', $path);
}

/**
 * 解析标题slug
 *
 * @param string $title
 * @return string
 */
function kg_parse_slug(string $title): string
{
    $symbols = [
        ',', ';', ':', '.', '–', '—', '_', '/', '|', '@', '#', '%', '^', '*', '~',
        '&', '+', '<', '>', '(', ')', '[', ']', '{', '}', '!', '?', '\\', '\'', '"',
        '，', '；', '：', '。', '-', '《', '》', '（', '）', '【', '】', '！', '？', '‘', '’', '“', '”',
    ];

    $germanMappings = [
        'ä' => 'ae',
        'ö' => 'oe',
        'ü' => 'ue',
        'ß' => 'ss',
    ];

    $title = strtr($title, $germanMappings);

    // transliterate accented characters to ASCII
    $title = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);

    $title = str_replace($symbols, '-', $title);
    $title = preg_replace('/\s+/u', ' ', $title);
    $title = str_replace(' ', '-', $title);
    $title = preg_replace('/-+/', '-', $title);
    $title = trim($title, '-');

    return strtolower($title);
}

/**
 * 解析内容摘要
 *
 * @param string $content
 * @param int $length
 * @return string
 */
function kg_parse_summary(string $content, int $length = 100): string
{
    $content = trim(strip_tags($content));

    return kg_substr($content, 0, $length);
}

/**
 * 解析关键字
 *
 * @param string $content
 * @return string
 */
function kg_parse_keywords(string $content): string
{
    $search = ['|', ';', '；', '、', ','];

    $keywords = str_replace($search, '@', $content);

    $keywords = explode('@', $keywords);

    $list = [];

    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (kg_strlen($keyword) > 1) {
            $list[] = $keyword;
        }
    }

    return implode(', ', $list);
}

/**
 * 解析markdown内容
 *
 * @param string $content
 * @param string $htmlInput (escape|strip)
 * @param bool $allowUnsafeLinks
 * @return string
 * @throws CommonMarkException
 */
function kg_parse_markdown(string $content, string $htmlInput = 'escape', bool $allowUnsafeLinks = false): string
{
    $parser = new GithubFlavoredMarkdownConverter([
        'html_input' => $htmlInput,
        'allow_unsafe_links' => $allowUnsafeLinks,
    ]);

    return $parser->convert($content);
}

/**
 * 构造全路径url
 *
 * @param array|string $uri
 * @param mixed $args
 * @return string
 */
function kg_full_url(mixed $uri, mixed $args = null): string
{
    /**
     * @var $url Phalcon\Mvc\Url
     */
    $url = Di::getDefault()->getShared('url');

    $baseUrl = kg_site_url();

    return $baseUrl . $url->get($uri, $args);
}

/**
 * 获取H5首页地址
 *
 * @return string
 */
function kg_h5_index_url(): string
{
    $service = new FullH5UrlService();

    $url = $service->getHomeUrl();

    if ($pos = strpos($url, '?')) {
        return substr($url, 0, $pos);
    }

    return $url;
}
