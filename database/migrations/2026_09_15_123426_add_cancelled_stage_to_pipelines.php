<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize old stage names
        \App\Models\PipelineStage::where('name', 'Yangi')->update(['name' => 'New deal']);
        \App\Models\PipelineStage::where('name', 'Muzokara')->update(['name' => 'Negotiation']);
        \App\Models\PipelineStage::where('name', 'Shartnoma')->update(['name' => 'Contract']);
        \App\Models\PipelineStage::where('name', 'Muvaffaqiyatli')->update(['name' => 'Closed']);
        \App\Models\PipelineStage::where('name', 'Bekor qilingan')->update(['name' => 'Cancelled']);

        $pipelines = \App\Models\Pipeline::all();
        foreach ($pipelines as $pipeline) {
            $exists = \App\Models\PipelineStage::where('pipeline_id', $pipeline->id)
                ->where('name', 'Cancelled')
                ->exists();
                
            if (!$exists) {
                // Find max order
                $maxOrder = \App\Models\PipelineStage::where('pipeline_id', $pipeline->id)->max('order') ?? 0;
                \App\Models\PipelineStage::create([
                    'pipeline_id' => $pipeline->id,
                    'name' => 'Cancelled',
                    'order' => $maxOrder + 1
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\PipelineStage::where('name', 'Cancelled')->delete();
    }
};
