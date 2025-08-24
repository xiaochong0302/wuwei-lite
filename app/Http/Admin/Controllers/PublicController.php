<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Library\AppInfo as AppInfo;
use App\Services\Utils\ApcuCache as ApcuCacheUtil;
use App\Services\Utils\OpCache as OpCacheUtil;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/admin")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use ClientTrait;

    public function initialize()
    {
        $jsLocale = $this->getJsLocale();

        $this->view->setVar('js_locale', $jsLocale);
    }

    /**
     * @Get("/language", name="admin.language")
     */
    public function languageAction()
    {
        $code = $this->request->getQuery('code', 'trim', 'en');

        $this->cookies->set('language', $code);

        return $this->response->redirect(['for' => 'admin.index']);
    }

    /**
     * @Get("/auth", name="admin.auth")
     */
    public function authAction()
    {
        $this->response->setStatusCode(401);

        if ($this->request->isAjax()) {

            $msg = $this->locale->query('error_401_tips');

            return $this->jsonError(['msg' => $msg]);
        }

        return $this->response->redirect(['for' => 'admin.login']);
    }

    /**
     * @Get("/forbidden", name="admin.forbidden")
     */
    public function forbiddenAction()
    {
        $this->response->setStatusCode(403);

        if ($this->request->isAjax()) {

            $msg = $this->locale->query('error_403_tips');

            return $this->jsonError(['msg' => $msg]);
        }
    }

    /**
     * @Post("/apcu/cache", name="admin.apcu_cache")
     */
    public function apcuCacheAction()
    {
        $auth = $this->request->getPost('auth');
        $scope = $this->request->getPost('scope');
        $value = $this->crypt->decrypt($auth);

        if ($scope == $value) {
            $util = new ApcuCacheUtil();
            $util->reset($scope);
        }
    }

    /**
     * @Post("/op/cache", name="admin.op_cache")
     */
    public function opCacheAction()
    {
        $auth = $this->request->getPost('auth');
        $scope = $this->request->getPost('scope');
        $value = $this->crypt->decrypt($auth);

        if ($scope == $value) {
            $util = new OpCacheUtil();
            $util->reset($scope);
        }
    }

    /**
     * @Get("/vod/player", name="admin.vod_player")
     */
    public function vodPlayerAction()
    {
        $playUrl = $this->request->getQuery('play_url', 'string');

        $this->view->pick('public/vod_player');
        $this->view->setVar('play_url', $playUrl);
    }

}
