<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Tenant;
use App\Models\File;
use Illuminate\Support\Facades\Http;

class ActivityArchiver
{
    public static function archive($tenantId)
    {
        $activities = Activity::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'asc')
            ->limit(1000)
            ->get();

        if ($activities->count() < 1000) {
            return; // Safety check
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant || !$tenant->storage_chat_id) {
            // Can't archive to telegram without storage setup. Just delete them? Or keep them?
            // The requirement is to save to storage every 1000 rows. We'll only delete if saved successfully.
            return;
        }

        $jsonContent = $activities->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $fileName = "activities_archive_" . now()->format('Y_m_d_H_i_s') . ".json";
        $tempPath = storage_path('app/private/' . $fileName);
        
        // Ensure directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        
        file_put_contents($tempPath, $jsonContent);

        $botToken = env('TELEGRAM_BOT_TOKEN');
        
        $caption = "Avtomatik Arxiv: Xodimlar faoliyati tarixi\n";
        $caption .= "Fayl nomi: " . $fileName . "\n";
        $caption .= "Papka: Arxivlar (Avtomatik)\n";
        $caption .= "Qatorlar soni: 1000 ta\n";

        $response = Http::timeout(60)->attach(
            'document', file_get_contents($tempPath), $fileName
        )->post("https://api.telegram.org/bot{$botToken}/sendDocument", [
            'chat_id' => $tenant->storage_chat_id,
            'caption' => $caption,
        ]);

        if ($response->successful()) {
            $result = $response->json('result');
            
            // Record the file in the database
            File::create([
                'tenant_id' => $tenantId,
                'user_id' => $tenant->owner_id, // Owner gets the credit
                'telegram_message_id' => $result['message_id'],
                'telegram_file_id' => $result['document']['file_id'],
                'file_name' => $fileName,
                'file_size' => filesize($tempPath),
                'file_type' => 'application/json',
                'folder_id' => null, // Root folder
            ]);

            // Delete the archived activities
            Activity::whereIn('id', $activities->pluck('id'))->delete();
        }

        // Clean up temp file
        @unlink($tempPath);
    }
}
