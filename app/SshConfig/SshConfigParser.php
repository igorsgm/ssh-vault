<?php

namespace Igorsgm\SshVault\SshConfig;

use Igorsgm\SshVault\Host;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SshConfigParser
{
    private const SECTION_PATTERN = '/^\s*(\S+)\s*=(.*)$/';

    /**
     * Parse the given configuration file.
     */
    public static function parse($filePath): Collection
    {
        return static::parseString(File::get($filePath));
    }

    /**
     * Parse the given configuration string.
     */
    public static function parseString(string $fileContent): Collection
    {
        $hosts = collect();
        $currentHost = null;

        foreach (explode(PHP_EOL, $fileContent) as $line) {
            $line = trim($line);

            if ($line == '' || Str::startsWith($line, '#')) {
                continue;
            }

            // Keys and values may get separated via an equals, so we'll parse them both
            // out here and hang onto their values. We will also lower case this keys
            // and unquotes the values, so they are properly formatted for next use.
            if (preg_match(self::SECTION_PATTERN, $line, $match)) {
                $key = strtolower($match[1]);

                $value = Str::unquote($match[2]);
            }

            // Keys and values may also get separated via a space, so we will parse them
            // out here and hang onto their values. We will also lower case this keys
            // and unquotes the values, so they are properly formatted for next use.
            else {
                $segments = preg_split('/\s+/', $line, 2);

                $key = strtolower($segments[0]);

                $value = Str::unquote($segments[1] ?? '');
            }

            // The configuration file contains sections separated by Host and / or Match
            // specifications. Therefore, if we come across a Host keyword we start a
            // new group. If it's a Match we ignore declarations until next 'Host'.
            if ($key === 'host') {

                $currentHost = new Host($value);
                $hosts->push($currentHost);
            } elseif ($currentHost) {
                $currentHost->addParameter($key, $value);
            }
        }

        return $hosts;
    }
}
