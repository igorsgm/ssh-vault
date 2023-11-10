<?php

namespace App\Commands;

use App\Concerns\CommandHelper;
use App\Concerns\InteractsWithIO;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

class RawCommand extends Command
{
    use CommandHelper;
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:raw';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '📄 Displays all SSH connections in a raw format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->ensureSshConfigFile();

        $this->line($sshConfig->content());

        return Command::SUCCESS;
    }
}
