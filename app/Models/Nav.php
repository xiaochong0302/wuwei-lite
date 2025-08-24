<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Nav extends Model
{

    /**
     * 位置类型
     */
    const POS_TOP = 1; // 顶部
    const POS_BOTTOM = 2; // 底部

    /**
     * 打开方式
     */
    const TARGET_BLANK = '_blank'; // 新建窗口
    const TARGET_SELF = '_self'; // 当前窗口

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 上级编号
     *
     * @var int
     */
    public int $parent_id = 0;

    /**
     * 层级
     *
     * @var int
     */
    public int $level = 0;

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
     * 打开方式
     *
     * @var string
     */
    public string $target = '';

    /**
     * 链接地址
     *
     * @var string
     */
    public string $url = '';

    /**
     * 位置
     *
     * @var int
     */
    public int $position = 1;

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 99;

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 1;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 节点数
     *
     * @var int
     */
    public int $child_count = 0;

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

        $this->setSource('kg_nav');

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

    public static function posTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::POS_TOP => $locale->query('nav_pos_top'),
            self::POS_BOTTOM => $locale->query('nav_pos_bottom'),
        ];
    }

    public static function targetTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::TARGET_BLANK => $locale->query('nav_target_blank'),
            self::TARGET_SELF => $locale->query('nav_target_self'),
        ];
    }

}
