<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.net
 */

namespace App\Console\Migrations;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Models\Nav as NavModel;
use App\Models\Page as PageModel;
use App\Models\Role as RoleModel;
use App\Models\Vip as VipModel;
use App\Repos\User as UserRepo;

class V20250517020616 extends Migration
{

    public function run(): void
    {
        $this->initSettings();
        $this->initUserData();
        $this->initRoleData();
        $this->initPageData();
        $this->initNavData();
        $this->initVipData();
    }

    public function initSettings(): void
    {
        $this->handleSiteSettings();
        $this->handleMailSettings();
        $this->handlePaypalPaymentSettings();
        $this->handleStripePaymentSettings();
        $this->handleContactSettings();
    }

    protected function initUserData(): void
    {
        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash('123456', $salt);

        $account = new AccountModel();

        $account->id = 10000;
        $account->email = '10000@163.com';
        $account->password = $password;
        $account->salt = $salt;

        $account->create();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $user->name = 'WuWei';
        $user->title = 'Stuff';
        $user->about = 'WUWEI: Your Trusted Partner in Digital Education';
        $user->admin_role = 1;

        $user->update();
    }

    protected function initRoleData(): void
    {
        $role = new RoleModel();

        $role->assign([
            'id' => 1,
            'type' => 1,
            'name' => 'Administrator',
            'summary' => '',
            'routes' => '[]',
            'user_count' => 1,
            'create_time' => time(),
        ]);

        $role->create();
    }

    protected function initPageData(): void
    {
        $rows = [
            [
                'id' => 1,
                'title' => 'About Us',
                'slug' => 'about-us',
                'published' => 1,
            ],
            [
                'id' => 2,
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'published' => 1,
            ],
            [
                'id' => 3,
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'published' => 1,
            ],
        ];

        foreach ($rows as $row) {
            $page = new PageModel();
            $page->assign($row);
            $page->create();
        }
    }

    protected function initNavData(): void
    {
        $rows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'Home',
                'path' => ',1,',
                'target' => '_self',
                'url' => '/',
                'position' => 1,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'Courses',
                'path' => ',2,',
                'target' => '_self',
                'url' => '/course/list',
                'position' => 1,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 3,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'Instructors',
                'path' => ',3,',
                'target' => '_self',
                'url' => '/teacher/list',
                'position' => 1,
                'priority' => 3,
                'published' => 1,
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'About Us',
                'path' => ',4,',
                'target' => '_blank',
                'url' => '/page/1/about-us',
                'position' => 2,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 5,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'Terms of Service',
                'path' => ',5,',
                'target' => '_blank',
                'url' => '/page/2/terms-of-service',
                'position' => 2,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 6,
                'parent_id' => 0,
                'level' => 1,
                'name' => 'Privacy Policy',
                'path' => ',6,',
                'target' => '_blank',
                'url' => '/page/3/privacy-policy',
                'position' => 2,
                'priority' => 3,
                'published' => 1,
            ],
        ];

        foreach ($rows as $row) {
            $nav = new NavModel();
            $nav->assign($row);
            $nav->create();
        }
    }

    protected function initVipData(): void
    {
        $rows = [
            [
                'id' => 1,
                'expiry' => 1,
                'price' => 60,
                'published' => 1,
            ],
            [
                'id' => 2,
                'expiry' => 3,
                'price' => 150,
                'published' => 1,
            ],
            [
                'id' => 3,
                'expiry' => 6,
                'price' => 240,
                'published' => 1,
            ],
            [
                'id' => 4,
                'expiry' => 12,
                'price' => 360,
                'published' => 1,
            ],
        ];

        foreach ($rows as $row) {
            $vip = new VipModel();
            $vip->assign($row);
            $vip->create();
        }
    }

    protected function handleSiteSettings(): void
    {
        $settings = [
            [
                'section' => 'site',
                'item_key' => 'title',
                'item_value' => 'WUWEI LMS',
            ],
            [
                'section' => 'site',
                'item_key' => 'url',
                'item_value' => '',
            ],

            [
                'section' => 'site',
                'item_key' => 'logo',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'favicon',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'language',
                'item_value' => 'en',
            ],
            [
                'section' => 'site',
                'item_key' => 'timezone',
                'item_value' => 'Asia/Shanghai',
            ],
            [
                'section' => 'site',
                'item_key' => 'currency',
                'item_value' => 'USD',
            ],
            [
                'section' => 'site',
                'item_key' => 'keywords',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'description',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'copyright',
                'item_value' => '2025 WUWEI LMS',
            ],
            [
                'section' => 'site',
                'item_key' => 'status',
                'item_value' => 'online',
            ],
            [
                'section' => 'site',
                'item_key' => 'offline_tips',
                'item_value' => 'Site under maintenance, please try again later.',
            ],
            [
                'section' => 'site',
                'item_key' => 'allow_register',
                'item_value' => 1,
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics_enabled',
                'item_value' => 0,
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics_script',
                'item_value' => '',
            ],
        ];

        $this->saveSettings($settings);
    }

    protected function handleMailSettings(): void
    {
        $settings = [
            [
                'section' => 'mail',
                'item_key' => 'smtp_host',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_port',
                'item_value' => 465,
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_encryption',
                'item_value' => 'ssl',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_from_email',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_from_name',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_auth_enabled',
                'item_value' => 1,
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_username',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_password',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'notification',
                'item_value' => json_encode([
                    'account_login' => 1,
                    'order_finish' => 1,
                    'refund_finish' => 1,
                    'review_remind' => 1,
                ]),
            ],
        ];

        $this->saveSettings($settings);
    }

    protected function handlePaypalPaymentSettings(): void
    {
        $settings = [
            [
                'section' => 'payment.paypal',
                'item_key' => 'enabled',
                'item_value' => 0,
            ],
            [
                'section' => 'payment.paypal',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'payment.paypal',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'payment.paypal',
                'item_key' => 'service_rate',
                'item_value' => 5,
            ],
            [
                'section' => 'payment.paypal',
                'item_key' => 'webhook_id',
                'item_value' => '',
            ],
            [
                'section' => 'payment.paypal',
                'item_key' => 'webhook_url',
                'item_value' => '',
            ],
        ];

        $this->saveSettings($settings);
    }

    protected function handleStripePaymentSettings(): void
    {
        $settings = [
            [
                'section' => 'payment.stripe',
                'item_key' => 'enabled',
                'item_value' => 0,
            ],
            [
                'section' => 'payment.stripe',
                'item_key' => 'api_key',
                'item_value' => '',
            ],
            [
                'section' => 'payment.stripe',
                'item_key' => 'service_rate',
                'item_value' => 5,
            ],
            [
                'section' => 'payment.stripe',
                'item_key' => 'webhook_secret',
                'item_value' => '',
            ],
            [
                'section' => 'payment.stripe',
                'item_key' => 'webhook_url',
                'item_value' => '',
            ],
        ];

        $this->saveSettings($settings);
    }

    protected function handleContactSettings(): void
    {
        $settings = [
            [
                'section' => 'contact',
                'item_key' => 'enabled',
                'item_value' => 0,
            ],
            [
                'section' => 'contact',
                'item_key' => 'email',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'phone',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'address',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'facebook',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'reddit',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'twitter',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'youtube',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'linkedin',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'tiktok',
                'item_value' => '',
            ],
        ];

        $this->saveSettings($settings);
    }

}
