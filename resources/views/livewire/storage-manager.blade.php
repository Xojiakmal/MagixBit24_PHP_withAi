<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            {{ __('Kompaniya Ombri') }}
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
                    <h3 class="text-xl font-bold text-white mb-4">{{ __('Storage tizimini sozlash') }}</h3>
                    <div class="text-gray-300 space-y-4 mb-8 text-sm">
                        <p>{!! __('Fayllarni markazlashgan holda saqlash uchun Telegram Yopiq Guruhini tizimga ulashingiz kerak:') !!}</p>
                        <ol class="list-decimal pl-5 space-y-2 text-accent">
                            <li>{!! __('Telegramda yangi <b>Yopiq guruh</b> yarating.') !!}</li>
                            <li>{!! __('Tizim botini shu guruhga qo\'shing.', ['bot' => '@' . env('TELEGRAM_BOT_USERNAME', 'WipeBitrixBot')]) !!}</li>
                            <li>{!! __('Botga guruhda <b>Admin</b> huquqlarini bering.') !!}</li>
                            <li>{!! __('Guruhga quyidagi maxsus buyruqni yuboring:') !!}</li>
                        </ol>
                        
                        <div class="mt-4 p-4 bg-black/40 border border-dark-border rounded-xl">
                            <p class="text-white font-mono text-center text-lg select-all cursor-pointer">
                                /setstorage {{ $tenant->unique_link }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl max-w-3xl">
                    <p class="text-gray-300">{{ __('Kompaniya rahbari hali Storage tizimini sozlamagan. Iltimos kuting.') }}</p>
                </div>
            @endif
        @else
            <!-- File Manager -->
            <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-2 text-white/80">
                        <button wire:click="$set('currentFolderId', null); updateBreadcrumbs()" class="hover:text-accent transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </button>
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <span>/</span>
                            <button wire:click="openFolder({{ $breadcrumb->id }})" class="hover:text-accent transition-colors truncate max-w-[150px]">
                                {{ $breadcrumb->name }}
                            </button>
                        @endforeach
                    </div>

                    @if(Auth::user()->can('manage_storage') || Auth::user()->hasRole('Admin'))
                    <div class="flex items-center space-x-4">
                        @if($isCreatingFolder)
                            <div class="flex items-center space-x-2">
                                <input type="text" wire:model.defer="newFolderName" placeholder="{{ __('Papka nomi') }}" class="bg-black/40 border border-dark-border rounded-xl text-white px-3 py-1.5 text-sm focus:outline-none focus:border-accent w-40">
                                <button wire:click="saveFolder" class="text-green-400 hover:text-green-300">✓</button>
                                <button wire:click="$set('isCreatingFolder', false)" class="text-red-400 hover:text-red-300">✕</button>
                            </div>
                        @else
                            <button wire:click="$set('isCreatingFolder', true)" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-dark-bg border border-dark-border hover:border-accent/50 transition-all">
                                + {{ __('Yangi papka') }}
                            </button>
                        @endif

                        <div class="flex items-center space-x-2">
                            <input type="file" wire:model="fileToUpload" id="fileUpload" class="hidden">
                            <label for="fileUpload" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-accent hover:bg-accent-hover shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-all cursor-pointer">
                                <svg wire:loading.remove wire:target="fileToUpload" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <svg wire:loading wire:target="fileToUpload" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('Fayl tanlash') }}
                            </label>

                            @if($fileToUpload)
                                <button wire:click="uploadFile" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-green-400 bg-green-400/10 border border-green-400/20 hover:bg-green-400/20 transition-all">
                                    {{ __('Yuklash') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                @if($folders->count() == 0 && $files->count() == 0)
                    <div class="text-center py-12 border-2 border-dashed border-dark-border rounded-2xl">
                        <svg class="mx-auto h-12 w-12 text-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-white">{{ __('Bo\'sh ombor') }}</h3>
                        <p class="mt-1 text-sm text-dark-muted">{{ __('Bu papkada hech narsa yo\'q.') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <!-- Folders -->
                        @foreach($folders as $folder)
                            <div class="bg-black/20 p-4 rounded-2xl border border-white/5 hover:border-accent/50 transition-all group relative cursor-pointer overflow-hidden" wire:click="openFolder({{ $folder->id }})">
                                <!-- Glassmorphism decorations -->
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-accent/20 rounded-full blur-[30px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <svg class="w-8 h-8 text-yellow-500/80" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                    <h4 class="text-white font-medium truncate text-sm flex-1">{{ $folder->name }}</h4>
                                    
                                    @if(Auth::user()->can('manage_storage') || Auth::user()->hasRole('Admin'))
                                    <button wire:click.stop="deleteFolder({{ $folder->id }})" class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-300 transition-opacity p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <!-- Files -->
                        @foreach($files as $file)
                            <div class="bg-black/20 p-4 rounded-2xl border border-white/5 hover:border-accent/50 transition-all group relative overflow-hidden">
                                <!-- Glassmorphism decorations -->
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/20 rounded-full blur-[30px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                <div class="h-32 bg-dark-bg/50 rounded-xl mb-4 flex items-center justify-center relative">
                                    @if(str_starts_with($file->file_type, 'image/'))
                                        <svg class="w-12 h-12 text-accent/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @elseif(str_starts_with($file->file_type, 'video/'))
                                        <svg class="w-12 h-12 text-blue-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    @else
                                        <svg class="w-12 h-12 text-gray-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @endif
                                </div>
                                @if($isRenamingFile && $renamingFileId == $file->id)
                                    <div class="flex items-center space-x-2 mt-2">
                                        <input type="text" wire:model.defer="newFileName" class="bg-black/40 border border-dark-border rounded-lg px-2 py-1 text-sm text-white focus:outline-none focus:border-accent w-full">
                                        <button wire:click="saveFileName" class="text-green-400 hover:text-green-300">✓</button>
                                        <button wire:click="$set('isRenamingFile', false)" class="text-red-400 hover:text-red-300">✕</button>
                                    </div>
                                @else
                                    <h4 class="text-white font-medium truncate text-sm" title="{{ $file->file_name }}">{{ $file->file_name }}</h4>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-xs text-dark-muted">{{ number_format($file->file_size / 1024 / 1024, 2) }} MB</p>
                                    
                                    <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="downloadFile({{ $file->id }})" class="text-blue-400 hover:text-blue-300" title="{{ __('Yuklab olish') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </button>
                                        @if(Auth::user()->can('manage_storage') || Auth::user()->hasRole('Admin'))
                                        <button wire:click="startRenamingFile({{ $file->id }}, '{{ addslashes($file->file_name) }}')" class="text-yellow-400 hover:text-yellow-300" title="{{ __('Nomini o\'zgartirish') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button wire:click="deleteFile({{ $file->id }})" class="text-red-400 hover:text-red-300" title="{{ __('O\'chirish') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
