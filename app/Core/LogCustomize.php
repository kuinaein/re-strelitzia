<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use Monolog\Logger as MonologLogger;
use Illuminate\Log\Logger as LaravelLogger;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

class LogCustomize
{
    public function __invoke(LaravelLogger $logger)
    {
        $monologLogger = $logger->getLogger();
        if (!($monologLogger instanceof MonologLogger)) {
            throw new Exception();
        }

        // クラス名等を extra フィールドに挿入するプロセッサを生成
        $ip = new IntrospectionProcessor(MonologLogger::DEBUG, ['Illuminate\\']);
        // IPアドレス等を extra フィールドに挿入するプロセッサを生成
        $wp = new WebProcessor();
        foreach ($monologLogger->getHandlers() as $handler) {
            if (!($handler instanceof ProcessableHandlerInterface)) {
                continue;
            }
            $handler->pushProcessor($ip);
            $handler->pushProcessor($wp);
        }
    }
}
