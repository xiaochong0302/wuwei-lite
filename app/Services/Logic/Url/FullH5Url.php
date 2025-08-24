<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Url;

use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\Service as AppService;

class FullH5Url extends AppService
{

    /**
     * 基准地址
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * 跳转来源
     *
     * @var string
     */
    protected string $source = 'pc';

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    public function getHomeUrl(): string
    {
        return $this->getFullUrl('/index/index');
    }

    public function getAccountRegisterUrl(): string
    {
        return $this->getFullUrl('/account/register');
    }

    public function getAccountLoginUrl(): string
    {
        return $this->getFullUrl('/account/login');
    }

    public function getAccountForgetUrl(): string
    {
        return $this->getFullUrl('/account/forget');
    }

    public function getCourseListUrl(): string
    {
        return $this->getFullUrl('/course/list');
    }

    public function getTeacherListUrl(): string
    {
        return $this->getFullUrl('/teacher/list');
    }

    public function getTeacherIndexUrl($id)
    {
        return $this->getFullUrl('/teacher/index', ['id' => $id]);
    }

    public function getVipIndexUrl()
    {
        return $this->getFullUrl('/vip/index');
    }

    public function getTeacherInfoUrl(int $id): string
    {
        return $this->getFullUrl('/teacher/info', ['id' => $id]);
    }

    public function getPageInfoUrl(int $id): string
    {
        return $this->getFullUrl('/page/info', ['id' => $id]);
    }

    public function getCourseInfoUrl(int $id): string
    {
        return $this->getFullUrl('/course/info', ['id' => $id]);
    }

    public function getChapterInfoUrl(int $id): string
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if ($chapter->model == ChapterModel::MODEL_VIDEO) {
            return $this->getFullUrl('/chapter/vod', ['id' => $id]);
        } elseif ($chapter->model == ChapterModel::MODEL_ARTICLE) {
            return $this->getFullUrl('/chapter/read', ['id' => $id]);
        } else {
            return $this->getHomeUrl();
        }
    }

    protected function getFullUrl(string $path, array $params = []): string
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->baseUrl, $path, $query);
    }

    protected function getBaseUrl(): string
    {
        return sprintf('%s/h5/#/pages', kg_site_url());
    }

}
