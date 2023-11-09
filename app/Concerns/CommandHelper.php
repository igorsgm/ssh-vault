<?php

namespace App\Concerns;

use App\Host;
use App\SshConfig\SshConfig;

trait CommandHelper
{
    /**
     * Ensure that the SSH configuration file is present and contains at least one host.
     */
    public function ensureSshConfigFile(): void
    {
        $sshConfig = app(SshConfig::class);
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');
    }

    /**
     * Generate an array of select options for the hosts.
     */
    public function hostsSelectOptions(): array
    {
        return app(SshConfig::class)->hosts()->mapWithKeys(function (Host $host) {
            return [
                $host->getName() => "{$host->getName()} <fg=gray>({$host->hostName()})</>",
            ];
        })->toArray();
    }
}
