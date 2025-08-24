<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class Audit extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $user_id = 0;

    /**
     * 用户名称
     *
     * @var string
     */
    public string $user_name = '';

    /**
     * 用户IP
     *
     * @var string
     */
    public string $user_ip = '';

    /**
     * 请求路由
     *
     * @var string
     */
    public string $req_route = '';

    /**
     * 请求路径
     *
     * @var string
     */
    public string $req_path = '';

    /**
     * 请求参数
     *
     * @var array|string
     */
    public string|array $req_data = '';

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_audit');
    }

    public function beforeCreate(): void
    {
        if (is_array($this->req_data) && !empty($this->req_data)) {
            foreach ($this->req_data as $key => $value) {
                if (!is_scalar($value)) {
                    $value = kg_json_encode($value);
                }
                if (kg_strlen($value) > 255) {
                    $this->req_data[$key] = kg_substr($value, 0, 255);
                }
            }
            $this->req_data = kg_json_encode($this->req_data);
        } else {
            $this->req_data = '';
        }

        $this->create_time = time();
    }

}
