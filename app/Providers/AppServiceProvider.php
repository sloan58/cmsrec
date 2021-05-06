<?php

namespace App\Providers;

use App\Models\CmsRecording;
use App\Observers\CmsRecordingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CmsRecording::observe(CmsRecordingObserver::class);
    }
}
