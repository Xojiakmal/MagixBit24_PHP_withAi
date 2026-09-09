<div wire:poll.5s class="mt-8">
    
    @if(auth()->user()->hasRole('Admin'))
        <div class="mb-6 bg-black/20 border border-dark-border p-4 rounded-2xl flex items-center justify-between">
            <div>
                <h4 class="text-white font-semibold">{{ __('Tarixni qayta tiklash') }}</h4>
                <p class="text-xs text-dark-muted">{{ __('Storage\'dan yuklab olingan .json/.log faylni yuklab, eski ma\'lumotlarni tiklang') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <input type="file" wire:model="logFile" accept=".json,.log" class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 cursor-pointer">
                <button wire:click="restoreLogs" class="px-4 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-xl shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-colors" wire:loading.attr="disabled">{{ __('Tiklash') }}</button>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="mb-4 text-green-400 text-sm bg-green-400/10 p-3 rounded-xl border border-green-400/20">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 text-red-400 text-sm bg-red-400/10 p-3 rounded-xl border border-red-400/20">{{ session('error') }}</div>
        @endif
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Activity Widget -->
    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <!-- Glassmorphism decorations -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                {{ __('Xodimlar Faoliyati') }}
            </h3>
            <button wire:click="expandActivity" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-colors text-gray-400 hover:text-white" title="{{ __('Kattalashtirish') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-2">
            @forelse($activities as $activity)
                @php $details = $this->getActivityDetails($activity); @endphp
                <div class="flex space-x-3 items-start border-l-2 border-accent/30 pl-4 py-1 relative">
                    <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-black border-2 border-accent"></div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <p class="text-sm text-gray-300 leading-snug">{!! $details['message'] !!}</p>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $details['badgeBg'] }} {{ $details['color'] }} shrink-0">
                                {{ $details['severity'] }}
                            </span>
                        </div>
                        <p class="text-xs text-dark-muted mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center">{{ __('Hech qanday faoliyat mavjud emas.') }}</p>
            @endforelse
        </div>
    </div>

    <!-- History Widget -->
    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <!-- Glassmorphism decorations -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-400/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('O\'zgarishlar Tarixi') }}
            </h3>
            <button wire:click="expandHistory" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-colors text-gray-400 hover:text-white" title="{{ __('Kattalashtirish') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-2">
            @forelse($history as $hist)
                @php $details = $this->getHistoryDetails($hist); @endphp
                <div class="bg-white/5 p-3 rounded-xl border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full {{ str_replace('text', 'bg', $details['color']) }}"></div>
                    <div class="flex justify-between items-start mb-2 pl-2">
                        <div class="flex-1">
                            <p class="text-sm text-gray-300 leading-snug">{!! $details['message'] !!}</p>
                        </div>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $details['badgeBg'] }} {{ $details['color'] }} shrink-0">
                            {{ $details['severity'] }}
                        </span>
                    </div>
                    <p class="text-xs text-dark-muted mt-1 pl-2">{{ $hist->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center">{{ __('Tarix bo\'sh.') }}</p>
            @endforelse
        </div>
        </div>
    </div>

    <!-- Expanded Activity Modal -->
    <x-slide-over wire:model="isExpandedActivity" id="expandedActivity" :title="__('Barcha Faoliyatlar')" maxWidth="4xl">
        <x-slot:actions>
            <button wire:click="collapseActivity" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                {{ __('Yopish') }}
            </button>
        </x-slot:actions>
        
        <div class="p-6">
            <div class="space-y-6">
                @foreach($activities as $activity)
                    @php $details = $this->getActivityDetails($activity); @endphp
                    <div class="flex space-x-4 items-start border-b border-dark-border/50 pb-4 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full {{ str_replace('text', 'bg', $details['color']) }}"></div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent to-purple-600 flex items-center justify-center text-white font-bold shrink-0 shadow-lg ml-2">
                            {{ substr($activity->user->name ?? 'T', 0, 1) }}
                        </div>
                        <div class="flex-1 pr-2">
                            <div class="flex justify-between items-start">
                                <p class="text-base text-gray-200 leading-snug">
                                    {!! $details['message'] !!}
                                </p>
                                <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $details['badgeBg'] }} {{ $details['color'] }} shrink-0">
                                    {{ $details['severity'] }}
                                </span>
                            </div>
                            <span class="text-xs text-dark-muted font-mono mt-2 block">{{ $activity->created_at->format('d.m.Y H:i:s') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slide-over>

    <!-- Expanded History Modal -->
    <x-slide-over wire:model="isExpandedHistory" id="expandedHistory" :title="__('Barcha O\'zgarishlar Tarixi')" maxWidth="5xl">
        <x-slot:actions>
            <button wire:click="collapseHistory" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                {{ __('Yopish') }}
            </button>
        </x-slot:actions>
        
        <div class="p-6">
            <div class="bg-black/20 border border-dark-border rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-black/40 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-1/6">{{ __('Sana va Vaqt') }}</th>
                            <th class="px-6 py-4 font-semibold w-1/6">{{ __('Xodim') }}</th>
                            <th class="px-6 py-4 font-semibold w-1/6">{{ __('Obyekt') }}</th>
                            <th class="px-6 py-4 font-semibold w-1/2">{{ __('O\'zgarishlar') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50">
                        @foreach($history as $hist)
                            @php $details = $this->getHistoryDetails($hist); @endphp
                            <tr class="hover:bg-white/5 transition-colors relative">
                                <td class="px-6 py-4 text-xs font-mono text-dark-muted relative">
                                    <div class="absolute top-0 left-0 w-1 h-full {{ str_replace('text', 'bg', $details['color']) }}"></div>
                                    {{ $hist->created_at->format('d.m.Y') }}<br>
                                    <span class="text-white">{{ $hist->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-white">{{ $hist->user->name ?? __('Tizim') }}</td>
                                <td class="px-6 py-4 text-blue-400">
                                    {{ class_basename($hist->subject_type) }} #{{ $hist->subject_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm text-gray-300 leading-snug">{!! $details['message'] !!}</p>
                                            <span class="ml-4 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $details['badgeBg'] }} {{ $details['color'] }} shrink-0">
                                                {{ $details['severity'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-slide-over>

</div>
