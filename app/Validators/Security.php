<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Library\CsrfToken as CsrfTokenService;

class Security extends Validator
{

    public function checkClientAddress(): void
    {
        $address = $this->request->getClientAddress();

        $settings = $this->getSettings('security.blacklist');

        if ($settings['enabled'] == 0) return;

        $settings['content'] = str_replace('，', ',', $settings['content']);

        $blacklist = explode(',', $settings['content']);

        if (in_array($address, $blacklist)) {
            throw new ForbiddenException('security.client_address_blocked');
        }
    }

    public function checkCsrfToken(): bool
    {
        $route = $this->router->getMatchedRoute();
        $headerToken = $this->request->getHeader('X-Csrf-Token');
        $postToken = $this->request->getPost('csrf_token');

        if (in_array($route->getName(), $this->getCsrfWhitelist())) {
            return true;
        }

        $service = new CsrfTokenService();

        $result = false;

        if ($headerToken) {
            $result = $service->checkToken($headerToken);
        } elseif ($postToken) {
            $result = $service->checkToken($postToken);
        }

        if (!$result) {
            throw new BadRequestException('security.invalid_csrf_token');
        }

        return true;
    }

    public function checkHttpReferer(): void
    {
        $refererHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $httpHost = preg_replace('/:\d+/', '', $this->request->getHttpHost());

        if ($refererHost != $httpHost) {
            throw new BadRequestException('security.invalid_http_referer');
        }
    }

    protected function getCsrfWhitelist(): array
    {
        return [
            'admin.upload.chunk',
            'admin.upload.merge_media_chunks',
            'admin.upload.merge_resource_chunks',
        ];
    }

}
