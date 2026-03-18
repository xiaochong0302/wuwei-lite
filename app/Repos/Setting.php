<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Setting as SettingModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Setting extends Repository
{

    /**
     * @param string $section
     * @param string $itemKey
     * @return SettingModel|Row|null
     */
    public function findItem(string $section, string $itemKey)
    {
        return SettingModel::findFirst([
            'conditions' => 'section = :section: AND item_key = :item_key:',
            'bind' => ['section' => $section, 'item_key' => $itemKey],
        ]);
    }

    /**
     * @param string $section
     * @return ResultsetInterface|Resultset|SettingModel[]
     */
    public function findBySection(string $section)
    {
        $query = SettingModel::query();

        $query->where('section = :section:', ['section' => $section]);

        return $query->execute();
    }

}
