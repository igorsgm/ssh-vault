<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\multiselect;

class RemoveCommand extends Command
{
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
    protected $description = 'Remove SSH connection';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');

        $hostsToRemove = multiselect(
            label: 'Select hosts to remove:',
            options: $sshConfig->hosts()->map(fn (Host $host) => $host->getName())->toArray(),
            scroll: 30,
            required: true,
        );

        foreach ($hostsToRemove as $hostToRemove) {
            $sshConfig->remove($hostToRemove);
        }

        $sshConfig->sync();

        $this->successfulStep('Removed: <comment>'.implode(', ', $hostsToRemove).'</comment> successfully.');

        return 0;
    }
}
