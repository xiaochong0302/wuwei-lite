<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Util as UtilService;

/**
 * @RoutePrefix("/admin/util")
 */
class UtilController extends Controller
{

    /**
     * @Route("/cache", name="admin.util.cache")
     */
    public function cacheAction()
    {
        $service = new UtilService();

        if ($this->request->isPost()) {

            $service->handleCache();

            $content = [
                'msg' => $this->locale->query('flushed_ok'),
            ];

            return $this->jsonSuccess($content);
        }
    }

}
