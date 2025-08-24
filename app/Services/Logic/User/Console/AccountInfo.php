<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Logic\Service as LogicService;

class AccountInfo extends LogicService
{

    public function handle(): array
    {
        $user = $this->getLoginUser();

        return $this->handleAccount($user);
    }

    protected function handleAccount(UserModel $user): array
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        return [
            'id' => $account->id,
            'name' => $account->name,
            'email' => $account->email,
        ];
    }

}
