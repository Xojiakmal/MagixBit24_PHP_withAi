<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stage = \App\Models\PipelineStage::firstOrCreate([
    'pipeline_id' => 2,
    'name' => 'Cancelled',
    'tenant_id' => 1
], [
    'order' => 5
]);

echo "Created stage: " . $stage->name . " with ID " . $stage->id . "\n";
