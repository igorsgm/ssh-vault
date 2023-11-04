<?php

namespace App\Commands;

use App\Concerns\InteractsWithIO;
use App\Host;
use App\SshConfig\SshConfig;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\table;

class ListCommand extends Command
{
    use InteractsWithIO;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'hosts:list {--output=list : The output format (list, table, raw)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List SSH connections';

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

        switch ($this->option('output')) {
            case 'table':
                $this->displayTable($hosts);
                break;
            case 'raw':
                $this->line($sshConfig->content());
                break;
            case 'list':
            default:
                $this->displayList($hosts);
                break;
        }

        return 0;
    }

    /**
     * Displays the list of hosts in a table format.
     *
     * Each host's data is shown in a table with columns for index, host, hostname, user, port,
     * and remote command. The table omits columns that have null values for all hosts.
     *
     * @param  array  $hosts An array of hosts to be displayed.
     */
    protected function displayTable(array $hosts): void
    {
        $columns = ['', 'Host', 'HostName', 'User', 'Port', 'RemoteCommand'];

        $index = 0;
        $rows = collect($hosts)->map(function (Host $host) use (&$index) {
            $index++;

            return [
                'index' => '<fg=gray>'.$index.'</>',
                'Host' => "<info>$host->name</info>",
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
    }

    /**
     * Displays the list of hosts in a list format.
     *
     * Host information is listed with index numbers, names, and hostnames. Padding is calculated
     * to align the host names and hostnames column-wise in the output.
     *
     * @param  array  $hosts An array of hosts to be displayed.
     */
    public function displayList(array $hosts): void
    {
        $maxNameLength = collect($hosts)->max(fn ($host) => mb_strlen($host->name));

        $lastIndexLength = mb_strlen((string) count($hosts));
        foreach ($hosts as $index => $host) {
            $index++;
            $nameLength = $maxNameLength - mb_strlen($host->name);
            $indexLength = $lastIndexLength - mb_strlen((string) $index);
            $paddingLength = $nameLength + $indexLength + 2;

            $this->line(sprintf(
                '  <fg=gray>[%s]</> <fg=green>%s</>%s%s',
                $index,
                $host->name,
                str_repeat(' ', $paddingLength),
                "<comment>{$host->hostName()}</comment>"
            ));
        }
    }
}
