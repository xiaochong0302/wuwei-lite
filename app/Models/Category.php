<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Support\HelperFactory;

class Category extends Model
{

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
     * slug
     *
     * @var string
     */
    public string $slug = '';

    /**
     * 路径
     *
     * @var string
     */
    public string $path = '';

    /**
     * 图标
     *
     * @var string
     */
    public string $icon = '';

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 10;

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

        $this->setSource('kg_category');

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

    public function beforeSave()
    {
        $helper = new HelperFactory();

        $this->slug = kg_parse_slug($this->name);

        if (empty($this->icon)) {
            $this->icon = kg_default_category_icon_path();
        } elseif ($helper->startsWith($this->icon, 'http')) {
            $this->icon = self::getIconPath($this->icon);
        }
    }

    public function afterCreate(): void
    {
        $cache = new MaxCategoryIdCache();

        $cache->rebuild();
    }

    public static function getIconPath($url)
    {
        $helper = new HelperFactory();

        if ($helper->startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

}
