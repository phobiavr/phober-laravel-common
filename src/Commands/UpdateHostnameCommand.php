<?php

namespace Phobiavr\PhoberLaravelCommon\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateHostnameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostname:update {container}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates or creates a hostname record';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $hostname = gethostname(); // Automatically get the hostname
        $containerName = $this->argument('container'); // Manually provided container name
        $timestamp = Carbon::now();
        $success = false;
        $attempts = 5;

        while (!$success && $attempts > 0) {
            try {
                $attempts--;

                $query = DB::connection('db_shared')->table('hostnames');

                $query->insert([
                    'hostname' => $hostname,
                    'created_at' => $timestamp,
                    'container' => $containerName,
                    'updated_at' => $timestamp,
                ]);

                $this->info("Hostname record for '{$hostname}' has been created.");

                $success = true;
            } catch (\Exception $e) {
                Log::error("Failed to create a hostname record attempts {$attempts} left", ['message' => $e->getMessage()]);
                $this->error("Failed to create a hostname record: {$e->getMessage()}. Retrying in 5 seconds, attempts {$attempts} left...");
                sleep(5);
            }
        }

        return Command::SUCCESS;
    }
}
