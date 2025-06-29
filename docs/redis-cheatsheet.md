# Redis Command Reference for Laravel

This file provides a quick reference to commonly used Redis commands and their Laravel usage.

---

## 🔧 Redis Setup in Laravel

**Configuration file:** `config/database.php`

```php
'redis' => [
    'client' => env('REDIS_CLIENT', 'predis'),
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
],


Common Redis Commands in Laravel
🔹 Set a key
--------------------------------------------------------------------------------------------------------
Redis::set('key', 'value');
🔹 Get a key
--------------------------------------------------------------------------------------------------------
$value = Redis::get('key');
🔹 Set with expiry (TTL in seconds)
--------------------------------------------------------------------------------------------------------
Redis::setex('key', 60, 'value'); // OR
Redis::set('key', 'value', 'EX', 60);
🔹 Increment/Decrement
--------------------------------------------------------------------------------------------------------
Redis::incr('counter');
Redis::decr('counter');
🔹 Delete a key
--------------------------------------------------------------------------------------------------------
Redis::del('key');
🔹 Check if a key exists
--------------------------------------------------------------------------------------------------------
Redis::exists('key');
🧺 Working with Lists
Add to list
--------------------------------------------------------------------------------------------------------
Redis::rpush('list', 'item1');
Get list items
--------------------------------------------------------------------------------------------------------
Redis::lrange('list', 0, -1);
Pop from list
--------------------------------------------------------------------------------------------------------
Redis::lpop('list');
Redis::rpop('list');
🗂 Working with Hashes
Set hash fields
--------------------------------------------------------------------------------------------------------
Redis::hset('user:1000', 'name', 'John');
Redis::hmset('user:1000', ['email' => 'john@example.com', 'status' => 'active']);
Get hash field(s)
--------------------------------------------------------------------------------------------------------
Redis::hget('user:1000', 'name');
Redis::hgetall('user:1000');
📅 Key Expiry
--------------------------------------------------------------------------------------------------------
Redis::expire('key', 60); // 60 seconds
Redis::ttl('key'); // Get remaining time
📛 Namespacing keys
--------------------------------------------------------------------------------------------------------
$key = "user:{$userId}:session";
Redis::set($key, $sessionData);
🎯 Pub/Sub (Publish/Subscribe)
Publish
--------------------------------------------------------------------------------------------------------
Redis::publish('channel', json_encode(['event' => 'order.placed']));
Subscribe (Usually via artisan command or listener)
--------------------------------------------------------------------------------------------------------
Redis::subscribe(['channel'], function ($message) {
    echo $message;
});
🧪 Laravel Queues with Redis
Set queue connection in .env:

env---------------------------------------------------------------------------------
QUEUE_CONNECTION=redis
Use a queue job:

--------------------------------------------------------------------------------------------------------
dispatch(new SendEmailJob($user));
Worker:

-------------------------------------------------------------------
php artisan queue:work redis
🧹 Flush DB (Be careful!)
--------------------------------------------------------------------------------------------------------
Redis::flushdb(); // flushes the current db
Redis::flushall(); // flushes all dbs
📂 Laravel Cache with Redis
--------------------------------------------------------------------------------------------------------
Cache::store('redis')->put('key', 'value', 60); // 60 seconds
Cache::store('redis')->get('key');
📚 Useful CLI Commands (For Devs)
-------------------------------------------------------------------
redis-cli
> KEYS *               # List all keys
> TTL key              # Time to live
> INFO                 # Redis server info
> MONITOR              # Watch commands in real-time


---------------------------------------------------------------


Docker Redis Commands
🔸 Run Redis container
---------------------------------------------------------------------------------
docker run --name redis-server -p 6379:6379 -d redis
--name redis-server: Names the container

-p 6379:6379: Maps Redis port to host

-d: Runs in detached mode

🔸 Run Redis with persistence (mount volume)
---------------------------------------------------------------------------------
docker run --name redis-server -p 6379:6379 -v redis-data:/data -d redis
🔸 Connect to Redis CLI inside container
---------------------------------------------------------------------------------
docker exec -it redis-server redis-cli
Or if the CLI is not included in your local machine:

---------------------------------------------------------------------------------
docker run -it --rm redis redis-cli -h <container-ip or host>
🔸 Run a command in Redis directly from host terminal
---------------------------------------------------------------------------------
docker exec -it redis-server redis-cli set mykey myvalue
docker exec -it redis-server redis-cli get mykey
🔸 View logs
---------------------------------------------------------------------------------
docker logs redis-server
🔸 Restart Redis container
---------------------------------------------------------------------------------
docker restart redis-server
🔸 Stop Redis container
---------------------------------------------------------------------------------
docker stop redis-server
🔸 Start Redis container
---------------------------------------------------------------------------------
docker start redis-server
🔸 Remove Redis container
---------------------------------------------------------------------------------
docker rm -f redis-server
🔸 Redis with custom config
---------------------------------------------------------------------------------
docker run --name redis-server -v /path/to/redis.conf:/usr/local/etc/redis/redis.conf \
-p 6379:6379 -d redis redis-server /usr/local/etc/redis/redis.conf
🔸 Check Redis stats
---------------------------------------------------------------------------------
docker exec -it redis-server redis-cli info