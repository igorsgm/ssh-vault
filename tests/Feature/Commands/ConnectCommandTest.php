<?php

use App\Commands\Hosts\ConnectCommand;
use Illuminate\Support\Facades\Process;

it('connects to an SSH host', function () {
    $mockedHost = mockedHost();
    $this->sshConfig->add($mockedHost)->sync();

    Process::fake();

    $this->artisan(ConnectCommand::class, ['hostOrHostName' => $mockedHost->getName()])
        ->expectsOutputToContain('Connecting to ')
        ->expectsOutputToContain("And executing: '{$mockedHost->remoteCommand(true)}'")
        ->assertSuccessful();

    Process::assertRan($mockedHost->toSshCommandString());
});

it('prompts for selection when the hostOrHostName parameter is not defined', function () {
    $mockedHost = mockedHost();
    $this->sshConfig->add($mockedHost)->sync();

    // Prepare a list of hosts as it would appear in the command prompt.
    $options = $this->hostsSelectOptions();

    // Prepare the expected choices due to Laravel Prompts Fallback
    $optionsWithFallback = [...array_keys($options), ...$options];

    Process::fake();

    // Since hostOrHostName is not provided, the command should ask the user to select a host.
    $this->artisan(ConnectCommand::class)
        ->expectsChoice('Select host to connect:', $mockedHost->getName(), $optionsWithFallback)
        ->expectsOutputToContain('Connecting to ')
        ->expectsOutputToContain("And executing: '{$mockedHost->remoteCommand(true)}'")
        ->assertSuccessful();

    Process::assertRan($mockedHost->toSshCommandString());
});
