<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Models;

use Phalcon\Di\Di;

class KgSale
{

    /**
     * 物品类型
     */
    const ITEM_COURSE = 1; // 课程服务
    const ITEM_PACKAGE = 2; // 课程套餐
    const ITEM_VIP = 3; // 会员套餐

    public static function itemTypes(): array
    {
        $locale = Di::getDefault()->getShared('locale');

        return [
            self::ITEM_COURSE => $locale->query('sale_item_course'),
            self::ITEM_PACKAGE => $locale->query('sale_item_package'),
            self::ITEM_VIP => $locale->query('sale_item_vip'),
        ];
    }

    public static function currencies(): array
    {
        return [
            'USD' => ['symbol' => '$', 'unit' => 100, 'title' => 'US Dollar (USD)'],
            'EUR' => ['symbol' => '€', 'unit' => 100, 'title' => 'Euro (EUR)'],
            'GBP' => ['symbol' => '£', 'unit' => 100, 'title' => 'British Pound (GBP)'],
            'CAD' => ['symbol' => 'C$', 'unit' => 100, 'title' => 'Canadian Dollar (CAD)'],
            'AUD' => ['symbol' => 'A$', 'unit' => 100, 'title' => 'Australian Dollar (AUD)'],
            'SGD' => ['symbol' => 'S$', 'unit' => 100, 'title' => 'Singapore Dollar (SGD)'],
            'JPY' => ['symbol' => '￥', 'unit' => 1, 'title' => 'Japanese Yen (JPY)'],
            'RUB' => ['symbol' => '₽', 'unit' => 1, 'title' => 'Russian Ruble (RUB)'],
        ];
    }

}
