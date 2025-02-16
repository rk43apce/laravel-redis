<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    /**
     * Set a value in Redis.
     */
    public function setValue(Request $request)
    {
        // Optionally, you can get key/value from request parameters
        Redis::set('my_key', 'Hello from Redis!');
        return response()->json(['message' => 'Value set successfully in Redis']);
    }

    /**
     * Get a value from Redis.
     */
    public function getValue(Request $request)
    {
        $value = Redis::get('my_key');
        return response()->json(['value' => $value]);
    }

    function getConfig() {
        
        $client = Redis::client();
        $config = $client->config('GET', '*');
        return response()->json(['config' => $config]);
    }

    function getKeys() {
        
        // Get all keys
        $keys = Redis::keys('*');

        // Initialize an array to hold key-value pairs
        $data = [];

        // Loop through each key and retrieve its value
        foreach ($keys as $key) {
            // Note: This works if your keys are simple string values.
            // For other data types (e.g., hashes, lists), you'll need different commands.
            $data[$key] = Redis::get($key);
        }

        return response()->json(['data' => $data]);

    }
}
