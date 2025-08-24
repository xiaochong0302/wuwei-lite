<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class Language
{

    public function getLanguages(): array
    {
        $translatedLanguages = $this->getTranslatedLanguages();

        $options = $this->getLanguageOptions();

        foreach ($options as $code => $title) {
            if (!in_array($code, $translatedLanguages)) {
                unset($options[$code]);
            }
        }

        return $options;
    }

    protected function getTranslatedLanguages(): array
    {
        $dir = locale_path();

        $files = scandir($dir);

        $result = [];

        foreach ($files as $file) {
            if (!in_array($file, ['.', '..'])) {
                $result[] = $file;
            }
        }

        return $result;
    }

    protected function getLanguageOptions(): array
    {
        return [
            'en' => 'English',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'ru' => 'Русский',
            'ja' => '日本語',
        ];
    }

}
