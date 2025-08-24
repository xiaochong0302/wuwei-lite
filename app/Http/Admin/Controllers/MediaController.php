<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Media as MediaService;

/**
 * @RoutePrefix("/admin/media")
 */
class MediaController extends Controller
{

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.media.delete")
     */
    public function deleteAction($id)
    {
        $service = new MediaService();

        $service->delete($id);

        $content = [
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
