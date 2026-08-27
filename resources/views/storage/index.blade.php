<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            Kompaniya Ombri (Storage)
        </h2>
    </x-slot>

    <div class="h-full space-y-6">
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-200 px-6 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-6 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        @if(!$tenant->storage_chat_id)
            @if(Auth::user()->hasRole('Admin') || Auth::user()->id === $tenant->owner_id)
                <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl max-w-3xl">
                    <h3 class="text-xl font-bold text-white mb-4">Storage tizimini sozlash</h3>
                    <div class="text-gray-300 space-y-4 mb-8 text-sm">
                        <p>Fayllarni markazlashgan holda saqlash uchun Telegram Yopiq Guruhini (Private Group) tizimga ulashingiz kerak:</p>
                        <ol class="list-decimal pl-5 space-y-2 text-accent">
                            <li>Telegramda yangi <b>Yopiq guruh (Private Group)</b> yarating.</li>
                            <li>Tizim botini (<span class="text-white font-mono">{{ "@" }}{{ env('TELEGRAM_BOT_USERNAME', 'WipeBitrixBot') }}</span>) shu guruhga qo'shing.</li>
                            <li>Botga guruhda <b>Admin</b> huquqlarini bering.</li>
                            <li>Guruhga quyidagi maxsus buyruqni yuboring:</li>
                        </ol>
                        
                        <div class="mt-4 p-4 bg-black/40 border border-dark-border rounded-xl">
                            <p class="text-white font-mono text-center text-lg select-all cursor-pointer">
                                /setstorage {{ $tenant->unique_link }}
                            </p>
                        </div>
                        <p class="text-xs text-dark-muted text-center mt-2">Ushbu buyruqni nusxalab guruhga yuboring. Bot o'zi avtomatik guruhni ulab oladi.</p>
                    </div>
                </div>
            @else
                <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl max-w-3xl">
                    <p class="text-gray-300">Kompaniya rahbari hali Storage tizimini sozlamagan. Iltimos kuting.</p>
                </div>
            @endif
        @else
            <!-- File Manager -->
            <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold text-white">Barcha Fayllar</h3>
                    
                    <form action="{{ route('storage.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                        @csrf
                        <input type="file" name="file" required class="text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-dark-border file:text-white hover:file:bg-gray-600 transition-all cursor-pointer">
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-accent hover:bg-accent-hover shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Yuklash (Max 50MB)
                        </button>
                    </form>
                </div>

                @if($files->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($files as $file)
                            <div class="bg-black/20 p-4 rounded-2xl border border-white/5 hover:border-accent/50 transition-all group">
                                <div class="h-32 bg-dark-bg/50 rounded-xl mb-4 flex items-center justify-center">
                                    @if(str_starts_with($file->file_type, 'image/'))
                                        <svg class="w-12 h-12 text-accent/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @elseif(str_starts_with($file->file_type, 'video/'))
                                        <svg class="w-12 h-12 text-blue-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    @else
                                        <svg class="w-12 h-12 text-gray-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @endif
                                </div>
                                <h4 class="text-white font-medium truncate text-sm" title="{{ $file->file_name }}">{{ $file->file_name }}</h4>
                                <p class="text-xs text-dark-muted mt-1">{{ number_format($file->file_size / 1024 / 1024, 2) }} MB • {{ $file->uploader->name ?? 'Tizim' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 border-2 border-dashed border-dark-border rounded-2xl">
                        <svg class="mx-auto h-12 w-12 text-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-white">Hozircha fayllar yo'q</h3>
                        <p class="mt-1 text-sm text-dark-muted">Yuqoridagi tugma orqali fayl yuklang.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
