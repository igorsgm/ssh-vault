<?php

namespace Igorsgm\SshVault\Mixins;

use Closure;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Support\Stringable */
class StringableMixin
{
    public function expandedPath(): Closure
    {
        /**
         * Apply the expandedPath method from StrMixin to the string.
         */
        return function (): static {
            return new static(Str::expandedPath($this->value));
        };
    }

    public function toDirectorySeparator(): Closure
    {
        /**
         * Apply the toDirectorySeparator method from StrMixin to the string.
         */
        return function (): static {
            return new static(Str::toDirectorySeparator($this->value));
        };
    }

    public function unquote(): Closure
    {
        /**
         * Apply the unquote method from StrMixin to the string.
         */
        return function (): static {
            return new static(Str::unquote($this->value));
        };
    }
}
