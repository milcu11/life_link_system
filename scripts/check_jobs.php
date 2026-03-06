<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jobs = Illuminate\Support\Facades\DB::table('jobs')->orderBy('id','desc')->take(10)->get();
echo "jobs in table: " . Illuminate\Support\Facades\DB::table('jobs')->count() . PHP_EOL;
foreach($jobs as $j){
    echo "id=".$j->id." available_at=".$j->available_at." payload-type=" . (strpos($j->payload,'DonorVerificationStatus')!==false? 'DonorVerificationStatus':'other') . PHP_EOL;
}

return 0;
