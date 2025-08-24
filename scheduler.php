<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

require_once __DIR__ . '/vendor/autoload.php';

use GO\Scheduler;

$scheduler = new Scheduler();

$script = __DIR__ . '/console.php';

$bin = '/usr/local/bin/php';

$scheduler->php($script, $bin, ['--task' => 'deliver', '--action' => 'main'])
    ->everyMinute();

$scheduler->php($script, $bin, ['--task' => 'notice', '--action' => 'main'])
    ->everyMinute();

$scheduler->php($script, $bin, ['--task' => 'sync_learning', '--action' => 'main'])
    ->everyMinute(7);

$scheduler->php($script, $bin, ['--task' => 'refund', '--action' => 'main'])
    ->hourly(3);

$scheduler->php($script, $bin, ['--task' => 'sync_course_index', '--action' => 'main'])
    ->hourly(11);

$scheduler->php($script, $bin, ['--task' => 'sync_chapter_index', '--action' => 'main'])
    ->hourly(13);

$scheduler->php($script, $bin, ['--task' => 'sync_course_score', '--action' => 'main'])
    ->hourly(17);

$scheduler->php($script, $bin, ['--task' => 'close_order', '--action' => 'main'])
    ->hourly(19);

$scheduler->php($script, $bin, ['--task' => 'clean_log', '--action' => 'main'])
    ->daily(3, 3);

$scheduler->php($script, $bin, ['--task' => 'unlock_user', '--action' => 'main'])
    ->daily(3, 7);

$scheduler->php($script, $bin, ['--task' => 'revoke_vip', '--action' => 'main'])
    ->daily(3, 11);

$scheduler->php($script, $bin, ['--task' => 'sync_course_stat', '--action' => 'main'])
    ->daily(3, 17);

$scheduler->php($script, $bin, ['--task' => 'sync_sitemap', '--action' => 'main'])
    ->daily(4, 7);

$scheduler->run();
