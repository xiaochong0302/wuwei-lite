<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Exceptions\BadRequest;
use App\Http\Admin\Services\Setting as SettingService;
use App\Library\Utils\Password as PasswordUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;
use App\Validators\Account as AccountValidator;

class MaintainTask extends Task
{

    /**
     * 重建首页缓存
     *
     * @param string $section
     * @command: php console.php maintain rebuild_index_page_cache {section}
     */
    public function rebuildIndexPageCacheAction(string $section = 'all'): void
    {
        $util = new IndexPageCacheUtil();

        $util->rebuild($section);

        $this->successPrint('rebuild index page cache ok');
    }

    /**
     * 修改邮箱
     *
     * @param string $oldEmail
     * @param string $newEmail
     * @command: php console.php maintain reset_email {old_email} {new_email}
     * @throws BadRequest
     */
    public function resetEmailAction($oldEmail, $newEmail): void
    {
        if (empty($oldEmail)) {
            $this->errorPrint('old email is required');
            return;
        }

        if (empty($newEmail)) {
            $this->errorPrint('new email is required');
            return;
        }

        if (!CommonValidator::email($oldEmail)) {
            $this->errorPrint('old email is invalid');
            return;
        }

        if (!CommonValidator::email($newEmail)) {
            $this->errorPrint('new email is invalid');
            return;
        }

        $validator = new AccountValidator();

        $account = $validator->checkAccount($oldEmail);

        if ($account->email != $newEmail) {
            $validator->checkIfEmailTaken($newEmail);
        }

        $account->email = $newEmail;

        $account->update();

        $this->successPrint('reset email ok');
    }

    /**
     * 修改密码
     *
     * @param string $email
     * @param string $password
     * @command: php console.php maintain reset_password {email} {password}
     * @throws BadRequest
     */
    public function resetPasswordAction(string $email, string $password): void
    {
        if (empty($email)) {
            $this->errorPrint('email is required');
            return;
        }

        if (empty($password)) {
            $this->errorPrint('password is required');
            return;
        }

        if (!CommonValidator::email($email)) {
            $this->errorPrint('email is invalid');
            return;
        }

        if (!CommonValidator::password($password)) {
            $this->errorPrint('password is invalid');
            return;
        }

        $validator = new AccountValidator();

        $account = $validator->checkAccount($email);

        $salt = PasswordUtil::salt();
        $hash = PasswordUtil::hash($password, $salt);

        $account->salt = $salt;
        $account->password = $hash;

        $account->update();

        $this->successPrint('reset password ok');
    }

    /**
     * 关闭站点
     *
     * @command: php console.php maintain disable_site
     */
    public function disableSiteAction(): void
    {
        $service = new SettingService();

        $service->updateSettings('site', ['status' => 'offline']);

        $this->successPrint('disable site ok');
    }

    /**
     * 开启站点
     *
     * @command: php console.php maintain enable_site
     */
    public function enableSiteAction(): void
    {
        $service = new SettingService();

        $service->updateSettings('site', ['status' => 'online']);

        $this->successPrint('enable site ok');
    }

}
