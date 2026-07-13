<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CacheMonitor
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        DB::enableQueryLog();

        $response = $next($request);

        $queries = DB::getQueryLog();
        $queryCount = count($queries);
        $executionTime = microtime(true) - $startTime;

        $response->headers->set('X-Database-Queries', $queryCount);
        $response->headers->set('X-Execution-Time-Ms', round($executionTime * 1000, 2));
        $response->headers->set('X-Cache-Status', $queryCount === 0 ? 'HIT' : 'MISS');

        return $response;
    }
}