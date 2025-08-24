<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Account as AccountService;
use App\Services\Logic\Account\EmailUpdate as EmailUpdateService;
use App\Services\Logic\Account\OAuthProvider as OAuthProviderService;
use App\Services\Logic\Account\PasswordReset as PasswordResetService;
use App\Services\Logic\Account\PasswordUpdate as PasswordUpdateService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Traits\Client as ClientTrait;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/register", name="home.account.register")
     */
    public function registerAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getAccountRegisterUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $returnUrl = $this->request->getHTTPReferer();

        $title = $this->locale->query('register_account');

        $this->seo->prependTitle($title);

        $this->view->setVar('return_url', $returnUrl);
    }

    /**
     * @Get("/login", name="home.account.login")
     */
    public function loginAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getAccountLoginUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $service = new OAuthProviderService();

        $oauthProvider = $service->handle();

        $returnUrl = $this->request->getHTTPReferer();

        $title = $this->locale->query('login_account');

        $this->seo->prependTitle($title);

        $this->view->setVar('oauth_provider', $oauthProvider);
        $this->view->setVar('return_url', $returnUrl);
    }

    /**
     * @Get("/logout", name="home.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        return $this->response->redirect(['for' => 'home.index']);
    }

    /**
     * @Get("/forget", name="home.account.forget")
     */
    public function forgetAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getAccountForgetUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $title = $this->locale->query('forget_pwd');

        $this->seo->prependTitle($title);
    }

    /**
     * @Post("/register", name="home.account.do_register")
     */
    public function doRegisterAction()
    {
        $service = new AccountService();

        $service->register();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $msg = $this->locale->query('registered_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/login", name="home.account.do_login")
     */
    public function doLoginAction()
    {
        $service = new AccountService();

        $service->login();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $msg = $this->locale->query('logged_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/reset", name="home.account.reset_pwd")
     */
    public function resetPasswordAction()
    {
        $service = new PasswordResetService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'home.account.login']),
            'msg' => $this->locale->query('pwd_reset_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/email/update", name="home.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'home.uc.account']),
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/update", name="home.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'home.uc.account']),
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
