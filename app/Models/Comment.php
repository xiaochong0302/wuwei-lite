<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Comment extends Model
{

    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 内容
     *
     * @var string
     */
    public string $content = '';

    /**
     * 父级编号
     *
     * @var int
     */
    public int $parent_id = 0;

    /**
     * 章节编号
     *
     * @var int
     */
    public int $chapter_id = 0;

    /**
     * 作者编号
     *
     * @var int
     */
    public int $owner_id = 0;

    /**
     * 目标用户
     *
     * @var int
     */
    public int $to_user_id = 0;

    /**
     * 终端类型
     *
     * @var int
     */
    public int $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public string $client_ip = '';

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = self::PUBLISH_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 回复数
     *
     * @var int
     */
    public int $reply_count = 0;

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

        $this->setSource('kg_comment');

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

    public static function publishTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::PUBLISH_PENDING => $locale->query('status_pending'),
            self::PUBLISH_APPROVED => $locale->query('status_approved'),
            self::PUBLISH_REJECTED => $locale->query('status_rejected'),
        ];
    }

}
