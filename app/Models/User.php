<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use App\Caches\MaxUserId as MaxUserIdCache;
use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Support\HelperFactory;

class User extends Model
{

    /**
     * 管理角色
     */
    const ADMIN_ROLE_ROOT = 1; // 超管

    /**
     * 教学角色
     */
    const EDU_ROLE_STUDENT = 1; // 学员
    const EDU_ROLE_TEACHER = 2; // 讲师

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
     * 头像
     *
     * @var string
     */
    public string $avatar = '';

    /**
     * 头衔
     *
     * @var string
     */
    public string $title = '';

    /**
     * 介绍
     *
     * @var string
     */
    public string $about = '';

    /**
     * 会员标识
     *
     * @var int
     */
    public int $vip = 0;

    /**
     * 锁定标识
     *
     * @var int
     */
    public int $locked = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 教学角色
     *
     * @var int
     */
    public int $edu_role = self::EDU_ROLE_STUDENT;

    /**
     * 后台角色
     *
     * @var int
     */
    public int $admin_role = 0;

    /**
     * 会员期限
     *
     * @var int
     */
    public int $vip_expiry_time = 0;

    /**
     * 锁定期限
     *
     * @var int
     */
    public int $lock_expiry_time = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public int $active_time = 0;

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

        $this->setSource('kg_user');

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

        if (empty($this->avatar)) {
            $this->avatar = kg_default_user_avatar_path();
        } elseif ($helper->startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }
    }

    public function afterCreate(): void
    {
        $cache = new MaxUserIdCache();

        $cache->rebuild();
    }

    public function afterFetch(): void
    {
        $helper = new HelperFactory();

        if (!$helper->startsWith($this->avatar, 'http')) {
            $this->avatar = kg_cos_user_avatar_url($this->avatar);
        }
    }

    public static function getAvatarPath($url): string
    {
        $helper = new HelperFactory();

        if ($helper->startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function eduRoleTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::EDU_ROLE_STUDENT => $locale->query('edu_role_student'),
            self::EDU_ROLE_TEACHER => $locale->query('edu_role_teacher'),
        ];
    }

}
