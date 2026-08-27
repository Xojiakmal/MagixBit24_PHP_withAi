<x-app-layout>
    <div class="h-full w-full flex items-center justify-center p-6">
        <div class="max-w-4xl w-full">
            <h2 class="text-3xl font-bold text-white text-center mb-10">
                {{ request()->routeIs('company.create.form') ? 'Kompaniya Yaratish' : 'Kompaniyaga Qo\'shilish' }}
            </h2>

            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-2xl mb-8 backdrop-blur-md">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 justify-center">
                @php
                    // Endi subdomen o'rniga qaysi marshrutdan (endpoint) kelganiga qarab ajratamiz
                    $isCreatePage = request()->routeIs('company.create.form');
                @endphp

                @if(!$isCreatePage)
                <!-- Join Company (magixbit24.com) -->
                <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group max-w-xl mx-auto w-full">
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-accent/20 rounded-full blur-3xl group-hover:bg-accent/30 transition-all duration-500"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-dark-bg border border-dark-border flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Xodim sifatida qo'shilish</h3>
                        <p class="text-sm text-dark-muted mb-8">Kompaniya administratori bergan maxsus havolani kiriting.</p>
                        
                        <form method="POST" action="{{ route('company.join') }}">
                            @csrf
                            <div>
                                <label for="unique_link" class="block text-sm font-medium text-dark-muted mb-2">Kompaniya Havolasi</label>
                                <input id="unique_link" name="unique_link" type="text" class="w-full bg-dark-bg border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required placeholder="Masalan: mening-kompaniyam-xyz123" />
                            </div>
                            <div class="mt-8">
                                <button type="submit" class="w-full bg-accent hover:bg-accent-hover text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(155,114,255,0.4)]">Qo'shilish</button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <!-- Create Company (company.magixbit24.com) -->
                <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group max-w-xl mx-auto w-full">
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-accent/20 rounded-full blur-3xl group-hover:bg-accent/30 transition-all duration-500"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-dark-bg border border-dark-border flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Kompaniya Yaratish (So'rov qoldirish)</h3>
                        <p class="text-sm text-dark-muted mb-6">Yangi kompaniya ochish uchun ma'lumotlarni kiriting. Tasdiqlangandan so'ng tizimga kira olasiz.</p>
                        
                        <form method="POST" action="{{ route('company.create') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-dark-muted mb-1">Kompaniya Nomi *</label>
                                <input id="name" name="name" type="text" class="w-full bg-dark-bg border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required placeholder="Kompaniyangiz nomi" />
                            </div>
                            
                            <div>
                                <label for="creator_name" class="block text-sm font-medium text-dark-muted mb-1">Ism-familiya *</label>
                                <input id="creator_name" name="creator_name" type="text" value="{{ auth()->user()->name }}" class="w-full bg-dark-bg border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required placeholder="To'liq ismingiz" />
                            </div>

                            <div>
                                <label for="gmail" class="block text-sm font-medium text-dark-muted mb-1">Gmail Manzili *</label>
                                <input id="gmail" name="gmail" type="email" class="w-full bg-dark-bg border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required placeholder="example@gmail.com" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-dark-muted mb-1">Telefon Raqam *</label>
                                <input id="phone" name="phone" type="text" value="{{ auth()->user()->phone }}" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" class="w-full bg-dark-bg border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required placeholder="+998901234567" />
                            </div>

                            <div class="mt-8 pt-4">
                                <button type="submit" class="w-full bg-dark-surface border border-accent text-accent hover:bg-accent hover:text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(155,114,255,0.2)]">So'rov yuborish</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
