<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class    SqlListenServiceProvider
 * @package App\Providers
 * @desc    用于数据库SQL语句执行监听
 */
class SqlListenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            $message = sprintf("\nSQL:%s\nRUNTIME:%s\nPARAMS:", $query->sql, $query->time);
            Log::info($message, $query->bindings);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
