<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class CommentLike extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 评论编号
     *
     * @var int
     */
    public int $comment_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $user_id = 0;

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

        $this->setSource('kg_comment_like');
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
