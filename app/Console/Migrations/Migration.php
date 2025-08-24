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

    protected function saveSettings(array $settings): void
    {
        foreach ($settings as $setting) {
            $this->saveSetting($setting);
        }
    }

    protected function saveSetting(array $setting): void
    {
        $settingRepo = new SettingRepo();

        $item = $settingRepo->findItem($setting['section'], $setting['item_key']);

        if (!$item) {
            $item = new SettingModel();
            $item->assign($setting);
            $item->create();
        } else {
            $item->assign($setting);
            $item->update();
        }
    }

}
