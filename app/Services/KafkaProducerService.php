<?php

namespace App\Services;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaProducerService
{
    public function sendMessage(array $data)
    {
        $message = new Message(
            headers: ['event' => 'location_update'],
            body: $data
        );

        Kafka::publish()
            ->onTopic('my-topic')
            ->withMessage($message)
            ->send();

        return "Message sent!";
    }
}
