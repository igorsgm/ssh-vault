<?php

namespace App\Enums;

enum OperatingSystem: string
{
    case MACOS = 'Darwin';
    case LINUX = 'Linux';
    case WINDOWS = 'Windows';

    public static function current(): self
    {
        return match (PHP_OS_FAMILY) {
            self::MACOS->value => self::MACOS,
            self::WINDOWS->value => self::WINDOWS,
            self::LINUX->value => self::LINUX,
            default => throw new \UnexpectedValueException('Unsupported OS: '.PHP_OS_FAMILY),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::LINUX => '🐧',
            self::WINDOWS => '🪟',
            self::MACOS => '🍎',
        };
    }

    public function isCurrentOS(): bool
    {
        return $this->value === PHP_OS_FAMILY;
    }

    public function isMacOS(): bool
    {
        return $this === self::MACOS;
    }

    public function isLinux(): bool
    {
        return $this === self::LINUX;
    }

    public function isWindows(): bool
    {
        return $this === self::WINDOWS;
    }

    public function isUnix(): bool
    {
        return $this->isLinux() || $this->isMacOS();
    }
}
