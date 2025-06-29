<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Redis;

class RedisVisitorLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        // $traceId = request()->attributes->get('trace_id') ?? 'unknown';

         $traceId = 615372; // For testing purposes, replace with actual trace ID logic

        $logKey = "visitor_log:{$traceId}";

        $entry = json_encode([
            'timestamp' => $record->datetime->format('Y-m-d H:i:s'),
            'level'     => $record->level->getName(),
            'message'   => $record->message,
            'context'   => $record->context,
            'extra'     => $record->extra,
        ]);

        Redis::rpush($logKey, $entry);


    }
}
