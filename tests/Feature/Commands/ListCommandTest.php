<?php

use App\Commands\Hosts\ListCommand;
use App\Host;
use Illuminate\Support\Facades\Artisan;

it('displays the list of SSH connections', function () {
    $result = $this->withoutMockingConsoleOutput()->artisan(ListCommand::class);

    $output = Artisan::output();
    expect($output)->toContain('Hosts');

    $this->sshConfig->hosts()->each(function (Host $host) use ($output) {
        expect($output)->toContain($host->getName());
        expect($output)->toContain($host->hostName());
    });

    expect($result)->toBe(0);
});
