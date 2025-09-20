<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Modules\Setting\App\Models\Setting;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            $settings = Setting::all()->pluck('option_value', 'option_key')->toArray();
            $this->app->instance('settings', $settings);
            foreach ($settings as $key => $value) {
                Config::set('settings.' . $key, $value);
            }
        }
    }
}
