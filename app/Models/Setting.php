<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class Setting extends Model
{

    /**
     * 主键
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 配置块
     *
     * @var string
     */
    public string $section = '';

    /**
     * 配置键
     *
     * @var string
     */
    public string $item_key = '';

    /**
     * 配置值
     *
     * @var string
     */
    public string $item_value = '';

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_setting');
    }

}
