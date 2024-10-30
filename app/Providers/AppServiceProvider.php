<?php

namespace Igorsgm\SshVault\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerGitHooks();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Register Git Hooks.
     *
     * This method registers the Git Hooks if the application environment is not production.
     * It also adds the Git Hooks commands to the list of hidden commands.
     */
    private function registerGitHooks(): void
    {
        $gitHooksServiceProvider = \Igorsgm\GitHooks\GitHooksServiceProvider::class;
        // @codeCoverageIgnoreStart
        if (config('app.env') === 'production' || ! class_exists($gitHooksServiceProvider)) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $this->app->register($gitHooksServiceProvider);

        $hiddenCommands = config('commands.hidden', []);

        Config::set('commands.hidden', array_merge($hiddenCommands, [
            \Igorsgm\GitHooks\Console\Commands\RegisterHooks::class,
            \Igorsgm\GitHooks\Console\Commands\PreCommit::class,
            \Igorsgm\GitHooks\Console\Commands\PostCommit::class,
            \Igorsgm\GitHooks\Console\Commands\CommitMessage::class,
            \Igorsgm\GitHooks\Console\Commands\MakeHook::class,
            \Igorsgm\GitHooks\Console\Commands\PrePush::class,
            \Igorsgm\GitHooks\Console\Commands\PrepareCommitMessage::class,
        ]));
    }
}
