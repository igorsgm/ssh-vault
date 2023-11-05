<?php

namespace App;

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
    public function addParameter($name, $value): self
    {
        $this->config[$name] = $value;

        return $this;
    }

    /**
     * Get parameter from config
     */
    public function getParameter($parameter)
    {
        return $this->config[$parameter] ?? null;
    }

    /**
     * Get HostName parameter from config
     */
    public function hostName()
    {
        return $this->getParameter('HostName');
    }

    /**
     * Get Port parameter from config
     */
    public function port()
    {
        return $this->getParameter('Port');
    }

    /**
     * Get User parameter from config
     */
    public function user()
    {
        return $this->getParameter('User');
    }

    /**
     * Get IdentityFile parameter from config
     */
    public function identityFile()
    {
        return $this->getParameter('IdentityFile');
    }

    /**
     * Determine if the ForwardAgent parameter is truthy.
     */
    public function forwardAgent(): bool
    {
        $forwardAgent = $this->getParameter('ForwardAgent');

        return $this->isTruthy($forwardAgent);
    }

    /**
     * Determine if the RequestTTY parameter is truthy.
     */
    public function requestTTY(): bool
    {
        $requestTTY = $this->getParameter('RequestTTY');

        return $this->isTruthy($requestTTY);
    }

    /**
     * Get RemoteCommand parameter from config
     *
     * @return string|null The RemoteCommand parameter value or null if not set
     */
    public function remoteCommand(): ?string
    {
        return $this->getParameter('RemoteCommand');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        $result = "Host {$this->name}\n";
        foreach ($this->config as $key => $value) {
            $result .= "  $key $value\n";
        }

        return $result;
    }

    private function isTruthy($value): bool
    {
        return in_array($value, ['yes', 'Yes', 'YES', 'true', 'True', 'TRUE', true]);
    }
}
