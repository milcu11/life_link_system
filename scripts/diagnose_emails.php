<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$donors = App\Models\Donor::with('user')->latest()->take(10)->get();
foreach ($donors as $d) {
    $email = $d->user?->email ?? 'NULL';
    echo $d->id . ' -> ' . $email . PHP_EOL;
}

return 0;
