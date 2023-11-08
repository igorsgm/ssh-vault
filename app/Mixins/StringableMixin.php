<?php

namespace App\Mixins;

use Illuminate\Support\Str;

/** @mixin \Illuminate\Support\Stringable */
class StringableMixin
{
    public function expandedPath(): \Closure
    {
        /**
         * Apply the expandedPath method from StrMixin to the string.
         */
        return function (): static {
            return new static(Str::expandedPath($this->value));
        };
    }

    public function toDirectorySeparator(): \Closure
    {
        /**
         * Apply the toDirectorySeparator method from StrMixin to the string.
         */
        return function (): static {
            // Assuming StrMixin has been already mixed into the Str facade
            return new static(Str::toDirectorySeparator($this->value));
        };
    }
}
