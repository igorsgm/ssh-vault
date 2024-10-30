<?php

namespace Igorsgm\SshVault\Commands\Hosts;

use Igorsgm\SshVault\Concerns\HostsCommandHelper;
use Igorsgm\SshVault\Concerns\InteractsWithIO;
use Igorsgm\SshVault\Host;
use Igorsgm\SshVault\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\table;

class TableCommand extends Command
{
    use HostsCommandHelper;
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:table';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '🧮 Displays all SSH connections in a table format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        $this->ensureSshConfigFile();
        $this->step('Hosts');

        $columns = ['', 'Host', 'HostName', 'User', 'Port', 'RemoteCommand'];

        $rows = $sshConfig->hosts()->map(function (Host $host, $index) {
            return $this->formatTableRow($host, $index + 1);
        });

        // Determine which columns to exclude because they have only null values
        $columnsToExclude = array_flip(array_filter($columns, function ($column) use ($rows) {
            // Skip the 'index' column as it should always be present
            if ($column === '') {
                return false;
            }

            return $rows->every(fn ($row) => empty($row[$column]));
        }));

        // Filter out the columns from each row
        $filteredRows = $rows->map(function ($row) use ($columnsToExclude) {
            return array_diff_key($row, $columnsToExclude);
        });

        // Prepare the headers, excluding the unnecessary ones
        $filteredHeaders = array_diff($columns, array_keys($columnsToExclude));

        // Output the table with filtered columns and rows
        table($filteredHeaders, $filteredRows->toArray());

        return Command::SUCCESS;
    }

    public function formatTableRow(Host $host, $index): array
    {
        return [
            'index' => '<fg=gray>'.$index.'</>',
            'Host' => "<info>{$host->getName()}</info>",
            'HostName' => "<comment>{$host->hostName()}</comment>",
            'User' => $host->user(),
            'Port' => $host->port(),
            'RemoteCommand' => $host->remoteCommand() ?? '<fg=gray>-</>',
        ];
    }
}
