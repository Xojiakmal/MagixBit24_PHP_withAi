<x-slot name="header">
    <h2 class="font-bold text-2xl text-white tracking-tight">
        {{ __('Kompaniya Sozlamalari') }}
    </h2>
</x-slot>

<div class="max-w-4xl mx-auto py-8">
    
    <!-- Join Code Section -->
    <div class="mb-8 p-6 bg-black/20 border border-dark-border rounded-[24px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-accent/10 rounded-full blur-2xl group-hover:bg-accent/20 transition-all duration-500"></div>
        <div class="relative z-10">
            <h3 class="text-xl font-bold text-white flex items-center mb-4">
                <svg class="w-6 h-6 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                {{ __('Kompaniyaga qo\'shilish kodi') }}
            </h3>
            <p class="text-sm text-gray-400 mb-6">{{ __('Ushbu kod orqali yangi xodimlar kompaniyangiz tizimiga kirishlari mumkin. Agar kod kimningdir qo\'liga tushib qolsa, yangi kod yaratishingiz (regenerate) mumkin.') }}</p>

            @if (session()->has('success_join'))
                <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success_join') }}
                </div>
            @endif

            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-[250px] bg-black/40 px-5 py-4 rounded-xl border border-dark-border relative">
                    <code id="joinCodeText" class="text-accent font-mono text-xl font-bold tracking-wider select-all">{{ $unique_link }}</code>
                </div>
                <button onclick="copyJoinCode()" class="px-6 py-4 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-xl transition-colors flex items-center h-full">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span id="copyText">{{ __('Nusxalash') }}</span>
                </button>
                <button wire:click="regenerateJoinCode" wire:loading.attr="disabled" class="px-6 py-4 bg-accent hover:bg-accent-light text-white text-sm font-semibold rounded-xl shadow-[0_0_15px_rgba(var(--color-accent),0.3)] transition-all flex items-center h-full">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    {{ __('Yangilash') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Storage Settings -->
    <div class="mb-8 p-6 bg-black/20 border border-dark-border rounded-[24px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
        <div class="relative z-10">
            <h3 class="text-xl font-bold text-white flex items-center mb-4">
                <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                {{ __('Storage sozlamalari') }}
            </h3>
            
            @if($storage_chat_id)
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="text-green-400 font-bold">{{ __('Storage tizimi ulangan!') }}</h4>
                        <p class="text-sm text-green-200 mt-1">{{ __('Guruh ID: ') }} <span class="font-mono">{{ $storage_chat_id }}</span></p>
                    </div>
                </div>
            @endif

            <p class="text-sm text-gray-400 mb-6">{{ __('Fayllarni saqlash uchun Telegram Yopiq Guruhini tizimga ulashingiz kerak:') }}</p>
            <ol class="list-decimal pl-5 space-y-2 text-accent text-sm mb-6">
                <li>{!! __('Telegramda yangi <b class="text-white">Yopiq guruh</b> yarating.') !!}</li>
                <li>{!! __('Tizim botini (<span class="text-white font-mono">:bot</span>) shu guruhga qo\'shing.', ['bot' => '@' . env('TELEGRAM_BOT_USERNAME', 'WipeBitrixBot')]) !!}</li>
                <li>{!! __('Botga guruhda <b class="text-white">Admin</b> huquqlarini bering.') !!}</li>
                <li>{{ __('Guruhga quyidagi maxsus buyruqni yuboring:') }}</li>
            </ol>
            
            <div class="p-4 bg-black/40 border border-dark-border rounded-xl mb-2">
                <p class="text-white font-mono text-center text-lg select-all cursor-pointer">
                    /setstorage {{ $unique_link }}
                </p>
            </div>
            <p class="text-xs text-dark-muted text-center">{{ __('Ushbu buyruqni nusxalab guruhga yuboring. Bot o\'zi avtomatik guruhni ulab oladi.') }}</p>
        </div>
    </div>

    <!-- System Settings (Log limit) -->
    <div class="mb-8 p-6 bg-black/20 border border-dark-border rounded-[24px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
        <div class="relative z-10">
            <h3 class="text-xl font-bold text-white flex items-center mb-4">
                <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ __('Tizim Sozlamalari') }}
            </h3>
            <p class="text-sm text-gray-400 mb-6">{{ __('Xodimlar faoliyati va o\'zgarishlar tarixi (loglar) maksimal qancha qator bo\'lgach, eskilarini avtomatik tozalashni boshlashini kiriting.') }}</p>

            @if (session()->has('success_log'))
                <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success_log') }}
                </div>
            @endif

            <form wire:submit="saveLogLimit" class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('Loglar limiti (soni)') }}</label>
                    <input type="tel" wire:model="activity_log_limit" min="10" max="100000" class="w-full bg-dark-surface border border-dark-border text-white rounded-xl focus:ring-accent focus:border-accent p-3">
                    @error('activity_log_limit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.3)] transition-all flex items-center">
                    <span wire:loading.remove wire:target="saveLogLimit">{{ __('Saqlash') }}</span>
                    <span wire:loading wire:target="saveLogLimit">{{ __('Saqlanmoqda...') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function copyJoinCode() {
        const textToCopy = document.getElementById('joinCodeText').innerText;
        navigator.clipboard.writeText(textToCopy).then(() => {
            const btnText = document.getElementById('copyText');
            const originalText = btnText.innerText;
            btnText.innerText = 'Nusxalandi!';
            setTimeout(() => {
                btnText.innerText = originalText;
            }, 2000);
        });
    }
</script>
