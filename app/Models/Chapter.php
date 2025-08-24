<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use App\Caches\MaxChapterId as MaxChapterIdCache;
use App\Services\Sync\ChapterIndex as ChapterIndexSync;
use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Chapter extends Model
{

    /**
     * 模型类别
     */
    const MODEL_VIDEO = 1; // 视频
    const MODEL_LIVE = 2; // 直播
    const MODEL_ARTICLE = 3; // 图文

    /**
     * @var array
     *
     * 点播扩展属性
     */
    protected array $_video_attrs = [
        'duration' => 0,
    ];

    /**
     * @var array
     *
     * 图文扩展属性
     */
    protected array $_article_attrs = [
        'duration' => 0,
        'word_count' => 0,
    ];

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 父级编号
     *
     * @var int
     */
    public int $parent_id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * slug
     *
     * @var string
     */
    public string $slug = '';

    /**
     * 摘要
     *
     * @var string
     */
    public string $summary = '';

    /**
     * 关键字
     *
     * @var string
     */
    public string $keywords = '';

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 100;

    /**
     * 模式类型
     *
     * @var int
     */
    public int $model = 0;

    /**
     * 免费标识
     *
     * @var int
     */
    public int $free = 0;

    /**
     * 扩展属性
     *
     * @var array|string
     */
    public string|array $attrs = [];

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
     * 开启评论
     *
     * @var int
     */
    public int $comment_enabled = 0;

    /**
     * 课时数
     *
     * @var int
     */
    public int $lesson_count = 0;

    /**
     * 学员数
     *
     * @var int
     */
    public int $user_count = 0;

    /**
     * 评论数
     *
     * @var int
     */
    public int $comment_count = 0;

    /**
     * 点赞数
     *
     * @var int
     */
    public int $like_count = 0;

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

        $this->setSource('kg_chapter');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeSave(): void
    {
        $this->slug = kg_parse_slug($this->title);
    }

    public function beforeCreate(): void
    {
        if ($this->parent_id > 0) {
            if (empty($this->attrs)) {
                if ($this->model == self::MODEL_VIDEO) {
                    $this->attrs = $this->_video_attrs;
                } elseif ($this->model == self::MODEL_ARTICLE) {
                    $this->attrs = $this->_article_attrs;
                }
            }
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        if (time() - $this->update_time > 3 * 3600) {
            $sync = new ChapterIndexSync();
            $sync->addItem($this->id);
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->update_time = time();
    }

    public function afterCreate(): void
    {
        $cache = new MaxChapterIdCache();

        $cache->rebuild();

        if ($this->parent_id > 0) {

            $data = [
                'course_id' => $this->course_id,
                'chapter_id' => $this->id,
            ];

            $extend = false;

            switch ($this->model) {
                case self::MODEL_VIDEO:
                    $video = new ChapterVideo();
                    $video->assign($data);
                    $extend = $video->create();
                    break;
                case self::MODEL_ARTICLE:
                    $article = new ChapterArticle();
                    $article->assign($data);
                    $extend = $article->create();
                    break;
            }

            if ($extend === false) {
                throw new \RuntimeException("Create Chapter Extend Failed");
            }
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public static function modelTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::MODEL_VIDEO => $locale->query('chapter_model_video'),
            self::MODEL_ARTICLE => $locale->query('chapter_model_article'),
        ];
    }

}
