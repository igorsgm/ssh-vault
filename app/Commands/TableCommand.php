<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\table;

class TableCommand extends Command
{
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
    protected $description = 'Displays the list of SSH connections in a table format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SshConfig $sshConfig)
    {
        abort_if($sshConfig->isEmpty(), 1, 'No Hosts. To add new: ssh-vault add');

        $this->step('Hosts');
        $hosts = $sshConfig->hosts();

        $columns = ['', 'Host', 'HostName', 'User', 'Port', 'RemoteCommand'];

        $index = 0;
        $rows = collect($hosts)->map(function (Host $host) use (&$index) {
            $index++;

            return [
                'index' => '<fg=gray>'.$index.'</>',
                'Host' => "<info>{$host->getName()}</info>",
                'HostName' => "<comment>{$host->hostName()}</comment>",
                'User' => $host->user(),
                'Port' => $host->port(),
                'RemoteCommand' => $host->remoteCommand() ?? '<fg=gray>-</>',
            ];
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

        return 0;
    }
}
