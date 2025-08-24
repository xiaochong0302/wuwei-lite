<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Repos\Setting as SettingRepo;

class Setting extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 360 * 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return "setting-{$id}";
    }

    public function getContent($id = null): array
    {
        $settingRepo = new SettingRepo();

        $items = $settingRepo->findAll(['section' => $id]);

        if ($items->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item->item_key] = $item->item_value;
        }

        return $result;
    }

}
