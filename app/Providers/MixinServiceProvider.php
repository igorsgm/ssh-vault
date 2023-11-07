<?php

namespace App\Providers;

use App\Mixins\StrMixin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MixinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Str::mixin(new StrMixin());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
