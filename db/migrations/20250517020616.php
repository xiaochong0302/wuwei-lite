<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use Phinx\Db\Adapter\MysqlAdapter;

class V20250517020616 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->createAccountTable();
        $this->createAuditTable();
        $this->createCategoryTable();
        $this->createChapterTable();
        $this->createChapterLikeTable();
        $this->createChapterArticleTable();
        $this->createChapterUserTable();
        $this->createChapterVideoTable();
        $this->createCommentTable();
        $this->createCommentLikeTable();
        $this->createCourseTable();
        $this->createCourseFavoriteTable();
        $this->createCoursePackageTable();
        $this->createCourseRelatedTable();
        $this->createCourseUserTable();
        $this->createLearningTable();
        $this->createMediaTable();
        $this->createMigrationPhalconTable();
        $this->createNavTable();
        $this->createOrderTable();
        $this->createOrderStatusTable();
        $this->createPackageTable();
        $this->createPageTable();
        $this->createRefundTable();
        $this->createRefundStatusTable();
        $this->createRoleTable();
        $this->createResourceTable();
        $this->createReviewTable();
        $this->createReviewLikeTable();
        $this->createSettingTable();
        $this->createSlideTable();
        $this->createTaskTable();
        $this->createThumbLinkTable();
        $this->createUploadTable();
        $this->createUserTable();
        $this->createVipTable();
    }

    protected function createAccountTable()
    {
        $tableName = 'kg_account';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '邮箱',
                'after' => 'id',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '密码',
                'after' => 'email',
            ])
            ->addColumn('salt', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '密盐',
                'after' => 'password',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'salt',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['email'], [
                'name' => 'email',
                'unique' => false,
            ])
            ->create();
    }

    protected function createAuditTable()
    {
        $tableName = 'kg_audit';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('user_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名称',
                'after' => 'user_id',
            ])
            ->addColumn('user_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户IP',
                'after' => 'user_name',
            ])
            ->addColumn('req_route', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求路由',
                'after' => 'user_ip',
            ])
            ->addColumn('req_path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求路径',
                'after' => 'req_route',
            ])
            ->addColumn('req_data', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求数据',
                'after' => 'req_path',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'req_data',
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCategoryTable()
    {
        $tableName = 'kg_category';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '层级',
                'after' => 'parent_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'level',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'slug',
                'after' => 'name',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'slug',
            ])
            ->addColumn('icon', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '图标',
                'after' => 'path',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => '10',
                'signed' => false,
                'comment' => '优先级',
                'after' => 'icon',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'priority',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('child_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '节点数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'child_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createChapterTable()
    {
        $tableName = 'kg_chapter';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'parent_id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'course_id',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'slug',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'slug',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'summary',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => '10',
                'signed' => false,
                'comment' => '优先级',
                'after' => 'keywords',
            ])
            ->addColumn('model', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '模式类型',
                'after' => 'priority',
            ])
            ->addColumn('attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '扩展属性',
                'after' => 'model',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'attrs',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('comment_enabled', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '开启评论',
                'after' => 'deleted',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课时数',
                'after' => 'comment_enabled',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学员数',
                'after' => 'lesson_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '评论数',
                'after' => 'user_count',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'comment_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'like_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterLikeTable()
    {
        $tableName = 'kg_chapter_like';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterArticleTable()
    {
        $tableName = 'kg_chapter_article';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'chapter_id',
            ])
            ->addColumn('settings', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '点播设置',
                'after' => 'content',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'settings',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterUserTable()
    {
        $tableName = 'kg_chapter_user';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'chapter_id',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '播放位置',
                'after' => 'duration',
            ])
            ->addColumn('progress', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习进度',
                'after' => 'position',
            ])
            ->addColumn('consumed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '消费标识',
                'after' => 'progress',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'consumed',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterVideoTable()
    {
        $tableName = 'kg_chapter_video';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('media_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '媒体编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('settings', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '点播设置',
                'after' => 'media_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'settings',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['media_id'], [
                'name' => 'media_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCommentTable()
    {
        $tableName = 'kg_comment';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'id',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'content',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'parent_id',
            ])
            ->addColumn('to_user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '回复用户',
                'after' => 'owner_id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'to_user_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'chapter_id',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'client_ip',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('reply_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '回复数',
                'after' => 'deleted',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'reply_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'like_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCommentLikeTable()
    {
        $tableName = 'kg_comment_like';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('comment_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '评论编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'comment_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['comment_id'], [
                'name' => 'comment_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseTable()
    {
        $tableName = 'kg_course';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'slug',
                'after' => 'title',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'slug',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'summary',
            ])
            ->addColumn('details', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '详情',
                'after' => 'keywords',
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'details',
            ])
            ->addColumn('teacher_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '讲师编号',
                'after' => 'category_id',
            ])
            ->addColumn('regular_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '常规价格',
                'after' => 'teacher_id',
            ])
            ->addColumn('vip_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '会员价格',
                'after' => 'regular_price',
            ])
            ->addColumn('study_expiry', 'integer', [
                'null' => false,
                'default' => '12',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习期限（月）',
                'after' => 'vip_price',
            ])
            ->addColumn('refund_expiry', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '退款期限（月）',
                'after' => 'study_expiry',
            ])
            ->addColumn('rating', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '用户评分',
                'after' => 'refund_expiry',
            ])
            ->addColumn('score', 'float', [
                'null' => false,
                'default' => '0.0000',
                'comment' => '综合得分',
                'after' => 'rating',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '难度级别',
                'after' => 'score',
            ])
            ->addColumn('featured', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '推荐标识',
                'after' => 'level',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'featured',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('human_verify_enabled', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '开启真人验证',
                'after' => 'deleted',
            ])
            ->addColumn('review_enabled', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '开启评价',
                'after' => 'human_verify_enabled',
            ])
            ->addColumn('comment_enabled', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '开启评论',
                'after' => 'review_enabled',
            ])
            ->addColumn('package_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '套餐数',
                'after' => 'comment_enabled',
            ])
            ->addColumn('resource_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '资料数',
                'after' => 'package_count',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学员数',
                'after' => 'resource_count',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课时数',
                'after' => 'user_count',
            ])
            ->addColumn('review_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '评价数',
                'after' => 'lesson_count',
            ])
            ->addColumn('favorite_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '收藏数',
                'after' => 'review_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'favorite_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ])
            ->addIndex(['teacher_id'], [
                'name' => 'teacher_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseFavoriteTable()
    {
        $tableName = 'kg_course_favorite';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCoursePackageTable()
    {
        $tableName = 'kg_course_package';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('package_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '套餐编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'package_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['package_id'], [
                'name' => 'package_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseRelatedTable()
    {
        $tableName = 'kg_course_related';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('related_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '相关编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'related_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseUserTable()
    {
        $tableName = 'kg_course_user';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('join_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '加入类型',
                'after' => 'user_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'join_type',
            ])
            ->addColumn('progress', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习进度',
                'after' => 'duration',
            ])
            ->addColumn('reviewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '评价标识',
                'after' => 'progress',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'reviewed',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'deleted',
            ])
            ->addColumn('expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'active_time',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'expiry_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createLearningTable()
    {
        $tableName = 'kg_learning';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('request_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求编号',
                'after' => 'id',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'request_id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课时编号',
                'after' => 'course_id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'user_id',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '播放位置',
                'after' => 'duration',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'position',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'client_ip',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['request_id'], [
                'name' => 'request_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createMediaTable()
    {
        $tableName = 'kg_media';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('upload_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '上传编号',
                'after' => 'id',
            ])
            ->addColumn('file_origin', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '原始文件',
                'after' => 'upload_id',
            ])
            ->addColumn('file_standard', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '常规转码',
                'after' => 'file_origin',
            ])
            ->addColumn('file_encrypt', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '加密转码',
                'after' => 'file_standard',
            ])
            ->addColumn('standard_status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '常码状态',
                'after' => 'file_encrypt',
            ])
            ->addColumn('encrypt_status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '加码状态',
                'after' => 'standard_status',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'encrypt_status',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['upload_id'], [
                'name' => 'upload_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createMigrationPhalconTable()
    {
        $tableName = 'kg_migration_phalcon';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('version', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '版本',
                'after' => 'id',
            ])
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'version',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addIndex(['version'], [
                'name' => 'version',
                'unique' => true,
            ])
            ->create();
    }

    protected function createNavTable()
    {
        $tableName = 'kg_nav';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '层级',
                'after' => 'parent_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'level',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('target', 'string', [
                'null' => false,
                'default' => '_blank',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '打开方式',
                'after' => 'path',
            ])
            ->addColumn('url', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '链接地址',
                'after' => 'target',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '位置',
                'after' => 'url',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => '10',
                'signed' => false,
                'comment' => '优先级',
                'after' => 'position',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'priority',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('child_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '子类数量',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'child_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createOrderTable()
    {
        $tableName = 'kg_order';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '订单标题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '订单金额',
                'after' => 'subject',
            ])
            ->addColumn('currency', 'string', [
                'null' => false,
                'default' => 'USD',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '货币类型',
                'after' => 'amount',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'currency',
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '条目编号',
                'after' => 'owner_id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 3000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '条目内容',
                'after' => 'item_type',
            ])
            ->addColumn('coupon_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优惠券编号',
                'after' => 'item_info',
            ])
            ->addColumn('coupon_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '优惠券信息',
                'after' => 'coupon_id',
            ])
            ->addColumn('payment_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '支付类型',
                'after' => 'coupon_info',
            ])
            ->addColumn('payment_info', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '支付信息',
                'after' => 'payment_type',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'payment_info',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('client_country', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端国家',
                'after' => 'client_ip',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '状态标识',
                'after' => 'client_country',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['item_id', 'item_type'], [
                'name' => 'item',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['coupon_id'], [
                'name' => 'coupon_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createOrderStatusTable()
    {
        $tableName = 'kg_order_status';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('order_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '订单状态',
                'after' => 'order_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createPackageTable()
    {
        $tableName = 'kg_package';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('regular_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '市场价格',
                'after' => 'summary',
            ])
            ->addColumn('vip_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '会员价格',
                'after' => 'regular_price',
            ])
            ->addColumn('course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程数量',
                'after' => 'vip_price',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'course_count',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createPageTable()
    {
        $tableName = 'kg_page';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'slug',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'slug',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'summary',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'keywords',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'content',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'view_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createRefundTable()
    {
        $tableName = 'kg_refund';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('order_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'owner_id',
            ])
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '退款序号',
                'after' => 'order_id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '退款主题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '退款金额',
                'after' => 'subject',
            ])
            ->addColumn('currency', 'string', [
                'null' => false,
                'default' => 'USD',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '货币类型',
                'after' => 'amount',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '状态类型',
                'after' => 'currency',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('apply_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '申请备注',
                'after' => 'deleted',
            ])
            ->addColumn('review_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '审核备注',
                'after' => 'apply_note',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'review_note',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createRefundStatusTable()
    {
        $tableName = 'kg_refund_status';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('refund_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '订单状态',
                'after' => 'refund_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addIndex(['refund_id'], [
                'name' => 'refund_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createRoleTable()
    {
        $tableName = 'kg_role';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '2',
                'limit' => '10',
                'signed' => false,
                'comment' => '类型',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'name',
            ])
            ->addColumn('routes', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '权限路由',
                'after' => 'summary',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'routes',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '成员数量',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'user_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createResourceTable()
    {
        $tableName = 'kg_resource';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('upload_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '上传编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'upload_id',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createReviewTable()
    {
        $tableName = 'kg_review';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'owner_id',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'client_ip',
            ])
            ->addColumn('rating', 'integer', [
                'null' => false,
                'default' => '5',
                'limit' => '10',
                'signed' => false,
                'comment' => '综合评分',
                'after' => 'content',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'rating',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'like_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createReviewLikeTable()
    {
        $tableName = 'kg_review_like';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('review_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '评价编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'review_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['review_id'], [
                'name' => 'review_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createSettingTable()
    {
        $tableName = 'kg_setting';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('section', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置组',
                'after' => 'id',
            ])
            ->addColumn('item_key', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置项',
                'after' => 'section',
            ])
            ->addColumn('item_value', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置值',
                'after' => 'item_key',
            ])
            ->addIndex(['section', 'item_key'], [
                'name' => 'section_key',
                'unique' => true,
            ])
            ->create();
    }

    protected function createSlideTable()
    {
        $tableName = 'kg_slide';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'summary',
            ])
            ->addColumn('target', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '目标类型',
                'after' => 'content',
            ])
            ->addColumn('target_attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '目标属性',
                'after' => 'target',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '10',
                'limit' => '10',
                'signed' => false,
                'comment' => '优先级',
                'after' => 'target_attrs',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布状态',
                'after' => 'priority',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createTaskTable()
    {
        $tableName = 'kg_task';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '条目编号',
                'after' => 'id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 3000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '条目内容',
                'after' => 'item_type',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '状态标识',
                'after' => 'item_info',
            ])
            ->addColumn('locked', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '锁定标识',
                'after' => 'status',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => '10',
                'signed' => false,
                'comment' => '优先级',
                'after' => 'locked',
            ])
            ->addColumn('try_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '重试数',
                'after' => 'priority',
            ])
            ->addColumn('max_try_count', 'integer', [
                'null' => false,
                'default' => '3',
                'limit' => '10',
                'signed' => false,
                'comment' => '最大重试数',
                'after' => 'try_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'max_try_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['create_time'], [
                'name' => 'create_time',
                'unique' => false,
            ])
            ->create();
    }

    protected function createThumbLinkTable()
    {
        $tableName = 'kg_thumb_link';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('source_crc32', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '源路crc32',
                'after' => 'id',
            ])
            ->addColumn('target_crc32', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '标路crc32',
                'after' => 'source_crc32',
            ])
            ->addColumn('source_path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '源头路径',
                'after' => 'target_crc32',
            ])
            ->addColumn('target_path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '目标路径',
                'after' => 'source_path',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'target_path',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['source_crc32'], [
                'name' => 'source_crc32',
                'unique' => false,
            ])
            ->addIndex(['target_crc32'], [
                'name' => 'target_crc32',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUploadTable()
    {
        $tableName = 'kg_upload';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件名',
                'after' => 'type',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('mime', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'mime',
                'after' => 'path',
            ])
            ->addColumn('md5', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'md5',
                'after' => 'mime',
            ])
            ->addColumn('size', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '大小',
                'after' => 'md5',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'size',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['md5'], [
                'name' => 'md5',
                'unique' => false,
            ])
            ->addIndex(['path'], [
                'name' => 'path',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUserTable()
    {
        $tableName = 'kg_user';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '主键编号',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头像',
                'after' => 'name',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头衔',
                'after' => 'avatar',
            ])
            ->addColumn('about', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'title',
            ])
            ->addColumn('vip', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '会员标识',
                'after' => 'about',
            ])
            ->addColumn('locked', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '锁定标识',
                'after' => 'vip',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'locked',
            ])
            ->addColumn('edu_role', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '教学角色',
                'after' => 'deleted',
            ])
            ->addColumn('admin_role', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '后台角色',
                'after' => 'edu_role',
            ])
            ->addColumn('vip_expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '会员期限',
                'after' => 'admin_role',
            ])
            ->addColumn('lock_expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '锁定期限',
                'after' => 'vip_expiry_time',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'lock_expiry_time',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['name'], [
                'name' => 'name',
                'unique' => false,
            ])
            ->create();
    }

    protected function createVipTable()
    {
        $tableName = 'kg_vip';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => '10',
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'id',
            ])
            ->addColumn('expiry', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '有效期',
                'after' => 'cover',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '价格',
                'after' => 'expiry',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => '10',
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'price',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => '10',
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

}
