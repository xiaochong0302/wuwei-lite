<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Services\Logic\Notice\Email\Test as MailTestService;

/**
 * @RoutePrefix("/admin/test")
 */
class TestController extends Controller
{

    /**
     * @Post("/mail", name="admin.test.mail")
     */
    public function mailAction()
    {
        $email = $this->request->getPost('email', 'string');

        $mailService = new MailTestService();

        $result = $mailService->handle($email);

        if ($result) {
            $msg = $this->locale->query('send_email_success');
            return $this->jsonSuccess(['msg' => $msg]);
        } else {
            $msg = $this->locale->query('send_email_failed');
            return $this->jsonError(['msg' => $msg]);
        }
    }

}
