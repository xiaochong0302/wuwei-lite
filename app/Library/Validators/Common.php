<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library\Validators;

use Phalcon\Support\HelperFactory;

class Common
{

    public static function email(mixed $str): bool
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function url(mixed $str): bool
    {
        $helper = new HelperFactory();

        if ($helper->startsWith($str, '//')) {
            $str = 'http:' . $str;
        }

        return filter_var($str, FILTER_VALIDATE_URL) !== false;
    }

    public static function intNumber(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function floatNumber(mixed $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        if (!str_contains($value, '.')) {
            return false;
        }

        $head = strstr($value, '.', true);

        if ($head[0] == '0' && strlen($head) > 1) {
            return false;
        }

        return true;
    }

    public static function positiveNumber(mixed $value): bool
    {
        if (!self::intNumber($value)) {
            return false;
        }

        return $value > 0;
    }

    public static function name(mixed $str): bool
    {
        $pattern = '/^[A-Za-z0-9]{2,15}$/u';

        return (bool)preg_match($pattern, $str);
    }

    public static function password(mixed $str): bool
    {
        $pattern = '/^[[:graph:]]{6,16}$/';

        return (bool)preg_match($pattern, $str);
    }

    public static function birthday(mixed $str): bool
    {
        $pattern = '/^(19|20)\d{2}-(1[0-2]|0[1-9])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

        return (bool)preg_match($pattern, $str);
    }

    public static function date(mixed $str, string $format = 'Y-m-d'): bool
    {
        $date = date($format, strtotime($str));

        return $str == $date;
    }

    public static function image(mixed $path): bool
    {
        $exts = ['png', 'gif', 'jpg', 'jpeg', 'webp'];

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        return in_array(strtolower($ext), $exts);
    }

}
