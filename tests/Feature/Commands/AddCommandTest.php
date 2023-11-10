<?php

// Test that the command adds a new SSH host correctly
use App\Commands\Hosts\AddCommand;
use Illuminate\Support\Facades\File;

it('adds a new SSH connection', function () {

    $mockedHost = mockedHost();

    File::partialMock()
        ->allows('exists')
        ->with($mockedHost->identityFile())
        ->andReturnTrue();

    $this->artisan(AddCommand::class)
        ->expectsQuestion('Connection name:', $mockedHost->getName())
        ->expectsQuestion('Host Name:', $mockedHost->hostName())
        ->expectsQuestion('Port:', $mockedHost->port())
        ->expectsQuestion('User Name:', $mockedHost->user())
        ->expectsQuestion('Identity File Location:', $mockedHost->identityFile())
        ->expectsConfirmation('Forward Agent:', $mockedHost->forwardAgent() ? 'yes' : 'no')
        ->expectsConfirmation('Add Keys To Agent:', $mockedHost->addKeysToAgent() ? 'yes' : 'no')
        ->expectsConfirmation('Request TTY:', $mockedHost->requestTTY() ? 'yes' : 'no')
        ->expectsQuestion('Remote Command:', $mockedHost->remoteCommand())
        ->assertExitCode(0);

    // Check the content of the SSH config file to confirm the host was added
    $configContent = File::get($this->getTempFilePath('ssh-config'));

    if ($mockedHost->port() === AddCommand::DEFAULT_PORT) {
        $mockedHost->removeParameter('Port');
    }

    expect($configContent)->toContain($mockedHost);
});
