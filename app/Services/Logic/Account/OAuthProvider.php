<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Account;

use App\Services\Logic\Service as LogicService;

class OAuthProvider extends LogicService
{

    public function handle(): array
    {
        $google = $this->getSettings('oauth.google');
        $github = $this->getSettings('oauth.github');
        $facebook = $this->getSettings('oauth.facebook');

        return [
            'google' => ['enabled' => $google['enabled']],
            'github' => ['enabled' => $github['enabled']],
            'facebook' => ['enabled' => $facebook['enabled']],
        ];
    }

}
