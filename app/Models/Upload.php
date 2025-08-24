<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Upload extends Model
{

    /**
     * 资源类型
     */
    const TYPE_COVER_IMG = 1; // 封面图
    const TYPE_CONTENT_IMG = 2; // 内容图
    const TYPE_AVATAR_IMG = 3; // 头像
    const TYPE_ICON_IMG = 4; // 图标
    const TYPE_RESOURCE = 5; // 课件资源
    const TYPE_MEDIA = 6; // 媒体文件

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 路径
     *
     * @var string
     */
    public string $path = '';

    /**
     * mime
     *
     * @var string
     */
    public string $mime = '';

    /**
     * md5
     *
     * @var string
     */
    public string $md5 = '';

    /**
     * 大小（字节）
     *
     * @var int
     */
    public int $size = 0;

    /**
     * 类型
     *
     * @var int
     */
    public int $type = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

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

        $this->setSource('kg_upload');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
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
