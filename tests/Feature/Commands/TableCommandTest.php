<?php

use App\Commands\TableCommand;
use App\Host;
use Illuminate\Support\Facades\Artisan;
use Laravel\Prompts\Table;

it('displays a table of SSH connections', function () {
    $rows = $this->sshConfig->hosts()
        ->map(fn (Host $host, $index) => app(TableCommand::class)->formatTableRow($host, $index + 1));

    $headers = ['', 'Host', 'HostName', 'User', 'Port', 'RemoteCommand'];
    $tablePrompt = new Table($headers, $rows);

    $tablePromptOutput = invade($tablePrompt)->renderTheme();

    $result = $this->withoutMockingConsoleOutput()->artisan(TableCommand::class);

    $output = Artisan::output();
    expect($output)->toContain('Hosts')
        ->and($output)->toContain($tablePromptOutput)
        ->and($result)->toBe(0);
});

it('displays the table of SSH connections without empty columns', function () {
    // Remove the Port parameter from all hosts to check if the column will not be displayed
    $rows = $this->sshConfig->hosts()->map(function (Host $host, $index) {
        $host->addParameter('Port', null);
        $row = app(TableCommand::class)->formatTableRow($host, $index + 1);

        return array_filter($row);
    });

    $headers = ['', 'Host', 'HostName', 'User', 'RemoteCommand'];
    $tablePrompt = new Table($headers, $rows);

    $tablePromptOutput = invade($tablePrompt)->renderTheme();

    $result = $this->withoutMockingConsoleOutput()->artisan(TableCommand::class);

    $output = Artisan::output();
    expect($output)->toContain('Hosts')
        ->and($output)->toContain($tablePromptOutput)
        ->and($result)->toBe(0);
});
