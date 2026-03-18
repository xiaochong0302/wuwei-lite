<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Library\GeoIp;
use App\Library\Language;
use App\Models\KgClient as KgClientModel;
use Phalcon\Di\Di;
use Phalcon\Http\Request;
use Phalcon\Support\HelperFactory;
use Phalcon\Translate\Adapter\NativeArray as TranslateAdapter;
use WhichBrowser\Parser as BrowserParser;

trait Client
{

    protected function getLanguages(): array
    {
        $obj = new Language();

        return $obj->getLanguages();
    }

    protected function getJsLocale(): array
    {
        /**
         * @var TranslateAdapter $locale
         */
        $locale = Di::getDefault()->get('locale');

        $helper = new HelperFactory();

        $result = [];

        foreach ($locale->toArray() as $key => $value) {
            if ($helper->startsWith($key, 'js.')) {
                $index = str_replace('js.', '', $key);
                $result[$index] = $value;
            }
        }

        return $result;
    }

    protected function getClientIp(): string
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        return $request->getClientAddress() ?: '';
    }

    protected function getClientType(): int
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $platform = $request->getHeader('X-Platform');

        $types = array_flip(KgClientModel::types());

        if (!empty($platform) && isset($types[$platform])) {
            return $types[$platform];
        }

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        $clientType = KgClientModel::TYPE_PC;

        if ($result->isMobile()) {
            $clientType = KgClientModel::TYPE_H5;
        }

        return $clientType;
    }

    protected function getClientCountryCode(): string
    {
        try {

            $ip = $this->getClientIp();

            $geo = new GeoIp($ip);

            $code = $geo->getCountryCode();

        } catch (\Exception $e) {

        }

        return !empty($code) ? $code : '';
    }

    protected function isMobileBrowser(): bool
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        return $result->isMobile();
    }

    protected function isSearchBot(): bool
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $bots = array('Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider');

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function h5Enabled(): bool
    {
        $file = public_path('h5/index.html');

        return file_exists($file);
    }

}
