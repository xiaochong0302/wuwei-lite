<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Index as IndexService;
use App\Http\Admin\Services\Session as SessionService;
use App\Traits\Auth as AuthTrait;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;

/**
 * @RoutePrefix("/admin")
 */
class SessionController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;
    use AuthTrait;

    public function initialize()
    {
        $jsLocale = $this->getJsLocale();

        $this->view->setVar('js_locale', $jsLocale);
    }

    /**
     * @Route("/login", name="admin.login")
     */
    public function loginAction()
    {
        $user = $this->getCurrentUser();

        if ($user->id > 0) {
            return $this->response->redirect(['for' => 'admin.index']);
        }

        if ($this->request->isPost()) {

            $this->checkHttpReferer();
            $this->checkCsrfToken();

            $sessionService = new SessionService();

            $sessionService->login();

            $location = $this->url->get(['for' => 'admin.index']);

            return $this->jsonSuccess(['location' => $location]);
        }

        $indexService = new IndexService();

        $appInfo = $indexService->getAppInfo();

        $this->view->pick('public/login');

        $this->view->setVar('app_info', $appInfo);
    }

    /**
     * @Get("/logout", name="admin.logout")
     */
    public function logoutAction()
    {
        $sessionService = new SessionService();

        $sessionService->logout();

        return $this->response->redirect(['for' => 'admin.login']);
    }

}
