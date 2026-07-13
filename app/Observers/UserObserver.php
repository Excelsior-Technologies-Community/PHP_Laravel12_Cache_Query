<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Exception;

class UserObserver
{
    public function saved(User $user): void
    {
        $this->clearCache();
    }

    public function deleted(User $user): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        try {
            Cache::store('redis')->tags(['users'])->flush();
        } catch (Exception $e) {
            Cache::store('file')->forget('all_users');
        }
    }
}