<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Support\HelperFactory;

class Slide extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public string $cover = '';

    /**
     * 链接
     *
     * @var string
     */
    public string $link = '';

    /**
     * 摘要
     *
     * @var string
     */
    public string $summary = '';

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
    public int $published = 0;

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

        $this->setSource('kg_slide');

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

    public function beforeSave(): void
    {
        $helper = new HelperFactory();

        if (empty($this->cover)) {
            $this->cover = kg_default_slide_cover_path();
        } elseif ($helper->startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }
    }

    public function afterFetch(): void
    {
        $helper = new HelperFactory();

        if (!$helper->startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_slide_cover_url($this->cover);
        }
    }

    public static function getCoverPath($url): string
    {
        $helper = new HelperFactory();

        if ($helper->startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

}
