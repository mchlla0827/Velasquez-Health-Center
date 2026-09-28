<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Illuminate\Console\Command;

class ArchiveInactivePatients extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'patients:archive-inactive';

    /**
     * The console command description.
     */
    protected $description = 'Automatically archive patients with 5+ years of real inactivity (no recorded triage, consultation, NCD assessment, or dispensing activity).';

    public function handle(): int
    {
        $count = Patient::runAutoArchiveSweep();

        if ($count > 0) {
            $this->info("Archived {$count} patient(s) due to 5+ years of inactivity.");
        } else {
            $this->info('No patients met the 5-year inactivity threshold.');
        }

        return self::SUCCESS;
    }
}
