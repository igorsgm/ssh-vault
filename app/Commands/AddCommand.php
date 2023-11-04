<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class AddCommand extends Command
{
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:add';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add new SSH connection';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->step('Add new SSH connection');

        /** @var Host|null $lastHost */
        $lastHost = $sshConfig->lastHost();

        $name = text(
            label: 'Connection name:',
            placeholder: 'beta',
            required: true,
            hint: 'An alias for the connection, to access server via: ssh <name>'
        );

        $hostParams = [];
        $hostParams['HostName'] = text(
            label: 'Host Name:',
            required: true,
            hint: 'Server IP or DNS'
        );

        $hostParams['Port'] = text(
            label: 'Port:',
            default: '22',
            required: true,
            validate: fn ($port) => ! is_numeric($port) ? 'Error: The port must be a number.' : null,
        );

        $hostParams['User'] = text(
            label: 'User Name:',
            default: $lastHost ? $lastHost->user() : '',
            hint: 'Leave empty to use the same username as your local machine',
        );

        $hostParams['IdentityFile'] = text(
            label: 'Identity File Location:',
            default: $lastHost ? $lastHost->identityFile() : '',
            validate: function ($path) {
                $path = str_replace('~', getenv('HOME'), $path);

                return ! empty($path) && ! is_file($path) ? "Error: file not found at $path" : null;
            },
            hint: 'Example: ~/.ssh. Leave empty to use the same identity file as your local machine',
        );

        $hostParams['ForwardAgent'] = confirm(
            label: 'Forward Agent:',
            default: $lastHost ? $lastHost->forwardAgent() : true,
        ) ? 'yes' : 'no';

        $hostParams['RequestTTY'] = confirm(
            label: 'Request TTY:',
            default: $lastHost ? $lastHost->requestTTY() : true,
        ) ? 'yes' : 'no';

        $hostParams['RemoteCommand'] = text(
            label: 'Remote Command:',
            placeholder: $lastHost ? $lastHost->remoteCommand() : '',
            hint: 'Example: cd /var/www/project-name; exec $SHELL',
        );

        $host = new Host($name);
        foreach ($hostParams as $parameter => $value) {
            if (! empty($value)) {
                $host->addParameter($parameter, trim($value));
            }
        }

        $sshConfig->add($host)->sync();

        $this->successfulStep("<comment>$name</comment> SSH connection added successfully");
        $this->hintStep("You should now be able to connect to the server via: ssh $name");

        return 0;
    }
}