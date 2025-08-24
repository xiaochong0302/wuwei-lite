<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Thumb as ThumbService;
use App\Library\CsrfToken as CsrfTokenService;
use App\Repos\Upload as UploadRepo;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;

    /**
     * @Get("/language", name="admin.language")
     */
    public function languageAction()
    {
        $code = $this->request->getQuery('code', 'trim', 'en');

        $this->cookies->set('language', $code);

        $location = $this->request->getHTTPReferer();

        return $this->response->redirect($location);
    }

    /**
     * @Get("/download/{id}", name="home.download")
     */
    public function downloadAction($id)
    {
        $id = $this->crypt->decryptBase64($id, null, true);

        $repo = new UploadRepo();

        $file = $repo->findById($id);

        if ($file) {

            $location = sprintf('%s%s', kg_cos_url(), $file->path);

            return $this->response->redirect($location, true);

        } else {

            $this->response->setStatusCode(404);

            return $this->response;
        }
    }

    /**
     * @Get("/upload/img/{path:(.*)}", name="home.thumb")
     */
    public function thumbAction($path)
    {
        list($file, $style) = explode('!', $path);

        $file = sprintf('/upload/img/%s', $file);

        $service = new ThumbService();

        $thumb = $service->handle($file, $style);

        $location = kg_cos_img_url($thumb);

        return $this->response->redirect($location);
    }

    /**
     * @Post("/token/refresh", name="home.refresh_token")
     */
    public function refreshTokenAction()
    {
        $this->checkCsrfToken();

        $service = new CsrfTokenService();

        $token = $service->getToken();

        return $this->jsonSuccess(['token' => $token]);
    }

}
