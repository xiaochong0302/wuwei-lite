<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Library\Http\Request as HttpRequest;
use App\Library\Http\Response as HttpResponse;
use App\Plugins\LocaleError as LocaleErrorPlugin;
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Paginator\RepositoryInterface;

trait Response
{

    protected function forbidden(): void
    {
        throw new ForbiddenException('sys.forbidden');
    }

    protected function notFound(): void
    {
        throw new NotFoundException('sys.not_found');
    }

    protected function setCors(): HttpResponse
    {
        /**
         * @var HttpRequest $request
         */
        $request = Di::getDefault()->getShared('request');

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $cors = $config->get('cors')->toArray();

        if (!$cors['enabled']) return $response;

        if (is_array($cors['allow_headers'])) {
            $cors['allow_headers'] = implode(',', $cors['allow_headers']);
        }

        if (is_array($cors['allow_methods'])) {
            $cors['allow_methods'] = implode(',', $cors['allow_methods']);
        }

        $origin = $request->getHeader('Origin');

        if (is_array($cors['allow_origin']) && in_array($origin, $cors['allow_origin'])) {
            $cors['allow_origin'] = $origin;
        }

        $response->setHeader('Access-Control-Allow-Origin', $cors['allow_origin']);

        if ($request->isOptions()) {
            $response->setHeader('Access-Control-Allow-Headers', $cors['allow_headers']);
            $response->setHeader('Access-Control-Allow-Methods', $cors['allow_methods']);
        }

        return $response;
    }

    protected function jsonSuccess(array $content = []): HttpResponse
    {
        $content['code'] = 0;

        $content['msg'] = $content['msg'] ?? '';

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        $response->setStatusCode(200);

        $response->setJsonContent($content);

        return $response;
    }

    protected function jsonError(array $content = []): HttpResponse
    {
        $content['code'] = $content['code'] ?? 1;

        $content['msg'] = $content['msg'] ?? $this->getErrorMessage($content['code']);

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        $response->setJsonContent($content);

        return $response;
    }

    protected function jsonPaginate(RepositoryInterface $paginate): HttpResponse
    {
        $pager = [
            'items' => $paginate->getItems(),
            'total_items' => $paginate->getTotalItems(),
        ];

        return $this->jsonSuccess(['pager' => $pager]);
    }

    protected function getErrorMessage(string $code): string
    {
        $locale = new LocaleErrorPlugin();

        $translator = $locale->getTranslator();

        return $translator->query($code) ?: $code;
    }

}
