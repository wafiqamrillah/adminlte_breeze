<?php

namespace App\Console\Commands\System\Log;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear {log_file_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all log files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFileName = $this->argument('log_file_name');
        $logs = [];
        
        if ($logFileName) {
            // Check if the log file name has a .log extension
            if (substr($logFileName, -4) !== '.log') $logFileName .= '.log';

            // Check if the log file exists
            if (!file_exists(storage_path('logs/' . $logFileName))) {
                $this->error('The log file does not exist!');

                return;
            }

            $logs[] = storage_path('logs/' . $logFileName);
        } else {
            // Get all the log files with the .log extension in the storage/logs directory
            $logs = glob(storage_path('logs/*.log'));
        }
        
        // Clear all the log files using exec()
        foreach ($logs as $log) {
            $this->line('Clearing log: ' . $log);
            exec('echo "" > ' . $log);
        }

        $this->info('Logs have been cleared!');
    }
}
