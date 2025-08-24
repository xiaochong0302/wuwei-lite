<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Page\PageInfo as PageInfoService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Traits\Client as ClientTrait;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/{id}/{slug}", name="home.page.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getPageInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new PageInfoService();

        $page = $service->handle($id);

        if ($page['deleted'] == 1) {
            $this->notFound();
        }

        if ($page['published'] == 0) {
            $this->notFound();
        }

        $title = $this->locale->query('page_page_x', ['x' => $page['title']]);

        $this->seo->prependTitle($title);
        $this->seo->setKeywords($page['keywords']);
        $this->seo->setDescription($page['summary']);

        $this->view->setVar('page', $page);
    }

}
