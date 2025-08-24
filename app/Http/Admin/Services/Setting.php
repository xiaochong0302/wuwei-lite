<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\Setting as SettingCache;
use App\Library\Language;
use App\Models\KgSale as KgSaleModel;
use App\Repos\Setting as SettingRepo;
use DateTimeZone;

class Setting extends Service
{

    public function getTimezones(): array
    {
        $ids = DateTimeZone::listIdentifiers();

        $result = [];

        foreach ($ids as $id) {
            $name = str_replace(['/', '_', '-'], [' / ', ' ', ' '], $id);
            $result[] = [
                'code' => $id,
                'name' => $name,
            ];
        }

        return $result;
    }

    public function getLanguages(): array
    {
        $obj = new Language();

        return $obj->getLanguages();
    }

    public function getCurrencies(): array
    {
        return KgSaleModel::currencies();
    }

    public function getSettings(string $section): array
    {
        $settingsRepo = new SettingRepo();

        $items = $settingsRepo->findBySection($section);

        $result = [];

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result[$item->item_key] = $item->item_value;
            }
        }

        return $result;
    }

    public function updateSettings(string $section, array $settings): void
    {
        $settingsRepo = new SettingRepo();

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                array_walk_recursive($value, function (&$item) {
                    $item = trim($item);
                });
                $itemValue = kg_json_encode($value);
            } else {
                $itemValue = trim($value);
            }
            $item = $settingsRepo->findItem($section, $key);
            if ($item) {
                $item->item_value = $itemValue;
                $item->update();
            }
        }

        $cache = new SettingCache();

        $cache->rebuild($section);
    }

}
