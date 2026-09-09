<x-app-layout>
    <div class="h-full w-full flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-black/20 isolate border border-dark-border p-10 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl text-center relative overflow-hidden">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-accent/20 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 mx-auto bg-dark-bg border border-dark-border rounded-2xl flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(155,114,255,0.3)]">
                    <svg class="w-10 h-10 text-accent animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                
                <h2 class="text-2xl font-bold text-white mb-4">So'rovingiz kutilmoqda</h2>
                <p class="text-dark-muted mb-8">Administrator yoki loyiha egasi so'rovingizni tasdiqlaganidan so'ng tizimdan foydalanishingiz mumkin bo'ladi.</p>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-dark-muted hover:text-white transition-colors">Boshqa akkauntga o'tish</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
