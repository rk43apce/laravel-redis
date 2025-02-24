<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;

class KafkaConsumer extends Command
{
    protected $signature = 'kafka:consume';
    protected $description = 'Consume messages from Kafka';

    public function handle()
    {
        Kafka::consumer()
            ->subscribe('my-topic')
            ->withHandler(function (ConsumedMessage $message) { // ✅ Correct type
                $data = $message->getBody();
                
                // Debugging: Log received message
                \Log::info("Received Kafka Message: ", $data);

                // Output message to console
                $this->info("Received Message: " . json_encode($data));
            })
            ->build()
            ->consume();
    }
}
