<?php

namespace App\Providers;

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
        if (config('app.env') === 'production') {
            return;
        }

        $this->app->register(\Igorsgm\GitHooks\GitHooksServiceProvider::class);

        $hiddenCommands = config('commands.hidden', []);

        config('commands.hidden', array_merge($hiddenCommands, [
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
