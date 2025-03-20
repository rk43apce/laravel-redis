<?php

namespace App\Services;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis as PrometheusRedis;
use Prometheus\RenderTextFormat;
use Illuminate\Support\Facades\Redis; // Laravel Redis Facade

class PrometheusService
{
    protected $registry;

    public function __construct()
    {
        // Ensure Laravel Predis connection works
        try {
            Redis::ping();
        } catch (\Exception $e) {
            throw new \Exception('Predis connection failed: ' . $e->getMessage());
        }

        // Set up Prometheus Redis Storage using Predis
        PrometheusRedis::setDefaultOptions([
            'host' => env('REDIS_HOST', 'redis'), // Use Docker service name
            'port' => env('REDIS_PORT', 6379),
            'timeout' => 2.0, // Optional timeout
            'persistent_connections' => false
        ]);

        $this->registry = new CollectorRegistry(new PrometheusRedis());
    }

    public function getMetrics()
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());

        return response($result, 200)->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }

    public function observeRequest($route, $statusCode, $duration)
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            'laravel', 'http_requests_duration', 'Request duration',
            ['route', 'status_code'], [0.1, 0.5, 1, 2, 5]
        );
        $histogram->observe($duration, [$route, $statusCode]);

        $counter = $this->registry->getOrRegisterCounter(
            'laravel', 'http_requests_total', 'Total number of requests',
            ['route', 'status_code']
        );
        $counter->inc([$route, $statusCode]);
    }
}
