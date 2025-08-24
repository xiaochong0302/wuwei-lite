<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Plugins;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;

class Locale extends Injectable
{

    public function getTranslator(string $module): NativeArray
    {
        $language = kg_setting('site', 'language', 'en');

        if ($this->cookies->has('language')) {
            $language = $this->cookies->get('language');
        }

        $moduleFile = locale_path("{$language}/{$module}.php");
        $commonFile = locale_path("{$language}/common.php");

        $moduleContent = require $moduleFile;
        $commonContent = require $commonFile;

        $content = array_merge($commonContent, $moduleContent);

        $interpolator = new InterpolatorFactory();

        return new NativeArray($interpolator, ['content' => $content]);
    }

}
