<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\Mvc\ViewBaseInterface;

class Volt extends Provider
{

    protected string $serviceName = 'volt';

    public function register(): void
    {
        $di = $this->di;

        $this->di->setShared('volt', function (ViewBaseInterface $view) use ($di) {

            $volt = new PhVolt($view, $di);

            $volt->setOptions([
                'path' => cache_path() . '/volt/',
                'separator' => '_',
            ]);

            $compiler = $volt->getCompiler();

            $compiler->addFunction('config', function ($resolvedArgs) {
                return 'App\Services\Volt:config(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('setting', function ($resolvedArgs) {
                return 'App\Services\Volt::setting(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('substr', function ($resolvedArgs) {
                return 'App\Services\Volt::substr(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('full_url', function ($resolvedArgs) {
                return 'App\Services\Volt::fullUrl(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('share_url', function ($resolvedArgs) {
                return 'App\Services\Volt::shareUrl(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('static_url', function ($resolvedArgs) {
                return 'App\Services\Volt::staticUrl(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('icon_link', function ($resolvedArgs) {
                return 'App\Services\Volt::iconLink(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('css_link', function ($resolvedArgs) {
                return 'App\Services\Volt::cssLink(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('js_include', function ($resolvedArgs) {
                return 'App\Services\Volt::jsInclude(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('array_object', function ($resolvedArgs) {
                return 'App\Services\Volt::arrayObject(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('course_level', function ($resolvedArgs) {
                return 'App\Services\Volt::courseLevel(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('chapter_model', function ($resolvedArgs) {
                return 'App\Services\Volt::chapterModel(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('sale_item_type', function ($resolvedArgs) {
                return 'App\Services\Volt::saleItemType(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('payment_type', function ($resolvedArgs) {
                return 'App\Services\Volt::paymentType(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('comment_status', function ($resolvedArgs) {
                return 'App\Services\Volt::commentStatus(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('review_status', function ($resolvedArgs) {
                return 'App\Services\Volt::reviewStatus(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('order_status', function ($resolvedArgs) {
                return 'App\Services\Volt::orderStatus(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('refund_status', function ($resolvedArgs) {
                return 'App\Services\Volt::refundStatus(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('human_number', function ($resolvedArgs) {
                return 'App\Services\Volt::humanNumber(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('human_size', function ($resolvedArgs) {
                return 'App\Services\Volt::humanSize(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('human_price', function ($resolvedArgs) {
                return 'App\Services\Volt::humanPrice(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('time_ago', function ($resolvedArgs) {
                return 'App\Services\Volt::timeAgo(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('anonymous', function ($resolvedArgs) {
                return 'App\Services\Volt::anonymous(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('duration', function ($resolvedArgs) {
                return 'App\Services\Volt::duration(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('split', function ($resolvedArgs, $exprArgs) use ($compiler) {
                $firstArgument = $compiler->expression($exprArgs[0]['expr']);
                if (isset($exprArgs[1])) {
                    $secondArgument = $compiler->expression($exprArgs[1]['expr']);
                } else {
                    $secondArgument = '';
                }
                return sprintf('explode(%s,%s)', $secondArgument, $firstArgument);
            });

            return $volt;
        });
    }

}
