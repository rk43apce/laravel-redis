<?php

return [
    'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),
    'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'laravel-group'),
    'topics' => [
        'ride_updates' => env('KAFKA_TOPIC_RIDE_UPDATES', 'ride_updates'),
    ],
    'auto_commit' => true, // ✅ Ensure this is not null
];
