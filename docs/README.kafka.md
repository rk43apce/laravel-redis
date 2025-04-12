# Kafka Setup Instructions

## Prerequisites
- Ensure Docker and Docker Compose are installed on your system.
- Download and set up a `docker-compose.yml` file for Kafka and Zookeeper.
- Start the Kafka and Zookeeper containers using Docker Compose.
- Install `mateusjunges/laravel-kafka` package in your Laravel application.

## Add `docker-compose.yml` to Laravel
Place the following `docker-compose.yml` file in the root directory of your Laravel project:

```yaml
version: '3.8'

services:
  zookeeper:
    image: confluentinc/cp-zookeeper:latest
    container_name: zookeeper
    environment:
      ZOOKEEPER_CLIENT_PORT: 2181
      ZOOKEEPER_TICK_TIME: 2000
    networks:
      - kafka_network

  kafka:
    image: confluentinc/cp-kafka:latest
    container_name: kafka
    depends_on:
      - zookeeper
    ports:
      - "9092:9092"
    environment:
      KAFKA_BROKER_ID: 1
      KAFKA_ZOOKEEPER_CONNECT: zookeeper:2181
      KAFKA_ADVERTISED_LISTENERS: PLAINTEXT://kafka:9092
      KAFKA_LISTENERS: PLAINTEXT://0.0.0.0:9092
      KAFKA_OFFSETS_TOPIC_REPLICATION_FACTOR: 1
    networks:
      - kafka_network

networks:
  kafka_network:
    driver: bridge
```

## Configure Kafka in Laravel
### Install Laravel Kafka Package
```sh
composer require mateusjunges/laravel-kafka
```

### Publish Configuration
```sh
php artisan vendor:publish --provider="Junges\Kafka\KafkaServiceProvider"
```

### Update `.env` File
```ini
KAFKA_BROKERS=kafka:9092
```

## Start Kafka with Docker
```sh
docker-compose up -d
```

## Verify Kafka is Running
Check running containers:
```sh
docker ps
```
Ensure Kafka and Zookeeper are listed as running.

## Create Topics
```sh
docker exec -it kafka kafka-topics --create --topic my-topic --bootstrap-server localhost:9092 --replication-factor 1 --partitions 1

docker exec -it dock-kafka-1 kafka-topics --create --topic my-topic --bootstrap-server localhost:9092 --replication-factor 1 --partitions 1

docker exec -it dock-kafka-1 kafka-topics --create --topic my-topic-payment --bootstrap-server localhost:9092 --replication-factor 1 --partitions 1
```

## List Topics
```sh
docker exec -it dock-kafka-1 kafka-topics --list --bootstrap-server localhost:9092
```

## Describe a Topic
```sh
docker exec -it dock-kafka-1 kafka-topics --describe --topic my-topic --bootstrap-server localhost:9092
```

## Produce Messages in Laravel
Create a Kafka producer in `app/Services/KafkaProducer.php`:

```php
use Junges\Kafka\Facades\Kafka;

class KafkaProducer
{
    public function sendMessage($topic, $message)
    {
        Kafka::publishOn($topic)
            ->withBodyKey('data', $message)
            ->send();
    }
}
```

## Consume Messages in Laravel
Create a Kafka consumer in `app/Console/Commands/KafkaConsumer.php`:

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Consumers\ConsumerBuilder;

class KafkaConsumer extends Command
{
    protected $signature = 'kafka:consume';
    protected $description = 'Consume messages from Kafka topics';

    public function handle()
    {
        ConsumerBuilder::create()
            ->withBrokers(env('KAFKA_BROKERS'))
            ->subscribe('my-topic')
            ->withHandler(function ($message) {
                \Log::info("Kafka Message: ", (array)$message);
            })
            ->build()
            ->consume();
    }
}
```

### Run the Consumer
```sh
php artisan kafka:consume
```

## Stop Kafka and Zookeeper
To stop containers:
```sh
docker-compose down
```

## Restart Kafka
```sh
docker-compose restart
```

## Cleanup
To remove all containers and volumes:
```sh
docker-compose down -v
```

## Troubleshooting
### Check Kafka Logs
```sh
docker logs dock-kafka-1
```

### Ensure Broker is Running
```sh
docker exec -it dock-kafka-1 kafka-broker-api-versions --bootstrap-server localhost:9092
```
If Kafka is not running, restart it using Docker Compose.

