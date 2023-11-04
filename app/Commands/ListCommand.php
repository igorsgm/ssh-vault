<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

class ListCommand extends Command
{
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
    protected $description = 'Displays the list of SSH connections in a list format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');

        $this->step('Hosts');
        $hosts = $sshConfig->hosts();

        $maxNameLength = collect($hosts)->max(fn ($host) => mb_strlen($host->name));

        $lastIndexLength = mb_strlen((string) count($hosts));
        foreach ($hosts as $index => $host) {
            $index++;
            $nameLength = $maxNameLength - mb_strlen($host->name);
            $indexLength = $lastIndexLength - mb_strlen((string) $index);
            $paddingLength = $nameLength + $indexLength + 2;

            $this->line(sprintf(
                '  <fg=gray>[%s]</> <fg=green>%s</>%s%s',
                $index,
                $host->name,
                str_repeat(' ', $paddingLength),
                "<comment>{$host->hostName()}</comment>"
            ));
        }

        return 0;
    }
}
