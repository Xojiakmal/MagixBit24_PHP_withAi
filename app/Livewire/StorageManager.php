<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StorageManager extends Component
{
    use WithFileUploads;

    public $tenant;
    public $currentFolderId = null;
    
    // Breadcrumbs
    public $breadcrumbs = [];

    // Folder Creation
    public $isCreatingFolder = false;
    public $newFolderName = '';

    // File Renaming
    public $isRenamingFile = false;
    public $renamingFileId = null;
    public $newFileName = '';

    // File Upload
    public $fileToUpload;

    public function mount()
    {
        $this->tenant = Tenant::findOrFail(Auth::user()->current_tenant_id);
        $this->updateBreadcrumbs();
    }

    public function openFolder($folderId)
    {
        $this->currentFolderId = $folderId;
        $this->updateBreadcrumbs();
    }

    public function goBack()
    {
        if ($this->currentFolderId) {
            $folder = Folder::find($this->currentFolderId);
            $this->currentFolderId = $folder ? $folder->parent_id : null;
            $this->updateBreadcrumbs();
        }
    }

    public function updateBreadcrumbs()
    {
        $this->breadcrumbs = [];
        $current = Folder::find($this->currentFolderId);
        
        while ($current) {
            array_unshift($this->breadcrumbs, $current);
            $current = $current->parent;
        }
    }

    public function saveFolder()
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $this->validate(['newFolderName' => 'required|string|max:255']);
        
        Folder::create([
            'tenant_id' => $this->tenant->id,
            'name' => $this->newFolderName,
            'parent_id' => $this->currentFolderId
        ]);

        $this->isCreatingFolder = false;
        $this->newFolderName = '';
    }

    public function uploadFile()
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $this->validate([
            'fileToUpload' => 'required|max:51200' // 50MB
        ]);

        if (!$this->tenant->storage_chat_id) {
            session()->flash('error', 'Storage guruhi sozlanmagan!');
            return;
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $mime = $this->fileToUpload->getMimeType();
        $endpoint = 'sendDocument';
        $fileField = 'document';

        if (str_starts_with($mime, 'image/')) {
            $endpoint = 'sendPhoto';
            $fileField = 'photo';
        } elseif (str_starts_with($mime, 'video/')) {
            $endpoint = 'sendVideo';
            $fileField = 'video';
        }

        $folderPath = 'Asosiy Ombor';
        if ($this->currentFolderId) {
            $pathNames = [];
            $current = Folder::find($this->currentFolderId);
            while ($current) {
                array_unshift($pathNames, $current->name);
                $current = $current->parent;
            }
            $folderPath .= ' / ' . implode(' / ', $pathNames);
        }

        $caption = "Yukladi: " . Auth::user()->name . "\n";
        $caption .= "Fayl nomi: " . $this->fileToUpload->getClientOriginalName() . "\n";
        $caption .= "Hajmi: " . number_format($this->fileToUpload->getSize() / 1024 / 1024, 2) . " MB\n";
        $caption .= "Papka: " . $folderPath;

        $response = Http::timeout(60)->attach(
            $fileField, file_get_contents($this->fileToUpload->getRealPath()), $this->fileToUpload->getClientOriginalName()
        )->post("https://api.telegram.org/bot{$botToken}/{$endpoint}", [
            'chat_id' => $this->tenant->storage_chat_id,
            'caption' => $caption,
        ]);

        if ($response->successful()) {
            $result = $response->json('result');
            
            $telegramFileId = null;
            if ($endpoint === 'sendPhoto') {
                $telegramFileId = end($result['photo'])['file_id'];
            } elseif ($endpoint === 'sendVideo') {
                $telegramFileId = $result['video']['file_id'];
            } else {
                $telegramFileId = $result['document']['file_id'];
            }

            File::create([
                'tenant_id' => $this->tenant->id,
                'user_id' => Auth::id(),
                'telegram_message_id' => $result['message_id'],
                'telegram_file_id' => $telegramFileId,
                'file_name' => $this->fileToUpload->getClientOriginalName(),
                'file_size' => $this->fileToUpload->getSize(),
                'file_type' => $this->fileToUpload->getMimeType(),
                'folder_id' => $this->currentFolderId,
            ]);

            $this->fileToUpload = null;
            session()->flash('success', 'Fayl muvaffaqiyatli saqlandi!');
        } else {
            session()->flash('error', 'Telegramga yuklashda xatolik yuz berdi.');
        }
    }

    public function deleteFolder($id)
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) return;
        Folder::where('id', $id)->where('tenant_id', $this->tenant->id)->delete();
    }

    public function deleteFile($id)
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) return;
        File::where('id', $id)->where('tenant_id', $this->tenant->id)->delete();
    }

    public function startRenamingFile($id, $currentName)
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) return;
        $this->isRenamingFile = true;
        $this->renamingFileId = $id;
        $this->newFileName = $currentName;
    }

    public function saveFileName()
    {
        if (!Auth::user()->can('manage_storage') && !Auth::user()->hasRole('Admin')) return;
        $this->validate(['newFileName' => 'required|string|max:255']);

        $file = File::where('id', $this->renamingFileId)->where('tenant_id', $this->tenant->id)->first();
        if ($file) {
            $file->update(['file_name' => $this->newFileName]);
            session()->flash('success', 'Fayl nomi o\'zgartirildi!');
        }

        $this->isRenamingFile = false;
        $this->renamingFileId = null;
        $this->newFileName = '';
    }

    public function downloadFile($id)
    {
        $file = File::where('id', $id)->where('tenant_id', $this->tenant->id)->first();
        if (!$file || !$file->telegram_file_id) {
            session()->flash('error', 'Fayl topilmadi!');
            return;
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $response = Http::timeout(30)->get("https://api.telegram.org/bot{$botToken}/getFile", [
            'file_id' => $file->telegram_file_id
        ]);

        if ($response->successful()) {
            $filePath = $response->json('result.file_path');
            if ($filePath) {
                $downloadUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
                $this->redirect($downloadUrl);
            } else {
                session()->flash('error', 'Fayl yo\'li topilmadi.');
            }
        } else {
            session()->flash('error', 'Telegramdan faylni olishda xatolik yuz berdi.');
        }
    }

    public function render()
    {
        $folders = Folder::where('tenant_id', $this->tenant->id)
                         ->where('parent_id', $this->currentFolderId)
                         ->get();

        $files = File::where('tenant_id', $this->tenant->id)
                     ->where('folder_id', $this->currentFolderId)
                     ->get();

        return view('livewire.storage-manager', compact('folders', 'files'))
            ->layout('layouts.app');
    }
}
