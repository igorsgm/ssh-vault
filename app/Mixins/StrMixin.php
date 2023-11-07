<?php

namespace App\Mixins;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    public function expandedPath(): \Closure
    {
        /**
         * Replace a leading tilde with the user's home directory.
         *
         * @param  string  $path  The path to expand.
         * @return string
         */
        return function (string $path): string {
            return str_replace('~', home(), $path);
        };
    }

    public function toDirectorySeparator(): \Closure
    {
        /**
         * Convert all forward slashes to the system's directory separator.
         *
         * @param  string  $path  The path to transform.
         * @return string
         */
        return function (string $path): string {
            return str_replace('/', DIRECTORY_SEPARATOR, $path);
        };
    }
}
