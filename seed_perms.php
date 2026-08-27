<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = [
    'view_dashboard', 'view_crm', 'create_deal', 'comment_deal',
    'view_tasks', 'create_task', 'edit_task', 'delete_task',
    'view_storage', 'manage_storage', 'manage_employees'
];

foreach ($permissions as $p) {
    \Spatie\Permission\Models\Permission::findOrCreate($p, 'web');
}

echo "Permissions seeded successfully.\n";
