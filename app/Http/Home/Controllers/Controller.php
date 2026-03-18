<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Common as CommonService;
use App\Library\Seo as Seo;
use App\Models\User as UserModel;
use App\Traits\Auth as AuthTrait;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;
    use AuthTrait;

    /**
     * @var array
     */
    protected array $siteInfo;

    /**
     * @var Seo
     */
    protected Seo $seo;

    /**
     * @var UserModel
     */
    protected UserModel $authUser;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $commonService = new CommonService();

        $this->siteInfo = $commonService->getSiteInfo();

        if ($this->siteInfo['status'] == 'offline') {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'maintain',
            ]);
            return false;
        }

        $this->authUser = $this->getCurrentUser(true);

        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        return true;
    }

    public function initialize()
    {
        $this->eventsManager->fire('Site:afterView', $this, $this->authUser);

        $commonService = new CommonService();

        $navs = $commonService->getNavs();
        $appInfo = $commonService->getAppInfo();
        $contactInfo = $commonService->getContactInfo();

        $languages = $this->getLanguages();
        $jsLocale = $this->getJsLocale();

        $this->seo = $commonService->getSeo();

        $this->seo->title = $this->siteInfo['title'];

        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('navs', $navs);
        $this->view->setVar('languages', $languages);
        $this->view->setVar('js_locale', $jsLocale);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('contact_info', $contactInfo);
    }

}
