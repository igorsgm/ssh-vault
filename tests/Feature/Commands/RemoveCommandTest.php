<?php

use App\Commands\Hosts\RemoveCommand;

it('removes selected SSH connections', function () {
    $hosts = $this->sshConfig->hosts();
    $nameToRemove = $hosts->first()->getName();

    $this->artisan(RemoveCommand::class)
        ->expectsChoice(
            'Select hosts to remove:',
            [$nameToRemove],
            $this->hostsSelectOptions(),
        )
        ->assertExitCode(0);

    // Re-load the SSH config to check the updated content
    $this->sshConfig->load();
    $remainingHosts = $this->sshConfig->hosts();

    // Check the content of the SSH config file to confirm the host was removed
    expect($remainingHosts)->toHaveCount($hosts->count() - 1);

    // Verify the specific host was removed
    expect($remainingHosts->doesntContain(fn ($host) => $host->getName() === $nameToRemove));
});
