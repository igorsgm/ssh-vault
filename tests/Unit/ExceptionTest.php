<?php

use Igorsgm\SshVault\Commands\Hosts\ConnectCommand;
use Igorsgm\SshVault\Commands\Hosts\ListCommand;
use Igorsgm\SshVault\Commands\Hosts\RawCommand;
use Igorsgm\SshVault\Commands\Hosts\RemoveCommand;
use Igorsgm\SshVault\Commands\Hosts\TableCommand;
use Igorsgm\SshVault\SshConfig\SshConfig;

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
