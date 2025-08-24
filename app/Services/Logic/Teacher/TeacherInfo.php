<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Teacher;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\UserInfo as UserInfoService;

class TeacherInfo extends LogicService
{

    public function handle(int $id): array
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }

}
