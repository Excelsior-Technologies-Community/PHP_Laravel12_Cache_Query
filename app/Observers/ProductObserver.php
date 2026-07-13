<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function saved(Product $product): void
    {
        $this->clearCache();
    }

    public function deleted(Product $product): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        try {
            Cache::store('redis')->tags(['products'])->flush();
        } catch (\Throwable $e) {
            Cache::store('file')->flush();
        }
    }
}