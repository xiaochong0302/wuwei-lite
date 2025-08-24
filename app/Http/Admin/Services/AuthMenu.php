<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Services\Auth\Admin as AdminAuth;
use Phalcon\Di\Injectable;

class AuthMenu extends Injectable
{

    /**
     * @var array
     */
    protected array $authInfo;

    /**
     * @var array
     */
    protected array $authNodes = [];

    /**
     * @var array
     */
    protected array $owned1stLevelIds = [];

    /**
     * @var array
     */
    protected array $owned2ndLevelIds = [];

    /**
     * @var array
     */
    protected array $owned3rdLevelIds = [];

    public function __construct()
    {
        $this->authInfo = $this->getAuthInfo();
        $this->authNodes = $this->getAuthNodes();
        $this->setOwnedLevelIds();
    }

    public function getTopMenus(): array
    {
        $menus = [];

        foreach ($this->authNodes as $node) {
            if (($this->authInfo['root'] == 1) || in_array($node['id'], $this->owned1stLevelIds)) {
                $menus[] = [
                    'id' => $node['id'],
                    'title' => $node['title'],
                ];
            }
        }

        return $menus;
    }

    public function getLeftMenus(): array
    {
        $menus = [];

        foreach ($this->authNodes as $key => $level) {
            foreach ($level['children'] as $key2 => $level2) {
                foreach ($level2['children'] as $key3 => $level3) {
                    $allowed = ($this->authInfo['root'] == 1) || in_array($level3['id'], $this->owned3rdLevelIds);
                    $params = $level3['params'] ?? [];
                    if ($level3['type'] == 'menu' && $allowed) {
                        $menus[$key]['id'] = $level['id'];
                        $menus[$key]['title'] = $level['title'];
                        $menus[$key]['children'][$key2]['id'] = $level2['id'];
                        $menus[$key]['children'][$key2]['title'] = $level2['title'];
                        $menus[$key]['children'][$key2]['children'][$key3] = [
                            'id' => $level3['id'],
                            'title' => $level3['title'],
                            'url' => $this->url->get(['for' => $level3['route']], $params),
                        ];
                    }
                }
            }
        }

        return $menus;
    }

    protected function setOwnedLevelIds(): void
    {
        $routeIdMapping = $this->getRouteIdMapping();

        if (!$routeIdMapping) return;

        $owned1stLevelIds = [];
        $owned2ndLevelIds = [];
        $owned3rdLevelIds = [];

        foreach ($routeIdMapping as $key => $value) {
            $ids = explode('-', $value);
            if (is_array($this->authInfo['routes']) && in_array($key, $this->authInfo['routes'])) {
                $owned1stLevelIds[] = $ids[0];
                $owned2ndLevelIds[] = $ids[0] . '-' . $ids[1];
                $owned3rdLevelIds[] = $value;
            }
        }

        $this->owned1stLevelIds = array_unique($owned1stLevelIds);
        $this->owned2ndLevelIds = array_unique($owned2ndLevelIds);
        $this->owned3rdLevelIds = array_unique($owned3rdLevelIds);
    }

    protected function getRouteIdMapping(): array
    {
        $mapping = [];

        foreach ($this->authNodes as $level) {
            foreach ($level['children'] as $level2) {
                foreach ($level2['children'] as $level3) {
                    if ($level3['type'] == 'menu') {
                        $mapping[$level3['route']] = $level3['id'];
                    }
                }
            }
        }

        return $mapping;
    }

    protected function getAuthNodes(): array
    {
        $authNode = new AuthNode();

        return $authNode->getNodes();
    }

    protected function getAuthInfo(): array
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
