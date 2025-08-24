#!/usr/bin/env php

<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use Bootstrap\ConsoleKernel;

require __DIR__ . '/bootstrap/Kernel.php';
require __DIR__ . '/bootstrap/ConsoleKernel.php';

$kernel = new ConsoleKernel();

$kernel->handle();
