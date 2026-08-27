<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tenant;
use App\Models\Activity;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArchiveActivitiesJob implements ShouldQueue
{
    use Queueable;

    protected $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);
        if (!$tenant) return;

        $count = Activity::where('tenant_id', $this->tenantId)->count();
        
        if ($count >= 1000) {
            // Get oldest 1000 records
            $activities = Activity::where('tenant_id', $this->tenantId)
                                  ->orderBy('id', 'asc')
                                  ->take(1000)
                                  ->get();

            if ($activities->isEmpty()) return;

            // Generate JSON log file
            $fileName = 'activity-log-' . now()->format('Y-m-d-H-i-s') . '.json';
            $localPath = storage_path('app/' . $fileName);
            file_put_contents($localPath, $activities->toJson(JSON_PRETTY_PRINT));

            // Ensure "company-logs" folder exists for this tenant
            $folder = Folder::firstOrCreate([
                'tenant_id' => $this->tenantId,
                'name' => 'company-logs',
                'parent_id' => null,
            ]);

            // If tenant has Telegram storage set up, upload it
            if ($tenant->storage_chat_id) {
                $botToken = env('TELEGRAM_BOT_TOKEN');
                $response = Http::attach(
                    'document', file_get_contents($localPath), $fileName
                )->post("https://api.telegram.org/bot{$botToken}/sendDocument", [
                    'chat_id' => $tenant->storage_chat_id,
                    'caption' => 'Auto-archived Activities (1000 records)',
                ]);

                if ($response->successful()) {
                    $result = $response->json('result');
                    
                    File::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => null, // System
                        'telegram_message_id' => $result['message_id'],
                        'telegram_file_id' => $result['document']['file_id'],
                        'file_name' => $fileName,
                        'file_size' => filesize($localPath),
                        'file_type' => 'application/json',
                        'folder_id' => $folder->id,
                    ]);

                    // Delete the activities from DB
                    Activity::whereIn('id', $activities->pluck('id'))->delete();
                    Log::info("Archived 1000 activities for tenant {$this->tenantId} to Telegram");
                } else {
                    Log::error("Failed to upload auto-archive to Telegram for tenant {$this->tenantId}");
                }
            } else {
                // Keep file locally if no Telegram storage
                Log::warning("Tenant {$this->tenantId} has no Telegram storage, logs saved locally at {$localPath}");
            }

            // Cleanup local file if uploaded
            if ($tenant->storage_chat_id && file_exists($localPath)) {
                unlink($localPath);
            }
        }
    }
}
