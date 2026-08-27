<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileSettings extends Component
{
    public $name;
    public $phone;
    public $telegram_username;
    public $successMessage = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->telegram_username = $user->telegram_username;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->phone = $this->phone;
        // telegram_username read-only bo'lganligi uchun faqat name va phone saqlanadi
        $user->save();

        $this->successMessage = 'Profil muvaffaqiyatli yangilandi!';
    }

    public function render()
    {
        return view('livewire.profile-settings')->layout('layouts.app');
    }
}
