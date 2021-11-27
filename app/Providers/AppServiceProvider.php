<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
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
        Collection::macro('setAtr', function() {
            return $this->map(function($model){
                $model->end_flag = $model->end_flag;
                $model->relese_flag = $model->relese_flag;
                $model->start_flag = $model->start_flag;
                return $model;
            });
        });
    }
}
