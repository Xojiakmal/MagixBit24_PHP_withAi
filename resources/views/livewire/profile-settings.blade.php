<div class="p-4 sm:p-8">
    <div class="max-w-2xl mx-auto glass-panel rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-accent rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold text-white mb-6 tracking-tight">{{ __('Mening Profilim') }}</h2>
            
            @if ($successMessage)
                <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/50 text-green-300">
                    {{ $successMessage }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Name (Site Username) -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Saytdagi Ismingiz') }}</label>
                    <input type="text" id="name" wire:model="name" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required>
                    @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Telefon Raqam') }}</label>
                    <input type="text" id="phone" wire:model="phone" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" placeholder="+998901234567" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                    @error('phone') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Telegram Username (Read Only) -->
                <div>
                    <label for="telegram_username" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Telegram Username') }}</label>
                    <input type="text" id="telegram_username" wire:model="telegram_username" class="w-full bg-black/30 border border-dark-border rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" readonly>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Ushbu maydonni o\'zgartirib bo\'lmaydi. U faqat telegram orqali tizimga kirish uchun xizmat qiladi.') }}</p>
                </div>

                <!-- Locale/Language -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Til') }} / Language</label>
                    <!-- Custom Select for Locale -->
                    <div x-data="{ open: false, selected: @entangle('locale') }" class="relative">
                        <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            <span x-text="selected === 'en' ? 'English' : (selected === 'uz' ? 'O\'zbekcha' : '{{ __('Tanlang') }}')"></span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                            <div @click="selected = 'en'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">English</div>
                            <div @click="selected = 'uz'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">O'zbekcha</div>
                        </div>
                    </div>
                    @error('locale') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="bg-accent hover:bg-accent-hover text-white px-6 py-3 rounded-xl shadow-lg shadow-accent/20 transition-all font-semibold inline-flex items-center space-x-2">
                        <svg wire:loading.delay wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ __('Saqlash') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
