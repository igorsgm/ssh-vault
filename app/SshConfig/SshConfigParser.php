<?php

namespace App\SshConfig;

use App\Host;
use Illuminate\Support\Collection;

class SshConfigParser
{
    private const SECTION_PATTERN = '/^(\w+)(?:\s*=\s*|\s+)(.+)/m';

    private const HOST_KEYWORD = 'Host';

    /**
     * Parse ssh config content provided as input parameter.
     *
     * @param  string  $fileContent The content of the SSH configuration file.
     * @return Collection A collection of Host objects.
     */
    public function parse(string $fileContent): Collection
    {
        $lines = explode("\n", $fileContent);
        $hosts = collect();
        $currentHost = null;

        foreach ($lines as $line) {
            // Skip empty lines or lines that start with a hash (#) for comments
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            if ($matches = $this->parseLine($line)) {
                [$key, $value] = $matches;

                if ($key === self::HOST_KEYWORD) {
                    $currentHost = new Host($value);
                    $hosts->push($currentHost);
                } elseif ($currentHost) {
                    $currentHost->addParameter($key, $value);
                }
            }
        }

        return $hosts;
    }

    /**
     * Parse a line of the SSH configuration.
     *
     * @param  string  $line A line from the SSH configuration file.
     * @return array|null An array containing the key and value, if found.
     */
    private function parseLine(string $line): ?array
    {
        if (preg_match(self::SECTION_PATTERN, trim($line), $matches)) {
            // Omit the full match and return only the key and value
            return array_slice($matches, 1, 2);
        }

        return null;
    }
}
