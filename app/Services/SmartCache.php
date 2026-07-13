<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SmartCache
{
    public static function remember(string $key, int $ttl, callable $callback, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                return Cache::store('redis')->tags($tags)->remember($key, $ttl, $callback);
            }
            return Cache::store('redis')->remember($key, $ttl, $callback);
        } catch (\Throwable $e) { 
            return Cache::store('file')->remember($key, $ttl, $callback);
        }
    }
}