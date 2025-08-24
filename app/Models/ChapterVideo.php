<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class ChapterVideo extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 章节编号
     *
     * @var int
     */
    public int $chapter_id = 0;

    /**
     * 媒体编号
     *
     * @var int
     */
    public int $media_id = 0;

    /**
     * 点播设置
     *
     * @var array|string
     */
    public string|array $settings = [];

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

        $this->setSource('kg_chapter_video');
    }


    public function beforeCreate(): void
    {
        if (is_array($this->settings)) {
            $this->settings = kg_json_encode($this->settings);
        }

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        if (is_array($this->settings)) {
            $this->settings = kg_json_encode($this->settings);
        }

        $this->update_time = time();
    }

    public function afterFetch(): void
    {
        if (is_string($this->settings)) {
            $this->settings = json_decode($this->settings, true);
        }
    }

}
