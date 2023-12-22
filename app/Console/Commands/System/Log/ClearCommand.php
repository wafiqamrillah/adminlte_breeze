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
    protected $signature = 'log:clear';

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
        exec('echo "" > ' . storage_path('logs/laravel.log'));

        $this->info('Logs have been cleared!');
    }
}
