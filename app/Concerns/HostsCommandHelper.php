<?php

namespace App\Concerns;

use App\Host;
use App\SshConfig\SshConfig;
use Illuminate\Support\Collection;

trait HostsCommandHelper
{
    /**
     * Ensure that the SSH configuration file is present and contains at least one host.
     */
    public function ensureSshConfigFile(): void
    {
        $sshConfig = app(SshConfig::class);
        abort_if($sshConfig->isEmpty(), 1, sprintf('No Hosts. To add new: %s add', app_bin()));
    }

    /**
     * Generate an array of select options for the hosts.
     */
    public function hostsSelectOptions(?Collection $hosts = null): array
    {
        $hosts = $hosts ?? app(SshConfig::class)->hosts();

        return $hosts->mapWithKeys(function (Host $host) {
            return [
                $host->getName() => "{$host->getName()} <fg=gray>({$host->hostName()})</>",
            ];
        })->toArray();
    }
}
