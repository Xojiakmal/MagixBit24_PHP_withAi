<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StorageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = Tenant::find($user->current_tenant_id);

        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'Kompaniya topilmadi.');
        }

        $files = File::where('tenant_id', $tenant->id)->latest()->get();

        return view('storage.index', compact('tenant', 'files'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'storage_chat_id' => 'required|string',
        ]);

        $tenant = Tenant::find(Auth::user()->current_tenant_id);
        
        // Basic check to see if bot can access the group
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $response = Http::get("https://api.telegram.org/bot{$botToken}/getChat", [
            'chat_id' => $request->storage_chat_id
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Bot ushbu guruhni topa olmadi. Iltimos, bot guruhga admin qilinganligiga va ID to\'g\'ri ekanligiga ishonch hosil qiling.');
        }

        $tenant->update(['storage_chat_id' => $request->storage_chat_id]);

        return back()->with('success', 'Storage guruhi muvaffaqiyatli saqlandi!');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200', // max 50MB
        ]);

        $tenant = Tenant::find(Auth::user()->current_tenant_id);

        if (!$tenant || !$tenant->storage_chat_id) {
            return back()->with('error', 'Storage guruhi sozlanmagan.');
        }

        $file = $request->file('file');
        $botToken = env('TELEGRAM_BOT_TOKEN');

        // Determine if it's a photo, video or document
        $mime = $file->getMimeType();
        $endpoint = 'sendDocument';
        $fileField = 'document';

        if (str_starts_with($mime, 'image/')) {
            $endpoint = 'sendPhoto';
            $fileField = 'photo';
        } elseif (str_starts_with($mime, 'video/')) {
            $endpoint = 'sendVideo';
            $fileField = 'video';
        }

        // Upload to Telegram
        $response = Http::attach(
            $fileField, file_get_contents($file->getRealPath()), $file->getClientOriginalName()
        )->post("https://api.telegram.org/bot{$botToken}/{$endpoint}", [
            'chat_id' => $tenant->storage_chat_id,
            'caption' => 'Yukladi: ' . Auth::user()->name,
        ]);

        if ($response->successful()) {
            $result = $response->json('result');
            
            $telegramFileId = null;
            if ($endpoint === 'sendPhoto') {
                // Get the largest photo size
                $telegramFileId = end($result['photo'])['file_id'];
            } elseif ($endpoint === 'sendVideo') {
                $telegramFileId = $result['video']['file_id'];
            } else {
                $telegramFileId = $result['document']['file_id'];
            }

            File::create([
                'tenant_id' => $tenant->id,
                'user_id' => Auth::id(),
                'telegram_message_id' => $result['message_id'],
                'telegram_file_id' => $telegramFileId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
            ]);

            return back()->with('success', 'Fayl muvaffaqiyatli saqlandi.');
        }

        return back()->with('error', 'Faylni Telegramga yuklashda xatolik yuz berdi: ' . $response->body());
    }
}
