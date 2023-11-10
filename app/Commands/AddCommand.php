<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class AddCommand extends Command
{
    use InteractsWithIO;

    const DEFAULT_PORT = '22';

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
    protected $description = '🔗 Add a new SSH connection to your config file';

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
            default: self::DEFAULT_PORT,
            validate: fn ($port) => ! empty($port) && ! is_numeric($port) ? 'Error: The port must be a number.' : null,
        );

        if ($hostParams['Port'] === self::DEFAULT_PORT) {
            unset($hostParams['Port']);
        }

        $hostParams['User'] = text(
            label: 'User Name:',
            default: $lastHost ? $lastHost->user() : '',
            hint: 'Leave empty to use the same username as your local machine',
        );

        $hostParams['IdentityFile'] = text(
            label: 'Identity File Location:',
            default: $lastHost ? $lastHost->identityFile() : '',
            validate: function ($path) {
                $path = Str::expandedPath($path);

                return ! empty($path) && ! File::exists($path) ? "Error: file not found at $path" : null;
            },
            hint: 'Example: ~/.ssh. Leave empty to use the same identity file as your local machine',
        );

        $hostParams['ForwardAgent'] = confirm(
            label: 'Forward Agent:',
            default: ! $lastHost || $lastHost->forwardAgent(),
        ) ? 'yes' : 'no';

        $hostParams['AddKeysToAgent'] = confirm(
            label: 'Add Keys To Agent:',
            default: ! $lastHost || $lastHost->addKeysToAgent(),
        ) ? 'yes' : 'no';

        $hostParams['RequestTTY'] = confirm(
            label: 'Request TTY:',
            default: ! $lastHost || $lastHost->requestTTY(),
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

        return Command::SUCCESS;
    }
}
