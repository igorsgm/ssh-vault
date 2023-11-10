<?php

namespace App\Commands\Hosts;

use App\Concerns\CommandHelper;
use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use Illuminate\Support\Facades\Process;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process as SymfonyProcess;

use function Laravel\Prompts\select;

class ConnectCommand extends Command
{
    use CommandHelper;
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:connect {hostOrHostName? : The name of the host or host name to be accessed}';

    /**
     * {@inheritDoc}
     */
    protected $aliases = [
        'hosts:access',
        'hosts:ssh',
    ];

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '🌐 SSH to a specific host from your config file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->ensureSshConfigFile();

        $hostOrHostName = $this->getArgument('hostOrHostName');

        if (! $hostOrHostName) {
            $hostOrHostName = select(
                label: 'Select host to connect:',
                options: $this->hostsSelectOptions(),
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
