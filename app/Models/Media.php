<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

class Media extends Model
{

    /**
     * 转码模式
     */
    const TRANS_MODE_STANDARD = 'standard'; // 标准转码
    const TRANS_MODE_ENCRYPT = 'encrypt'; // 加密转码
    const TRANS_MODE_ORIGIN = 'origin'; // 暂不转码

    /**
     * 转码状态
     */
    const TRANS_STATUS_PENDING = 1; // 待启动
    const TRANS_STATUS_CREATED = 2; // 已创建
    const TRANS_STATUS_PROCESSING = 3; // 转码中
    const TRANS_STATUS_FINISHED = 4; // 已完成
    const TRANS_STATUS_FAILED = 5; // 已失败

    /**
     * 主键编号
     *
     * @var int
     */
    public int $id = 0;

    /**
     * 上传编号
     *
     * @var int
     */
    public int $upload_id = 0;

    /**
     * 原始文件
     *
     * @var array|string
     */
    public string|array $file_origin = [];

    /**
     * 常规转码
     *
     * @var array|string
     */
    public string|array $file_standard = [];

    /**
     * 加密转码
     *
     * @var array|string
     */
    public string|array $file_encrypt = [];

    /**
     * 常规转码状态
     *
     * @var int
     */
    public int $standard_status = self::TRANS_STATUS_PENDING;

    /**
     * 加密转码状态
     *
     * @var int
     */
    public int $encrypt_status = self::TRANS_STATUS_PENDING;

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

        $this->setSource('kg_media');
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
        if (is_array($this->file_origin)) {
            $this->file_origin = kg_json_encode($this->file_origin);
        }

        if (is_array($this->file_standard)) {
            $fileStandard = kg_array_unique_multi($this->file_standard, 'width');
            $this->file_standard = kg_json_encode($fileStandard);
        }

        if (is_array($this->file_encrypt)) {
            $fileEncrypt = kg_array_unique_multi($this->file_encrypt, 'width');
            $this->file_encrypt = kg_json_encode($fileEncrypt);
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->file_origin)) {
            $this->file_origin = json_decode($this->file_origin, true);
        }

        if (is_string($this->file_standard)) {
            $this->file_standard = json_decode($this->file_standard, true);
        }

        if (is_string($this->file_encrypt)) {
            $this->file_encrypt = json_decode($this->file_encrypt, true);
        }
    }

}
