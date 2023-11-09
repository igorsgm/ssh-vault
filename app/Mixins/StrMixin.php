<?php

namespace App\Mixins;

use Closure;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    public function expandedPath(): Closure
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

    public function toDirectorySeparator(): Closure
    {
        /**
         * Convert all slashes to the system's directory separator.
         *
         * @param  string  $path  The path to transform.
         * @return string
         */
        return function (string $path): string {
            return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        };
    }

    /**
     * Determine if the given value is truthy.
     */
    public function isTruthy(): Closure
    {
        return function (mixed $value): bool {
            return in_array(strtolower($value), ['yes', 'true'], true) || filter_var($value, FILTER_VALIDATE_BOOLEAN);
        };
    }

    /**
     * Unquote an optionally double-quoted string.
     */
    public function unquote(): Closure
    {
        return function (?string $string): ?string {
            if (Str::startsWith($string, '"') && Str::endsWith($string, '"')) {
                return substr($string, 1, -1);
            }

            return $string;
        };
    }
}
