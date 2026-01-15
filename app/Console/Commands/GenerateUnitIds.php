<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;

class GenerateUnitIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'units:generate-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate unit_id for existing property units that don\'t have one';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating unit_ids for existing property units...');

        $units = PropertyUnit::whereNull('unit_id')->orWhere('unit_id', '')->get();
        $count = $units->count();

        if ($count === 0) {
            $this->info('All units already have unit_ids.');
            return Command::SUCCESS;
        }

        $this->info("Found {$count} units without unit_id. Generating...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($units as $unit) {
            $unit->unit_id = PropertyUnit::generateUnitId();
            $unit->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated unit_ids for {$count} units.");

        return Command::SUCCESS;
    }
}
