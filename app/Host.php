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

    /**
     * @var string The bash command to execute on the remote host.
     */
    private string $bashCommand;

    public function __construct($name)
    {
        $this->name = $name;
        $this->setBashCommand('bash -s');
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
     * Determine if the AddKeysToAgent parameter is truthy.
     */
    public function addKeysToAgent(): bool
    {
        $forwardAgent = $this->getParameter('addkeystoagent');

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
     * Get the remote command to execute.
     *
     * @param  bool  $addBash Whether to add "bash -s" at the end of the remote command.
     */
    public function remoteCommand(bool $addBash = false): ?string
    {
        $remoteCommand = $this->getParameter('remotecommand');
        $remoteCommand = Str::of($remoteCommand ?? '')->unquote()->trim()->toString() ?: null;

        if ($addBash) {
            $bashCommand = $this->bashCommand();
            $remoteCommand = $remoteCommand ? "$remoteCommand && $bashCommand" : "$bashCommand";
        }

        return $remoteCommand;
    }

    /**
     * Get the name of the Host object
     */
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

    /**
     * Get the Bash command string.
     */
    public function bashCommand(): string
    {
        return $this->bashCommand;
    }

    /**
     * Set the bash command for the Host
     */
    public function setBashCommand(string $bashCommand): self
    {
        $this->bashCommand = $bashCommand;

        return $this;
    }

    /**
     * Convert the SSH connection information to a SSH command string.
     *
     * @param  bool  $addBash Whether to add the remote command wrapped in a Bash shell (default: true)
     */
    public function toSshCommandString(bool $addBash = true): string
    {
        $command = [
            "ssh {$this->user()}@{$this->hostName()}",
            $this->port() ? "-p {$this->port()}" : null,
            $this->identityFile() ? "-i {$this->identityFile()}" : null,
            $this->forwardAgent() ? '-A' : null,
            $this->addKeysToAgent() ? '-o AddKeysToAgent=yes' : null,
            $this->requestTTY() ? '-tt' : null,
            $this->remoteCommand($addBash) ? " '{$this->remoteCommand($addBash)}'" : null,
        ];

        return implode(' ', array_filter($command));
    }
}
