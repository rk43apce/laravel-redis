<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FlushVisitorLogs extends Command
{
    protected $signature = 'logs:flush-visitor';
    protected $description = 'Flush visitor logs from Redis to per-visitor log files';

    public function handle()
    {


        $this->flushLogs();

        exit();

        $lock = cache()->lock('flush_visitor_logs', 5);

        if ($lock->get()) {
            try {
                $this->flushLogs();
            } finally {
                $lock->release();
            }
        } else {
            $this->info('Another node is handling the flush, skipping this run.');
        }
    }

    protected function flushLogs()
    {
        $keys = Redis::keys('visitor_log:*');

        printf("Found %d visitor log keys in Redis.\n", count($keys));

        $batchSize = 1000;


        foreach ($keys as $key) {
    $visitorId = explode(':', $key)[1];
    $date = date('Y-m-d');

    $dir = storage_path("logs/visitors");
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
            $this->error("Failed to create directory: {$dir}");
            continue;
        }
    }

    $filename = "{$dir}/{$visitorId}_{$date}.log";

    if (file_exists($filename)) {
        $this->info("Appending to {$filename}");
    } else {
        $this->info("Creating {$filename}");
        // Try to create the file explicitly
        if (false === @touch($filename)) {
            $this->error("Failed to create file: {$filename}");
            continue;
        }
    }

    while (true) {

        
        $key = str_replace(config('database.redis.options.prefix'), '', $key);


        $entries = Redis::lrange($key, 0, -1);

        // printf("Fetched %d entries from %s\n", count($entries), $key);
        if (empty($entries)) {

            printf("No more entries to process for %s\n", $key);
            break;
        }

        $logBlock = implode(PHP_EOL, $entries) . PHP_EOL;

        print_r("Writing " . count($entries) . " entries to {$filename}\n");

    

        if (false === @file_put_contents($filename, $logBlock, FILE_APPEND)) {
            $this->error("Failed to write to file: {$filename}");
            break;
        }

        // Redis::ltrim($key, -1);

        Redis::del($key); // This deletes the entire list


    }
}
    }
    

}
