<?php

namespace App\SshConfig;

use App\Host;

class SshConfigParser
{
    private const SECTION_PATTERN = '/^(\w+)(?:\s*=\s*|\s+)(.+)/m';

    private const HOST_KEYWORD = 'Host';

    /**
     * Parse ssh config content provided as input parameter.
     *
     * @param  string  $fileContent The content of the SSH configuration file.
     * @return Host[] An array of Host objects.
     */
    public function parse(string $fileContent): array
    {
        $lines = explode("\n", $fileContent);
        $hosts = [];
        $currentHost = null;

        foreach ($lines as $line) {
            // Skip empty lines or lines that start with a hash (#) for comments
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            $matches = $this->parseLine($line);

            if (empty($matches)) {
                continue;
            }

            [$_, $key, $value] = $this->parseLine($line);

            if ($key === self::HOST_KEYWORD) {
                $currentHost = new Host($value);
                $hosts[] = $currentHost;
            } else {
                $currentHost->addParameter($key, $value);
            }
        }

        return $hosts;
    }

    /**
     * Parse a line of the SSH configuration.
     *
     * @param  string  $line A line from the SSH configuration file.
     * @return array An array containing the key and value, if found.
     */
    private function parseLine(string $line): array
    {
        preg_match(self::SECTION_PATTERN, trim($line), $matches);

        return $matches;
    }
}
