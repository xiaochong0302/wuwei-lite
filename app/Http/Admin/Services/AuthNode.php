<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

class AuthNode extends Service
{

    public function getNodes(): array
    {
        $nodes = [];

        $nodes[] = $this->getContentNodes();
        $nodes[] = $this->getMarketingNodes();
        $nodes[] = $this->getSaleNodes();
        $nodes[] = $this->getUserNodes();
        $nodes[] = $this->getSettingNodes();
        $nodes[] = $this->getUtilNodes();

        return $nodes;
    }

    protected function getContentNodes(): array
    {
        return [
            'id' => '1',
            'title' => $this->locale->query('nav_content'),
            'children' => [
                [
                    'id' => '1-1',
                    'title' => $this->locale->query('course_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-1-1',
                            'title' => $this->locale->query('course_list'),
                            'type' => 'menu',
                            'route' => 'admin.course.list',
                        ],
                        [
                            'id' => '1-1-2',
                            'title' => $this->locale->query('category_list'),
                            'type' => 'menu',
                            'route' => 'admin.category.list',
                        ],
                        [
                            'id' => '1-1-3',
                            'title' => $this->locale->query('package_list'),
                            'type' => 'menu',
                            'route' => 'admin.package.list',
                        ],
                    ],
                ],
                [
                    'id' => '1-2',
                    'title' => $this->locale->query('page_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-2-1',
                            'title' => $this->locale->query('page_list'),
                            'type' => 'menu',
                            'route' => 'admin.page.list',
                        ],
                        [
                            'id' => '1-2-2',
                            'title' => $this->locale->query('add_page'),
                            'type' => 'menu',
                            'route' => 'admin.page.add',
                        ],
                        [
                            'id' => '1-2-3',
                            'title' => $this->locale->query('search_page'),
                            'type' => 'menu',
                            'route' => 'admin.page.search',
                        ],
                        [
                            'id' => '1-2-3',
                            'title' => $this->locale->query('edit_page'),
                            'type' => 'button',
                            'route' => 'admin.page.edit',
                        ],
                        [
                            'id' => '1-2-4',
                            'title' => $this->locale->query('delete_page'),
                            'type' => 'button',
                            'route' => 'admin.page.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-3',
                    'title' => $this->locale->query('nav_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-3-1',
                            'title' => $this->locale->query('nav_list'),
                            'type' => 'menu',
                            'route' => 'admin.nav.list',
                        ],
                        [
                            'id' => '1-3-2',
                            'title' => $this->locale->query('add_nav'),
                            'type' => 'menu',
                            'route' => 'admin.nav.add',
                        ],
                        [
                            'id' => '1-3-3',
                            'title' => $this->locale->query('edit_nav'),
                            'type' => 'button',
                            'route' => 'admin.nav.edit',
                        ],
                        [
                            'id' => '1-3-4',
                            'title' => $this->locale->query('delete_nav'),
                            'type' => 'button',
                            'route' => 'admin.nav.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-4',
                    'title' => $this->locale->query('slide_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-4-1',
                            'title' => $this->locale->query('slide_list'),
                            'type' => 'menu',
                            'route' => 'admin.slide.list',
                        ],
                        [
                            'id' => '1-4-2',
                            'title' => $this->locale->query('add_slide'),
                            'type' => 'menu',
                            'route' => 'admin.slide.add',
                        ],
                        [
                            'id' => '1-4-3',
                            'title' => $this->locale->query('search_slide'),
                            'type' => 'menu',
                            'route' => 'admin.slide.search',
                        ],
                        [
                            'id' => '1-4-4',
                            'title' => $this->locale->query('edit_slide'),
                            'type' => 'button',
                            'route' => 'admin.slide.edit',
                        ],
                        [
                            'id' => '1-4-5',
                            'title' => $this->locale->query('delete_slide'),
                            'type' => 'button',
                            'route' => 'admin.slide.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-5',
                    'title' => $this->locale->query('review_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-5-1',
                            'title' => $this->locale->query('review_list'),
                            'type' => 'menu',
                            'route' => 'admin.review.list',
                        ],
                        [
                            'id' => '1-5-2',
                            'title' => $this->locale->query('search_review'),
                            'type' => 'menu',
                            'route' => 'admin.review.search',
                        ],
                        [
                            'id' => '1-5-3',
                            'title' => $this->locale->query('edit_review'),
                            'type' => 'button',
                            'route' => 'admin.review.edit',
                        ],
                        [
                            'id' => '1-5-5',
                            'title' => $this->locale->query('delete_review'),
                            'type' => 'button',
                            'route' => 'admin.review.delete',
                        ],
                        [
                            'id' => '1-5-6',
                            'title' => $this->locale->query('moderate_review'),
                            'type' => 'button',
                            'route' => 'admin.review.moderate',
                        ],
                    ],
                ],
                [
                    'id' => '1-6',
                    'title' => $this->locale->query('comment_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-6-1',
                            'title' => $this->locale->query('comment_list'),
                            'type' => 'menu',
                            'route' => 'admin.comment.list',
                        ],
                        [
                            'id' => '1-6-2',
                            'title' => $this->locale->query('search_comment'),
                            'type' => 'menu',
                            'route' => 'admin.comment.search',
                        ],
                        [
                            'id' => '1-6-3',
                            'title' => $this->locale->query('edit_comment'),
                            'type' => 'button',
                            'route' => 'admin.comment.edit',
                        ],
                        [
                            'id' => '1-6-4',
                            'title' => $this->locale->query('delete_comment'),
                            'type' => 'button',
                            'route' => 'admin.comment.delete',
                        ],
                        [
                            'id' => '1-6-5',
                            'title' => $this->locale->query('moderate_comment'),
                            'type' => 'button',
                            'route' => 'admin.comment.moderate',
                        ],
                    ],
                ],
                [
                    'id' => '1-20',
                    'title' => $this->locale->query('category_manager'),
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-20-1',
                            'title' => $this->locale->query('category_list'),
                            'type' => 'button',
                            'route' => 'admin.category.list',
                        ],
                        [
                            'id' => '1-20-2',
                            'title' => $this->locale->query('add_category'),
                            'type' => 'button',
                            'route' => 'admin.category.add',
                        ],
                        [
                            'id' => '1-20-3',
                            'title' => $this->locale->query('edit_category'),
                            'type' => 'button',
                            'route' => 'admin.category.edit',
                        ],
                        [
                            'id' => '1-20-4',
                            'title' => $this->locale->query('delete_category'),
                            'type' => 'button',
                            'route' => 'admin.category.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-21',
                    'title' => $this->locale->query('course_manager'),
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-21-1',
                            'title' => $this->locale->query('course_list'),
                            'type' => 'button',
                            'route' => 'admin.course.list',
                        ],
                        [
                            'id' => '1-21-2',
                            'title' => $this->locale->query('search_course'),
                            'type' => 'button',
                            'route' => 'admin.course.search',
                        ],
                        [
                            'id' => '1-21-3',
                            'title' => $this->locale->query('add_course'),
                            'type' => 'button',
                            'route' => 'admin.course.add',
                        ],
                        [
                            'id' => '1-21-4',
                            'title' => $this->locale->query('edit_course'),
                            'type' => 'button',
                            'route' => 'admin.course.edit',
                        ],
                        [
                            'id' => '1-21-5',
                            'title' => $this->locale->query('delete_course'),
                            'type' => 'button',
                            'route' => 'admin.course.delete',
                        ],
                        [
                            'id' => '1-21-6',
                            'title' => $this->locale->query('course_users'),
                            'type' => 'button',
                            'route' => 'admin.course.users',
                        ],
                    ],
                ],
                [
                    'id' => '1-22',
                    'title' => $this->locale->query('package_manager'),
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-22-1',
                            'title' => $this->locale->query('package_manager'),
                            'type' => 'button',
                            'route' => 'admin.package.list',
                        ],
                        [
                            'id' => '1-22-2',
                            'title' => $this->locale->query('search_package'),
                            'type' => 'button',
                            'route' => 'admin.package.search',
                        ],
                        [
                            'id' => '1-22-3',
                            'title' => $this->locale->query('add_package'),
                            'type' => 'button',
                            'route' => 'admin.package.add',
                        ],
                        [
                            'id' => '1-22-4',
                            'title' => $this->locale->query('edit_package'),
                            'type' => 'button',
                            'route' => 'admin.package.edit',
                        ],
                        [
                            'id' => '1-22-5',
                            'title' => $this->locale->query('delete_package'),
                            'type' => 'button',
                            'route' => 'admin.package.delete',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getMarketingNodes(): array
    {
        return [
            'id' => '2',
            'title' => $this->locale->query('nav_marketing'),
            'children' => [
                [
                    'id' => '2-2',
                    'title' => $this->locale->query('vip_plan_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-2-1',
                            'title' => $this->locale->query('vip_plan_list'),
                            'type' => 'menu',
                            'route' => 'admin.vip.list',
                        ],
                        [
                            'id' => '2-2-2',
                            'title' => $this->locale->query('add_vip_plan'),
                            'type' => 'menu',
                            'route' => 'admin.vip.add',
                        ],
                        [
                            'id' => '2-2-3',
                            'title' => $this->locale->query('edit_vip_plan'),
                            'type' => 'button',
                            'route' => 'admin.vip.edit',
                        ],
                        [
                            'id' => '2-2-4',
                            'title' => $this->locale->query('delete_vip_plan'),
                            'type' => 'button',
                            'route' => 'admin.vip.delete',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getSaleNodes()
    {
        return [
            'id' => '3',
            'title' => $this->locale->query('nav_sales'),
            'children' => [
                [
                    'id' => '3-1',
                    'title' => $this->locale->query('order_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '3-1-1',
                            'title' => $this->locale->query('order_list'),
                            'type' => 'menu',
                            'route' => 'admin.order.list',
                        ],
                        [
                            'id' => '3-1-2',
                            'title' => $this->locale->query('search_order'),
                            'type' => 'menu',
                            'route' => 'admin.order.search',
                        ],
                        [
                            'id' => '3-1-3',
                            'title' => $this->locale->query('view_order'),
                            'type' => 'button',
                            'route' => 'admin.order.show',
                        ],
                    ],
                ],
                [
                    'id' => '3-2',
                    'title' => $this->locale->query('refund_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '3-2-1',
                            'title' => $this->locale->query('refund_list'),
                            'type' => 'menu',
                            'route' => 'admin.refund.list',
                        ],
                        [
                            'id' => '3-2-2',
                            'title' => $this->locale->query('search_refund'),
                            'type' => 'menu',
                            'route' => 'admin.refund.search',
                        ],
                        [
                            'id' => '3-2-3',
                            'title' => $this->locale->query('view_refund'),
                            'type' => 'button',
                            'route' => 'admin.refund.show',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getUserNodes(): array
    {
        return [
            'id' => '4',
            'title' => $this->locale->query('nav_users'),
            'children' => [
                [
                    'id' => '4-1',
                    'title' => $this->locale->query('user_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-1-1',
                            'title' => $this->locale->query('user_list'),
                            'type' => 'menu',
                            'route' => 'admin.user.list',
                        ],
                        [
                            'id' => '4-1-2',
                            'title' => $this->locale->query('search_user'),
                            'type' => 'menu',
                            'route' => 'admin.user.search',
                        ],
                        [
                            'id' => '4-1-3',
                            'title' => $this->locale->query('add_user'),
                            'type' => 'menu',
                            'route' => 'admin.user.add',
                        ],
                        [
                            'id' => '4-1-4',
                            'title' => $this->locale->query('edit_user'),
                            'type' => 'button',
                            'route' => 'admin.user.edit',
                        ],
                    ],
                ],
                [
                    'id' => '4-2',
                    'title' => $this->locale->query('role_manager'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-2-1',
                            'title' => $this->locale->query('role_list'),
                            'type' => 'menu',
                            'route' => 'admin.role.list',
                        ],
                        [
                            'id' => '4-2-2',
                            'title' => $this->locale->query('add_role'),
                            'type' => 'menu',
                            'route' => 'admin.role.add',
                        ],
                        [
                            'id' => '4-2-3',
                            'title' => $this->locale->query('edit_role'),
                            'type' => 'button',
                            'route' => 'admin.role.edit',
                        ],
                        [
                            'id' => '4-2-4',
                            'title' => $this->locale->query('delete_role'),
                            'type' => 'button',
                            'route' => 'admin.role.delete',
                        ]
                    ],
                ],
                [
                    'id' => '4-3',
                    'title' => $this->locale->query('audit_history'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-3-1',
                            'title' => $this->locale->query('audit_list'),
                            'type' => 'menu',
                            'route' => 'admin.audit.list',
                        ],
                        [
                            'id' => '4-3-2',
                            'title' => $this->locale->query('search_audit'),
                            'type' => 'menu',
                            'route' => 'admin.audit.search',
                        ],
                        [
                            'id' => '4-3-3',
                            'title' => $this->locale->query('view_audit'),
                            'type' => 'button',
                            'route' => 'admin.audit.show',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getSettingNodes(): array
    {
        return [
            'id' => '5',
            'title' => $this->locale->query('nav_settings'),
            'children' => [
                [
                    'id' => '5-1',
                    'title' => $this->locale->query('basic_settings'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-1-1',
                            'title' => $this->locale->query('site_settings'),
                            'type' => 'menu',
                            'route' => 'admin.setting.site',
                        ],
                        [
                            'id' => '5-1-2',
                            'title' => $this->locale->query('contact_settings'),
                            'type' => 'menu',
                            'route' => 'admin.setting.contact',
                        ],
                        [
                            'id' => '5-1-3',
                            'title' => $this->locale->query('mail_settings'),
                            'type' => 'menu',
                            'route' => 'admin.setting.mail',
                        ],
                        [
                            'id' => '5-1-4',
                            'title' => $this->locale->query('payment_settings'),
                            'type' => 'menu',
                            'route' => 'admin.setting.payment',
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function getUtilNodes(): array
    {
        return [
            'id' => '6',
            'title' => $this->locale->query('nav_utils'),
            'children' => [
                [
                    'id' => '6-1',
                    'title' => $this->locale->query('basic_tools'),
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '6-1-1',
                            'title' => $this->locale->query('flush_caches'),
                            'type' => 'menu',
                            'route' => 'admin.util.cache',
                        ],
                    ],
                ],
            ],
        ];
    }

}
