<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $level;
    public $context;

    public function __construct($message, $context = [])
    {
         $this->message = $message;
        $this->context = $context;
    }

    public function handle()


    {


        $userId="11111";

            $logPath = storage_path("logs/{$userId}.log");

        // Create a simple Monolog logger for this user
        $logger = new Logger("user-{$userId}");
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));

        // Write the log
        $logger->info( json_encode(['message' =>   $this->message, 'context' => $this->context]) );



        // \Log::info($this->message, ['request' => $request]);

        // Log the message using the specified level
        // You can use Laravel's built-in logging methods
        // or any other logging library you prefer.
        // For example, using Laravel's Log facade:
        // \Log::log($this->level, $this->message);
        // Or using a specific channel:
        // \Log::channel('async')->log($this->level, $this->message);
        // For example, if you want to log to a specific channel:
        // \Log::channel('async')->log($this->level, $this->message);
        // For example, if you want to log to a specific channel:

            // print_r($this->message);
            // print_r($this->level);
            // print_r($this->context);

            // exit();
            

             // Access the 'request' parameter from the context
        // $request = $this->context['request'] ?? null;

        // Log the message with the request data
        // \Log::channel('async')->log($this->message, ['request' => $request]);

        // \Log::channel('async')->info($this->message, ['request' => $request]);

        // \Log::channel('async')->log('info', $this->message, ['request' => $request]);

    }
}
