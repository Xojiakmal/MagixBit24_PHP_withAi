<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CRMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pipeline = \App\Models\Pipeline::create(['name' => 'Umumiy Sotuv']);
        
        \App\Models\PipelineStage::insert([
            ['pipeline_id' => $pipeline->id, 'name' => 'New deal', 'order' => 1],
            ['pipeline_id' => $pipeline->id, 'name' => 'Negotiation', 'order' => 2],
            ['pipeline_id' => $pipeline->id, 'name' => 'Contract', 'order' => 3],
            ['pipeline_id' => $pipeline->id, 'name' => 'Closed', 'order' => 4],
            ['pipeline_id' => $pipeline->id, 'name' => 'Cancelled', 'order' => 5],
        ]);
    }
}
