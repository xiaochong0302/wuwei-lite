<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Page as PageModel;
use App\Validators\Page as PageValidator;

trait PageTrait
{

    protected function checkPage(int $id): PageModel
    {
        $validator = new PageValidator();

        return $validator->checkPage($id);
    }

}
