<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class ThumbLink extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 源路crc32
     *
     * @var int
     */
    public int $source_crc32 = 0;

    /**
     * 标路crc32
     *
     * @var int
     */
    public int $target_crc32 = 0;

    /**
     * 源头路径
     *
     * @var string
     */
    public string $source_path = '';

    /**
     * 目标路径
     *
     * @var string
     */
    public string $target_path = '';

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_thumb_link');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

}
