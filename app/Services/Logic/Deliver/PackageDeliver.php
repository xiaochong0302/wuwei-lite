<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Deliver;

use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;

class PackageDeliver extends LogicService
{

    public function handle(PackageModel $package, UserModel $user): void
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        foreach ($courses as $course) {
            $deliver = new CourseDeliver();
            $deliver->handle($course, $user);
        }
    }

}
