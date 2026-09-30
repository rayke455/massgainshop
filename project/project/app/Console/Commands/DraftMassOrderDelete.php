<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DraftMassOrderDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draft-mass-order:delete';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete DraftMassOrder records older than one day';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $deletedRows = DB::table('draft_mass_orders')
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $this->info("Deleted {$deletedRows} records older than one day.");
    }
}
