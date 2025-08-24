<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class Benchmark
{

    /**
     * @var float
     */
    protected float $startTime = 0.0;

    /**
     * @var float
     */
    protected float $endTime = 0.0;

    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    public function stop(): void
    {
        $this->endTime = microtime(true);
    }

    public function getElapsedTime(): float
    {
        return $this->endTime - $this->startTime;
    }

}
