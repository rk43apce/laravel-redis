<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


# Kafka Setup Instructions

## Prerequisites
- Ensure Docker and Docker Compose are installed on your system.
- Download and set up a `docker-compose.yml` file for Kafka and Zookeeper.
- Start the Kafka and Zookeeper containers using Docker Compose.

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

## Produce Messages
```sh
docker exec -it dock-kafka-1 kafka-console-producer --broker-list localhost:9092 --topic my-topic-payment
```
Type messages and press `Enter` to send them.
Press `CTRL+C` to exit.

## Consume Messages
```sh
docker exec -it dock-kafka-1 kafka-console-consumer --bootstrap-server localhost:9092 --topic my-topic --from-beginning

docker exec -it dock-kafka-1 kafka-console-consumer --bootstrap-server localhost:9092 --topic my-topic-payment --from-beginning
```
Press `CTRL+C` to exit.

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


