<?php

namespace Igorsgm\SshVault\Providers;

use Igorsgm\SshVault\Mixins\StringableMixin;
use Igorsgm\SshVault\Mixins\StrMixin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

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
        Stringable::mixin(new StringableMixin());
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
