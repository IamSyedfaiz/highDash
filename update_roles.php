<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$r = App\Models\Role::where('slug', 'calling')->first();
if ($r) {
    $r->update(['name' => 'Sales Team', 'slug' => 'sales']);
}
App\Models\Role::firstOrCreate(['slug' => 'inside_sales'], ['name' => 'Inside Sales Team', 'description' => 'Inside Sales Agent']);
App\Models\Role::firstOrCreate(['slug' => 'field_sales'], ['name' => 'Field Sales Team', 'description' => 'Field Sales Agent']);
App\Models\Role::firstOrCreate(['slug' => 'hr'], ['name' => 'HR', 'description' => 'Human Resources']);
echo "Roles updated.\n";
