<?php

namespace App\SshConfig;

use App\Host;
use Illuminate\Support\Collection;

class SshConfig
{
    private SshConfigParser $parser;

    private Collection $hosts;

    public function __construct()
    {
        $this->parser = new SshConfigParser();
        $this->hosts = collect();
    }

    /**
     * Load hosts from the ssh config file.
     */
    public function load(): Collection
    {
        $configFilePath = $this->configFilePath();
        $this->hosts = file_exists($configFilePath) ? $this->parser->parse($this->content()) : collect();

        return $this->hosts;
    }

    /**
     * Write new hosts to the ssh config file.
     */
    public function sync(): self
    {
        $result = $this->hosts->reduce(function ($carry, $host) {
            return $carry.$host."\n";
        }, '');

        file_put_contents($this->configFilePath(), $result);

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
        $this->hosts = $this->hosts->reject(fn (Host $host) => $host->getName() === $name);

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
        return file_get_contents($this->configFilePath());
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
    private function configFilePath(): string
    {
        return $this->homeFolderPath().'/.ssh/config';
    }

    /**
     * Get the path to the home folder.
     */
    private function homeFolderPath(): string
    {
        return rtrim(getenv('HOME'), '/');
    }
}
