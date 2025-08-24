<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class LayerController extends \Phalcon\Mvc\Controller
{

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
     * @var UserModel|null
     */
    protected ?UserModel $authUser;

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkRateLimit();

        return true;
    }

    public function initialize()
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->contactInfo = $this->getContactInfo();
        $this->authUser = $this->getAuthUser();
        $this->jsLocale = $this->getJsLocale();

        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('contact_info', $this->contactInfo);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('js_locale', $this->jsLocale);
    }

    protected function getSiteInfo(): array
    {
        $appService = new AppService();

        return $appService->getSettings('site');
    }

    protected function getContactInfo(): array
    {
        $appService = new AppService();

        return $appService->getSettings('contact');
    }

    protected function getAuthUser(): ?UserModel
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
