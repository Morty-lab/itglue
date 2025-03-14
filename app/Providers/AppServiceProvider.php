<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        set_time_limit(300);
        ini_set('upload_max_filesize', env('UPLOAD_MAX_FILESIZE', '128M'));
        ini_set('post_max_size', env('POST_MAX_SIZE', '128M'));
        ini_set('memory_limit', env('MEMORY_LIMIT', '256M'));
        ini_set('max_execution_time', env('MAX_EXECUTION_TIME', '120'));
        ini_set('max_input_time', env('MAX_INPUT_TIME', '120'));
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
    }
}
