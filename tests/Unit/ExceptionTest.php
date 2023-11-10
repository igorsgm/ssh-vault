<?php

use App\Commands\Hosts\ConnectCommand;
use App\Commands\Hosts\ListCommand;
use App\Commands\Hosts\RawCommand;
use App\Commands\Hosts\RemoveCommand;
use App\Commands\Hosts\TableCommand;
use App\SshConfig\SshConfig;

it('displays exception message when there are no hosts available', function ($command) {
    // Create an empty SSH config file
    $this->makeTempFile('ssh-config', '');

    // Reload the SSH configuration to reflect the empty state
    $this->sshConfig = app(SshConfig::class);
    $this->sshConfig->load();

    $this->expectExceptionMessage(sprintf('No Hosts. To add new: %s add', app_bin()));

    $this->artisan($command);
})->with([
    ListCommand::class,
    RawCommand::class,
    RemoveCommand::class,
    TableCommand::class,
    ConnectCommand::class,
]);
