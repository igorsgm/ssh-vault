<?php

namespace Igorsgm\SshVault\Commands\Hosts;

use Igorsgm\SshVault\Concerns\HostsCommandHelper;
use Igorsgm\SshVault\Concerns\InteractsWithIO;
use Igorsgm\SshVault\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\multiselect;

class RemoveCommand extends Command
{
    use HostsCommandHelper;
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:remove';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '🚮 Remove SSH connection from config file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->ensureSshConfigFile();

        $hostsToRemove = multiselect(
            label: 'Select hosts to remove:',
            options: $this->hostsSelectOptions(),
            scroll: 30,
            required: true,
        );

        foreach ($hostsToRemove as $hostToRemove) {
            $sshConfig->remove($hostToRemove);
        }

        $sshConfig->sync();

        $this->successfulStep('Removed: <comment>'.implode(', ', $hostsToRemove).'</comment> successfully.');

        return Command::SUCCESS;
    }
}
