<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Ssh Host representation
 */
class Host
{
    /**
     * The name of the SSH host.
     */
    private string $name;

    /**
     * The configuration parameters for the SSH host.
     * It stores key-value pairs representing SSH configuration directives.
     */
    private array $config;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add new parameter to ssh config
     */
    public function addParameter($parameter, $value): self
    {
        $this->config[strtolower($parameter)] = Str::unquote($value);

        return $this;
    }

    /**
     * Remove parameter from ssh config
     */
    public function removeParameter($parameter): self
    {
        $this->config = Arr::except($this->config, [$parameter, strtolower($parameter)]);

        return $this;
    }

    /**
     * Get parameter from config
     */
    public function getParameter($parameter)
    {
        return $this->config[$parameter] ?? ($this->config[strtolower($parameter)] ?? null);
    }

    /**
     * Get HostName parameter from config
     */
    public function hostName()
    {
        return $this->getParameter('hostname');
    }

    /**
     * Get Port parameter from config
     */
    public function port()
    {
        return $this->getParameter('port');
    }

    /**
     * Get User parameter from config
     */
    public function user()
    {
        return $this->getParameter('user');
    }

    /**
     * Get IdentityFile parameter from config
     */
    public function identityFile()
    {
        return $this->getParameter('identityfile');
    }

    /**
     * Determine if the ForwardAgent parameter is truthy.
     */
    public function forwardAgent(): bool
    {
        $forwardAgent = $this->getParameter('forwardagent');

        return Str::isTruthy($forwardAgent);
    }

    /**
     * Determine if the RequestTTY parameter is truthy.
     */
    public function requestTTY(): bool
    {
        $requestTTY = $this->getParameter('requesttty');

        return Str::isTruthy($requestTTY);
    }

    /**
     * Get RemoteCommand parameter from config
     *
     * @return string|null The RemoteCommand parameter value or null if not set
     */
    public function remoteCommand(): ?string
    {
        return $this->getParameter('remotecommand');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        $result = "Host {$this->name}\n";
        foreach ($this->config as $key => $value) {
            $value = is_bool($value) ? ($value ? 'yes' : 'no') : $value;
            $result .= "  $key $value\n";
        }

        return $result;
    }
}
