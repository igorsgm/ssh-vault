<?php

use App\Commands\ListCommand;
use App\Commands\RawCommand;
use App\Commands\RemoveCommand;
use App\Commands\TableCommand;
use App\SshConfig\SshConfig;

it('displays exception message when there are no hosts available', function ($command) {
    // Create an empty SSH config file
    $this->makeTempFile('ssh-config', '');

    // Reload the SSH configuration to reflect the empty state
    $this->sshConfig = app(SshConfig::class);
    $this->sshConfig->load();

    $this->expectExceptionMessage('No Hosts. To add new: ssh-vault add');

    $this->artisan($command);
})->with([
    ListCommand::class,
    RawCommand::class,
    RemoveCommand::class,
    TableCommand::class,
]);
