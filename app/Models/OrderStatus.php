<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class OrderStatus extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 订单编号
     *
     * @var int
     */
    public int $order_id = 0;

    /**
     * 状态类型
     *
     * @var int
     */
    public int $status = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_order_status');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

}
