<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class AppInfo
{

    /**
     * @var string
     */
    protected string $name = 'WUWEI LMS LITE';

    /**
     * @var string
     */
    protected string $alias = 'WUWEI-LITE';

    /**
     * @var string
     */
    protected string $link = 'https://www.koogua.net';

    /**
     * @var string
     */
    protected string $version = '1.0.0';

    public function __get($name): string|null
    {
        return $this->get($name);
    }

    public function get($name): string|null
    {
        return $this->{$name} ?? null;
    }

}
