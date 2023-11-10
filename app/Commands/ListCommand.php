<?php

namespace App\Commands;

use App\Concerns\CommandHelper;
use App\Concerns\InteractsWithIO;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

class ListCommand extends Command
{
    use CommandHelper;
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '📋 Displays all SSH connections in a list format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->ensureSshConfigFile();

        $this->step('Hosts');
        $hosts = $sshConfig->hosts();

        $maxNameLength = $hosts->max(fn ($host) => mb_strlen($host->getName()));

        $lastIndexLength = mb_strlen((string) $hosts->count());
        foreach ($hosts as $index => $host) {
            $index++;
            $nameLength = $maxNameLength - mb_strlen($host->getName());
            $indexLength = $lastIndexLength - mb_strlen((string) $index);
            $paddingLength = $nameLength + $indexLength + 2;

            $this->line(sprintf(
                '  <fg=gray>[%s]</> <fg=green>%s</>%s%s',
                $index,
                $host->getName(),
                str_repeat(' ', $paddingLength),
                "<comment>{$host->hostName()}</comment>"
            ));
        }

        return Command::SUCCESS;
    }
}
