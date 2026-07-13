<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Services\SmartCache;

Schedule::call(function () {
    SmartCache::remember('all_users', 3600, function () {
        return User::all();
    }, ['users']);
})->everyTenMinutes();