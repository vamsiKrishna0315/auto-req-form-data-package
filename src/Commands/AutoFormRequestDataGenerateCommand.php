<?php

namespace Vamsi\AutoFormRequestData\Commands;

use Illuminate\Console\Command;

class AutoFormRequestDataGenerateCommand extends Command
{
    // Set the custom command signature
    protected $signature = 'auto-req-data';

    // Provide a description for the command
    protected $description = 'Generate form request data automatically';

    public function handle(): int
    {
        $this->info('Generating auto-form request data...');

        // Implement your logic here
        $this->comment('Auto-form request data generation completed.');

        return Command::SUCCESS;
    }
}
