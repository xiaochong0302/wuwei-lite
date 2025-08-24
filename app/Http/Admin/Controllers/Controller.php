<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Models\Audit as AuditModel;
use App\Models\User as UserModel;
use App\Services\Auth\Admin as AdminAuth;
use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var array
     */
    protected array $languages;

    /**
     * @var array
     */
    protected array $jsLocale;

    /**
     * @var array
     */
    protected array $siteInfo;

    /**
     * @var array
     */
    protected array $authInfo;

    /**
     * @var UserModel|null
     */
    protected ?UserModel $authUser;

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;

    public function initialize()
    {
        $this->languages = $this->getLanguages();
        $this->jsLocale = $this->getJsLocale();

        $this->view->setVar('languages', $this->languages);
        $this->view->setVar('js_locale', $this->jsLocale);
        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('auth_user', $this->authUser);
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->siteInfo = $this->getSiteInfo();
        $this->authInfo = $this->getAuthInfo();

        if (!$this->authInfo) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'auth',
            ]);
            return false;
        }

        $this->authUser = $this->getAuthUser();

        /**
         * root用户忽略权限检查
         */
        if ($this->authUser->admin_role == UserModel::ADMIN_ROLE_ROOT) {
            return true;
        }

        /**
         * 特例白名单
         */
        $whitelist = [
            'controllers' => ['public', 'index', 'upload', 'test'],
            'routes' => [],
        ];

        $controller = $dispatcher->getControllerName();

        /**
         * 特定控制器忽略权限检查
         */
        if (in_array($controller, $whitelist['controllers'])) {
            return true;
        }

        $route = $this->router->getMatchedRoute();

        /**
         * 特定路由忽略权限检查
         */
        if (in_array($route->getName(), $whitelist['routes'])) {
            return true;
        }

        /**
         * 执行路由权限检查
         */
        if (!in_array($route->getName(), $this->authInfo['routes'])) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'forbidden',
            ]);
            return false;
        }

        return true;
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isPost()) {

            $audit = new AuditModel();

            $audit->user_id = $this->authUser->id;
            $audit->user_name = $this->authUser->name;
            $audit->user_ip = $this->request->getClientAddress();
            $audit->req_route = $this->router->getMatchedRoute()->getName();
            $audit->req_path = $this->request->getServer('REQUEST_URI');
            $audit->req_data = $this->request->getPost();

            $audit->create();
        }
    }

    protected function getSiteInfo(): array
    {
        return $this->getSettings('site');
    }

    protected function getAuthInfo(): array
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

    protected function getSettings(string $section): array
    {
        $appService = new AppService();

        return $appService->getSettings($section);
    }

    protected function getAuthUser(): ?UserModel
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
