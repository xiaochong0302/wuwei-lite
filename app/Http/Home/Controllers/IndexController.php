<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Index as IndexService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Traits\Client as ClientTrait;

class IndexController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/", name="home.index")
     */
    public function indexAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getHomeUrl();
            return $this->response->redirect($location);
        }

        $this->seo->setKeywords($this->siteInfo['keywords']);
        $this->seo->setDescription($this->siteInfo['description']);

        $service = new IndexService();

        $this->view->setVar('slides', $service->getSlides());
        $this->view->setVar('featured_courses', $service->getFeaturedCourses());
        $this->view->setVar('popular_courses', $service->getPopularCourses());
        $this->view->setVar('latest_courses', $service->getLatestCourses());
    }

}
