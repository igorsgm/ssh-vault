<?php

// @codeCoverageIgnoreStart
use App\Enums\OperatingSystem;
use Illuminate\Support\Str;

if (! function_exists('user')) {
    /**
     * Get the current user being executed.
     */
    function user(): string
    {
        return getenv('SUDO_USER') ?: getenv('USER');
    }
}

if (! function_exists('home')) {
    /**
     * Get the home directory based on OS.
     */
    function home(): string
    {
        if (config('app.env') == 'testing') {
            return base_path('tests');
        }

        return getenv('USERPROFILE') ?: getenv('HOME');
    }
}

if (! function_exists('os')) {
    /**
     * Get the current Operating System being used by the CLI.
     */
    function os(): OperatingSystem
    {
        return OperatingSystem::current();
    }
}

if (! function_exists('app_bin')) {
    /**
     * Retrieve the first bin script specified in composer.json.
     *
     * This function assumes that the 'bin' array is always set in composer.json
     * and returns the first element. It will return `null` if the bin array is not
     * set or is empty.
     */
    function app_bin(): ?string
    {
        $composerContent = file_get_contents(base_path('composer.json'));
        $composerContent = json_decode($composerContent, true);

        // Get the binary name from the 'bin' array in composer.json, assuming it always exists
        $appBin = data_get($composerContent, 'bin.0');

        return Str::afterLast($appBin, '/') ?: null;
    }
}

// @codeCoverageIgnoreEnd
