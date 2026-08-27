<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileSettings extends Component
{
    public $name;
    public $phone;
    public $telegram_username;
    public $locale;
    public $successMessage = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->telegram_username = $user->telegram_username;
        $this->locale = $user->locale ?? 'en';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'locale' => 'required|in:en,uz',
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->locale = $this->locale;
        $user->save();
        
        session()->put('locale', $this->locale);

        $this->successMessage = __('Profil muvaffaqiyatli yangilandi!');
        
        return redirect()->route('profile'); // Refresh page to apply locale
    }

    public function render()
    {
        return view('livewire.profile-settings')->layout('layouts.app');
    }
}
