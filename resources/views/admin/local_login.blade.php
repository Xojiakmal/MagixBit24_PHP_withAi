<x-app-layout>
    <div class="h-full w-full flex items-center justify-center p-6">
        <div class="max-w-md w-full">
            <h2 class="text-3xl font-bold text-white text-center mb-8">
                {{ $superadminExists ? 'Superadmin Kirish' : 'Superadmin Yaratish' }}
            </h2>

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-2xl mb-6 backdrop-blur-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-black/20 border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <p class="text-gray-400 mb-6 text-center text-sm">
                    {{ $superadminExists ? 'Tizimga kirish uchun Superadmin email va parolingizni kiriting.' : 'Tizimda birinchi marta Superadmin o\'rnatmoqdasiz. Email va parol yarating.' }}
                </p>

                <form method="POST" action="{{ route('admin.local.authenticate') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email manzil</label>
                        <input type="email" id="email" name="email" required
                            class="w-full bg-dark-bg border border-dark-border text-white rounded-xl px-4 py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            placeholder="admin@example.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Parol</label>
                        <input type="password" id="password" name="password" required
                            class="w-full bg-dark-bg border border-dark-border text-white rounded-xl px-4 py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-accent hover:bg-accent/90 text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(var(--accent-rgb),0.3)] hover:shadow-[0_0_25px_rgba(var(--accent-rgb),0.5)]">
                        {{ $superadminExists ? 'Kirish' : 'O\'rnatish va Kirish' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
