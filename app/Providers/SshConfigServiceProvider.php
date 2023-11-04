<?php

namespace App\Providers;

use App\SshConfig\SshConfig;
use Illuminate\Support\ServiceProvider;

/**
 * Provider copied from Laravel Forge CLI
 *
 * @see https://github.com/laravel/forge-cli/blob/master/app/Providers/ConfigServiceProvider.php
 */
class SshConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SshConfig::class, function () {
            $sshConfig = new SshConfig();
            $sshConfig->load();

            return $sshConfig;
        });
    }
}
