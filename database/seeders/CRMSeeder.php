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
            ['pipeline_id' => $pipeline->id, 'name' => 'Yangi', 'order' => 1],
            ['pipeline_id' => $pipeline->id, 'name' => 'Muzokara', 'order' => 2],
            ['pipeline_id' => $pipeline->id, 'name' => 'Shartnoma', 'order' => 3],
            ['pipeline_id' => $pipeline->id, 'name' => 'Muvaffaqiyatli', 'order' => 4],
            ['pipeline_id' => $pipeline->id, 'name' => 'Bekor qilingan', 'order' => 5],
        ]);
    }
}
