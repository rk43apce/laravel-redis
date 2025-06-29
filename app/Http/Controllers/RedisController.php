<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Jobs\LogMessage;
use Illuminate\Support\Facades\Log;
class RedisController extends Controller
{
    /**
     * Set a value in Redis.
     */
    public function setValue(Request $request)
    {   




        exit();

        // Log::dispatch('User signed in',['request' => $request->all()]);
        // Log::info('User signed in', ['request' => $request->all()]);

                LogMessage::dispatch('User signed in',['request' => $request->all()]);


//                 session()->put('user_id', "123456");
// session()->save();


// $sessionId = session()->getId();



// Redis::expire($sessionId, 36); 

//


//         session()->put('user_id', "123456");
// session()->save();

// $sessionId = session()->getId();
// $sessionKey = "laravel:session:$sessionId";


//         Redis::expire($sessionKey, 3600); 

//         session()->save();

// $ttl = Redis::ttl("$sessionKey");


        // LogMessage::dispatch('User signed in',['request' => $request->all()]);

        // Optionally, you can get key/value from request parameters
        // Redis::set('my_key', 'Hello from Redis!');
        // return response()->json(['message' => 'Value set successfully in Redis']);

        // return response()->json(['message' => "Session expires in $ttl seconds"]);
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
        
        // $client = Redis::client();
        // $config = $client->config('GET', '*');
        
        for ($i=0; $i < 100; $i++)
        { 
             $product_id   = rand(1000, 9999);

            \Log::info('User viewed product', [
                'product_id' => $product_id,
                'category' => 'shoes'
            ]);
            
            print_r("User viewed product: $product_id\n");
        }

        return response()->json(['product_id' => rand()]);
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
