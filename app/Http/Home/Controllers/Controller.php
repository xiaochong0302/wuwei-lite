<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Caches\NavTreeList as NavCache;
use App\Library\AppInfo as AppInfo;
use App\Library\Seo as Seo;
use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Config\Config;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var array
     */
    protected array $navs;

    /**
     * @var array
     */
    protected array $languages;

    /**
     * @var array
     */
    protected array $siteInfo;

    /**
     * @var array
     */
    protected array $contactInfo;

    /**
     * @var array
     */
    protected array $jsLocale;

    /**
     * @var Seo
     */
    protected Seo $seo;

    /**
     * @var AppInfo
     */
    protected AppInfo $appInfo;

    /**
     * @var UserModel|null
     */
    protected ?UserModel $authUser;

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;

    public function initialize()
    {
        $this->eventsManager->fire('Site:afterView', $this, $this->authUser);

        $this->seo = $this->getSeo();
        $this->navs = $this->getNavs();
        $this->languages = $this->getLanguages();
        $this->appInfo = $this->getAppInfo();
        $this->contactInfo = $this->getContactInfo();
        $this->jsLocale = $this->getJsLocale();

        $this->seo->setTitle($this->siteInfo['title']);

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('navs', $this->navs);
        $this->view->setVar('languages', $this->languages);
        $this->view->setVar('app_info', $this->appInfo);
        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('contact_info', $this->contactInfo);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('js_locale', $this->jsLocale);
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->authUser = $this->getAuthUser();

        if ($this->siteInfo['status'] == 'offline') {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'maintain',
            ]);
            return false;
        }

        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        return true;
    }

    protected function getNavs(): array
    {
        $cache = new NavCache();

        return $cache->get();
    }

    protected function getSiteInfo(): array
    {
        return $this->getSettings('site');
    }

    protected function getContactInfo(): array
    {
        return $this->getSettings('contact');
    }

    protected function getSettings(string $section): array
    {
        $appService = new AppService();

        return $appService->getSettings($section);
    }

    protected function getAuthUser(bool $cache = true): ?UserModel
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser($cache);
    }

    protected function getSeo(): Seo
    {
        return new Seo();
    }

    protected function getAppInfo(): AppInfo
    {
        return new AppInfo();
    }

    protected function getConfig(): Config
    {
        $appService = new AppService();

        return $appService->getConfig();
    }

}
