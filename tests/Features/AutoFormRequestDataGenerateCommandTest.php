<?php

use Illuminate\Support\Facades\Artisan;

it('can run the auto-req-data command', function () {
    // Run the command and capture the output
    $this->artisan('auto-req-data')
        ->expectsOutput('Generating auto-form request data...')
        ->expectsOutput('Auto-form request data generation completed.')
        ->assertExitCode(0); // Check for success exit code
});
