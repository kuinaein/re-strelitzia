<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\ServiceProvider;
use Log;

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
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format(config('stre.datetime_format'));
        });

        if (config('app.debug')) {
            $this->setupQueryLogger();
        }
    }

    private function setupQueryLogger()
    {
        DB::listen(function ($query): void {
            $sql = $query->sql;
            $count = count($query->bindings);
            for ($i = 0; $i < $count; $i++) {
                $sql = preg_replace('/\\?/', $query->bindings[$i], $sql, 1);
            }
            Log::debug($sql);
        });
    }
}
