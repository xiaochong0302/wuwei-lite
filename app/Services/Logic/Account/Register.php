<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class Register extends LogicService
{

    public function handle():AccountModel
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $accountValidator->checkRegisterStatus();

        $data = [];

        $data['email'] = $accountValidator->checkEmail($post['email']);

        $accountValidator->checkIfEmailTaken($post['email']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkEmailCode($post['email'], $post['verify_code']);

        $password = $accountValidator->checkPassword($post['password']);

        $salt = PasswordUtil::salt();

        $data['password'] = PasswordUtil::hash($password, $salt);

        $data['salt'] = $salt;

        try {

            $this->db->begin();

            $account = new AccountModel();

            $account->assign($data);

            if ($account->create() === false) {
                throw new \RuntimeException('Create Account Failed');
            }

            $this->db->commit();

            return $account;

        } catch (\Exception $e) {

            $this->db->rollback();

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
