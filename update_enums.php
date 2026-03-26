<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE leads MODIFY status VARCHAR(255) DEFAULT 'New Lead'");
DB::statement("ALTER TABLE leads MODIFY calling_status VARCHAR(255) NULL");
DB::statement("ALTER TABLE leads MODIFY prospect_status VARCHAR(255) NULL");
DB::statement("ALTER TABLE lead_follow_ups MODIFY status VARCHAR(255) NULL");

echo "Schema modified to VARCHAR successfully.\n";
