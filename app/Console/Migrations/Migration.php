<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.net
 */

namespace App\Console\Migrations;

use App\Models\Setting as SettingModel;
use App\Repos\Setting as SettingRepo;

abstract class Migration
{

    abstract public function run(): void;

    protected function saveSettings(string $section, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->saveSetting($section, $key, $value);
        }
    }

    protected function saveSetting(string $section, string $itemKey, array|string $itemValue): void
    {
        if (is_array($itemValue)) {
            $itemValue = json_encode($itemValue);
        }

        $settingRepo = new SettingRepo();

        $item = $settingRepo->findItem($section, $itemKey);

        if (!$item) {
            $newItem = new SettingModel();
            $newItem->section = $section;
            $newItem->item_key = $itemKey;
            $newItem->item_value = $itemValue;
            $newItem->create();
        } else {
            $item->item_value = $itemValue;
            $item->update();
        }
    }

}
