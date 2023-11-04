<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

class RawCommand extends Command
{
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
    protected $description = 'Displays the raw SSH config content';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');

        $this->line($sshConfig->content());

        return 0;
    }
}
