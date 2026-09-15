<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CompanySettings extends Component
{
    public $unique_link;
    public $storage_chat_id;
    public $activity_log_limit;

    public function mount()
    {
        $tenant = Tenant::find(Auth::user()->current_tenant_id);
        
        if (!$tenant) {
            return redirect()->route('dashboard');
        }

        $this->unique_link = $tenant->unique_link;
        $this->storage_chat_id = $tenant->storage_chat_id;
        $this->activity_log_limit = $tenant->activity_log_limit ?? 500;
    }

    public function regenerateJoinCode()
    {
        $tenant = Tenant::find(Auth::user()->current_tenant_id);
        if ($tenant && ($tenant->owner_id === Auth::id() || Auth::user()->hasRole('Admin'))) {
            $newCode = Str::slug($tenant->name) . '-' . Str::random(6);
            $tenant->update(['unique_link' => $newCode]);
            $this->unique_link = $newCode;
            session()->flash('success_join', __('Kompaniyaga qo\'shilish kodi muvaffaqiyatli yangilandi!'));
        }
    }

    // Removed saveStorageSettings as it is now handled directly by the Telegram bot webhook

    public function saveLogLimit()
    {
        $this->validate([
            'activity_log_limit' => 'required|integer|min:10|max:100000',
        ]);

        $tenant = Tenant::find(Auth::user()->current_tenant_id);
        $tenant->update(['activity_log_limit' => $this->activity_log_limit]);
        session()->flash('success_log', __('Tarix yozuvlari limiti muvaffaqiyatli saqlandi!'));
    }

    public function render()
    {
        return view('livewire.company-settings')->layout('layouts.app');
    }
}
