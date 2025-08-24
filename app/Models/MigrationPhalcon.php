<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class MigrationPhalcon extends Model
{

    /**
     * 主键
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 版本
     *
     * @var string
     */
    public string $version = '';

    /**
     * 开始时间
     *
     * @var int
     */
    public int $start_time = 0;

    /**
     * 结束时间
     *
     * @var int
     */
    public int $end_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_migration_phalcon');
    }

}
