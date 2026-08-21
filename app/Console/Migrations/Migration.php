<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\Setting as SettingModel;
use App\Repos\Setting as SettingRepo;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

abstract class Migration extends Injectable
{

    use ServiceTrait;

    abstract public function run(): void;

    protected function saveSettings(string $section, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->saveSetting($section, $key, $value);
        }
    }

    protected function deleteSettings(string $section, array $keys): void
    {
        foreach ($keys as $key) {
            $this->deleteSetting($section, $key);
        }
    }

    protected function findSetting(string $section, string $itemKey)
    {
        $settingRepo = new SettingRepo();

        return $settingRepo->findItem($section, $itemKey);
    }

    protected function deleteSetting(string $section, string $itemKey): void
    {
        $setting = $this->findSetting($section, $itemKey);

        if (!$setting) return;

        $setting->delete();
    }

    protected function saveSetting(string $section, string $itemKey, array|string $itemValue): void
    {
        if (is_array($itemValue)) {
            $itemValue = kg_json_encode($itemValue);
        }

        $item = $this->findSetting($section, $itemKey);

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
