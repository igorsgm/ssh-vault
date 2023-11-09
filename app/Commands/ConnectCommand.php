<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use Illuminate\Support\Facades\Process;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process as SymfonyProcess;

use function Laravel\Prompts\select;

class ConnectCommand extends Command
{
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:connect {hostOrHostName? : The name of the host or host name to be accessed}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'SSH to a host.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');

        $hostOrHostName = $this->getArgument('hostOrHostName');

        if (! $hostOrHostName) {
            $hostOrHostName = select(
                label: 'Select host to connect:',
                options: $sshConfig->hosts()->mapWithKeys(function (Host $host) {
                    return [
                        $host->getName() => "{$host->getName()} <fg=gray>({$host->hostName()})</>",
                    ];
                })->toArray(),
                scroll: 30,
            );
        }

        /** @var Host $host */
        $host = $sshConfig->findHost($hostOrHostName);

        $command = $host->toSshCommandString();
        $remoteCommand = $host->remoteCommand(true);

        $this->step('Connecting to <comment>'.$host->getName().'</comment>');
        $this->hintStep("And executing: '{$remoteCommand}'");

        Process::forever()->tty(SymfonyProcess::isTtySupported())->run($command);

        return Command::SUCCESS;
    }
}
