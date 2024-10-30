<?php

namespace App\SshConfig;

use App\Host;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SshConfig
{
    private Collection $hosts;

    public function __construct()
    {
        $this->hosts = collect();
    }

    /**
     * Load hosts from the ssh config file.
     */
    public function load(): self
    {
        $configFilePath = $this->configFilePath();
        $this->hosts = File::exists($configFilePath) ? SshConfigParser::parse($configFilePath) : collect();

        return $this;
    }

    /**
     * Write new hosts to the ssh config file.
     */
    public function sync(): self
    {
        $result = $this->hosts->reduce(function ($carry, $host) {
            return $carry.$host."\n";
        }, '');

        File::put($this->configFilePath(), $result);

        return $this;
    }

    /**
     * Add a new host to the ssh config.
     */
    public function add(Host $host): self
    {
        $this->hosts->push($host);

        return $this;
    }

    /**
     * Remove a host by name of the SSH host
     */
    public function remove(string $name): self
    {
        $this->hosts = $this->hosts->reject(
            fn (Host $host) => $host->getName() === $name || $host->hostName() === $name
        );

        return $this;
    }

    /**
     * Get the collection of hosts.
     */
    public function hosts(): Collection
    {
        return $this->hosts;
    }

    /**
     * Finds a host by its name or hostname.
     *
     * @param  string  $hostOrHostName  The name or hostname of the host to find.
     */
    public function findHost(string $hostOrHostName): ?Host
    {
        return $this->hosts->first(
            fn (Host $host) => $host->getName() === $hostOrHostName || $host->hostName() === $hostOrHostName
        );
    }

    /**
     * Check if the ssh config is empty.
     */
    public function isEmpty(): bool
    {
        return $this->hosts->isEmpty();
    }

    /**
     * Get the content of the config file.
     */
    public function content(): string
    {
        return File::get($this->configFilePath());
    }

    /**
     * Get the last host of the collection.
     */
    public function lastHost(): ?Host
    {
        return $this->hosts->last();
    }

    /**
     * Get the path to the ssh config file.
     */
    public function configFilePath(): string
    {
        return Str::of(config('app.ssh-config-path'))
            ->rtrim('/')
            ->expandedPath()
            ->toDirectorySeparator()
            ->toString();
    }

    /**
     * Returns the default file path for the SSH identity file.
     */
    public function defaultIdentityFilePath(): string
    {
        return Str::of(config('app.ssh-config-path'))
            ->rtrim('/')
            ->append('/id_rsa')
            ->expandedPath()
            ->toDirectorySeparator()
            ->toString();
    }
}
