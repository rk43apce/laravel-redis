<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KafkaProducerService;

class KafkaController extends Controller
{
    protected KafkaProducerService $producerService;

    public function __construct(KafkaProducerService $producerService)
    {
        $this->producerService = $producerService;
    }

    public function sendMessage(Request $request)
    {
        $message = [
            'car_id' => $request->input('car_id', 'uber_123'),
            'latitude' => $request->input('latitude', 37.7749),
            'longitude' => $request->input('longitude', -122.4194),
            'timestamp' => now()->toDateTimeString(),
        ];

        return response()->json([
            'status' => $this->producerService->sendMessage($message)
        ]);
    }
}
