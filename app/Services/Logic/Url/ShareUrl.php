<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Url;

use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;
use Phalcon\Support\HelperFactory;

class ShareUrl extends AppService
{

    /**
     * WEB站点URL
     *
     * @var FullWebUrl
     */
    protected FullWebUrl $fullWebUrl;

    /**
     * H5站点URL
     *
     * @var FullH5Url
     */
    protected FullH5Url $fullH5Url;

    /**
     * 目标类型：h5|web
     *
     * @var string
     */
    protected string $targetType;

    use ClientTrait;

    public function __construct()
    {
        $this->fullWebUrl = new FullWebUrl();
        $this->fullH5Url = new FullH5Url();
    }

    public function handle(string $type, int $id = 0, int $referer = 0): string
    {
        if ($type == 'teacher') {
            $result = $this->getTeacherUrl($id);
        } elseif ($type == 'page') {
            $result = $this->getPageUrl($id);
        } elseif ($type == 'course') {
            $result = $this->getCourseUrl($id);
        } elseif ($type == 'chapter') {
            $result = $this->getChapterUrl($id);
        } else {
            $result = $this->getHomeUrl();
        }

        if ($referer > 0) {
            $result['h5'] = $this->withReferer($result['h5'], $referer);
            $result['web'] = $this->withReferer($result['web'], $referer);
        }

        $gotoH5 = $this->gotoH5Url();

        return $gotoH5 ? $result['h5'] : $result['web'];
    }

    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    public function getHomeUrl(): array
    {
        $webUrl = $this->fullWebUrl->getHomeUrl();

        $h5Url = $this->fullH5Url->getHomeUrl();

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getTeacherUrl(int $id): array
    {
        $webUrl = $this->fullWebUrl->getTeacherShowUrl($id);

        $h5Url = $this->fullH5Url->getTeacherInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getPageUrl(int $id): array
    {
        $webUrl = $this->fullWebUrl->getPageShowUrl($id);

        $h5Url = $this->fullH5Url->getPageInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getCourseUrl(int $id): array
    {
        $webUrl = $this->fullWebUrl->getCourseShowUrl($id);

        $h5Url = $this->fullH5Url->getCourseInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getChapterUrl(int $id): array
    {
        $webUrl = $this->fullWebUrl->getChapterShowUrl($id);

        $h5Url = $this->fullH5Url->getChapterInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    protected function withReferer(string $url, int $referer): string
    {
        $params = ['referer' => $referer];

        $helper = new HelperFactory();

        if (!$helper->includes($url, '?')) {
            $url .= '?' . http_build_query($params);
        } else {
            $url .= '&' . http_build_query($params);
        }

        return $url;
    }

    protected function gotoH5Url(): string
    {
        if (!$this->h5Enabled()) return false;

        if ($this->targetType == 'h5') return true;

        return $this->isMobileBrowser();
    }

}
