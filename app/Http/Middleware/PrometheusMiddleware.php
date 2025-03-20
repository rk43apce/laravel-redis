<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\PrometheusService;
use Illuminate\Http\Request;

class PrometheusMiddleware
{
    protected $prometheus;

    public function __construct(PrometheusService $prometheus)
    {
        $this->prometheus = $prometheus;
    }

    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;

        $this->prometheus->observeRequest($request->path(), $response->status(), $duration);

        return $response;
    }
}
