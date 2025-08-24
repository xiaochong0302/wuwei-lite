<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\StudyCourseList as UserCourseListService;

class StudyCourseList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserCourseListService();

        return $service->handle($user->id);
    }

}
