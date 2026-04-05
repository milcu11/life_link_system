<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDonors extends Command
{
    protected $signature = 'donors:clear';
    protected $description = 'Clear all donors from the database';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('donors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->info('All donors have been cleared from the database.');
    }
}
