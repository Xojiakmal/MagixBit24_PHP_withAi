<div class="h-full bg-transparent text-white pb-10">
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Bitimlar Voronkasi') }}</h2>
                <p class="text-dark-muted mt-1 text-sm">{{ __('Savdo jarayonini interaktiv Kanban orqali kuzating') }}</p>
            </div>
            @can('create_deal')
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-accent hover:bg-accent-hover focus:outline-none transition-all duration-300 shadow-[0_0_20px_rgba(155,114,255,0.4)]">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Yangi Bitim') }}
            </button>
            @endcan
        </div>

        <!-- View Tabs -->
        <div class="mb-6 flex items-center space-x-1 bg-white/5 p-1 rounded-xl w-fit border border-dark-border">
            <button wire:click="setView('kanban')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'kanban' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                {{ __('Kanban') }}
            </button>
            <button wire:click="setView('list')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'list' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                {{ __('Ro\'yxat') }} (List)
            </button>
            <button wire:click="setView('activities')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'activities' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('Faoliyatlar') }} (Activities)
            </button>
        </div>
        
        <!-- View Content -->
        @if($currentView === 'kanban')
        <!-- Kanban Board -->
            <div x-data
                 @wheel="if ($event.deltaY !== 0 && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar h-full w-full">
            @foreach($stages as $stage)
            <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col"
                 x-data
                 @drop.prevent="$wire.updateDealStage(
                    event.dataTransfer.getData('deal_id'),
                    {{ $stage->id }}
                 )"
                 @dragover.prevent>
                 
                <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white tracking-wider flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full mr-2 bg-accent shadow-[0_0_8px_rgba(155,114,255,0.8)]"></span>
                        {{ $stage->name }}
                    </h3>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                        {{ count($deals[$stage->id] ?? []) }} ta
                    </span>
                </div>
                
                <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-[500px]">
                    @foreach($deals[$stage->id] ?? [] as $deal)
                    <div class="bg-slate-800/60 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-dark-border cursor-pointer hover:border-accent hover:shadow-[0_0_15px_rgba(155,114,255,0.2)] transition-all duration-300 group relative overflow-hidden"
                         draggable="true"
                         @dragstart="event.dataTransfer.setData('deal_id', {{ $deal->id }})"
                         wire:click="viewDeal({{ $deal->id }})">
                         
                        <!-- Glassmorphism decorations -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                         
                        <!-- Delete Button (Visible on hover) -->
                        <button wire:click="deleteDeal({{ $deal->id }})" class="absolute top-4 right-4 text-dark-muted hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="font-semibold text-white pr-6 text-lg">{{ $deal->title }}</div>
                        <div class="text-sm font-bold text-accent mt-2">$ {{ number_format($deal->amount, 0, ',', ' ') }}</div>
                        
                        @php
                            $assignedUsers = \App\Models\User::whereIn('id', $deal->assigned_users ?? [])->pluck('name')->toArray();
                            $assignedRoles = \App\Models\Role::whereIn('id', $deal->assigned_roles ?? [])->pluck('name')->toArray();
                            $assignedTeams = \App\Models\Team::whereIn('id', $deal->assigned_teams ?? [])->pluck('name')->toArray();
                            $assignees = array_merge($assignedUsers, $assignedRoles, $assignedTeams);
                        @endphp
                        <div class="mt-3 text-xs text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="truncate">{{ !empty($assignees) ? implode(', ', $assignees) : 'Biriktirilmagan' }}</span>
                        </div>
                        
                        @if($deal->contact)
                            <div class="text-xs text-dark-muted mt-4 pt-4 border-t border-dark-border/50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-dark-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $deal->contact->name }}
                                </div>
                                <a href="{{ route('projects.tasks', ['dealId' => $deal->id]) }}" class="text-accent hover:text-accent-hover text-xs font-semibold flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    {{ __('Vazifalar') }}
                                </a>
                            </div>
                        @else
                            <div class="text-xs text-dark-muted mt-4 pt-4 border-t border-dark-border/50 flex justify-end">
                                <a href="{{ route('projects.tasks', ['dealId' => $deal->id]) }}" class="text-accent hover:text-accent-hover text-xs font-semibold flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    {{ __('Vazifalar') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @elseif($currentView === 'list')
        <!-- List View -->
        <div class="bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-slate-900/60 text-xs uppercase text-gray-400 border-b border-dark-border/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Deal Name</th>
                            <th scope="col" class="px-6 py-4 font-bold">Stage</th>
                            <th scope="col" class="px-6 py-4 font-bold">Client</th>
                            <th scope="col" class="px-6 py-4 font-bold">Amount</th>
                            <th scope="col" class="px-6 py-4 font-bold">Responsible</th>
                            <th scope="col" class="px-6 py-4 font-bold">Created</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50">
                        @forelse($listDeals as $deal)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $deal->title }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                                        {{ $deal->stage->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($deal->contact)
                                        <div class="text-white">{{ $deal->contact->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $deal->contact->phone }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-accent">
                                    $ {{ number_format($deal->amount, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $deal->assignee->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ $deal->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('projects.tasks', ['dealId' => $deal->id]) }}" class="text-accent hover:text-accent-hover text-xs font-semibold inline-flex items-center">
                                        Vazifalar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    {{ __('Hech qanday bitim topilmadi.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @elseif($currentView === 'activities')
        <!-- Activities View -->
            <div x-data
                 @wheel="if ($event.deltaY !== 0 && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar h-full w-full">
            @php
                $columns = [
                    'overdue' => ['title' => 'Overdue'],
                    'today' => ['title' => 'Due today'],
                    'this_week' => ['title' => 'Due this week'],
                    'next_week' => ['title' => 'Due next week'],
                    'idle' => ['title' => 'Idle'],
                    'later' => ['title' => 'Due later'],
                ];
            @endphp
            @foreach($columns as $key => $col)
            <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col">
                <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full mr-2 
                            @if($key == 'overdue') bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]
                            @elseif($key == 'today') bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]
                            @elseif($key == 'this_week') bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]
                            @elseif($key == 'next_week') bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]
                            @elseif($key == 'idle') bg-gray-500 shadow-[0_0_8px_rgba(107,114,128,0.8)]
                            @else bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)] @endif
                        "></span>
                        {{ $col['title'] }}
                    </h3>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                        {{ count($activityDeals[$key] ?? []) }}
                    </span>
                </div>
                
                <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-[500px]">
                    @foreach($activityDeals[$key] ?? [] as $deal)
                    <div class="bg-slate-800/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-dark-border hover:border-accent transition-colors group relative overflow-hidden">
                        <!-- Glassmorphism decorations -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="font-semibold text-white pr-6 text-base">{{ $deal->title }}</div>
                        <div class="text-xs font-bold text-accent mt-1">$ {{ number_format($deal->amount, 0, ',', ' ') }}</div>
                        
                        @if($deal->contact)
                            <div class="text-xs text-blue-400 mt-2">{{ $deal->contact->name }}</div>
                        @endif
                        
                        <div class="text-xs text-dark-muted mt-3 pt-3 border-t border-dark-border/50 flex justify-between items-center">
                            <span class="text-gray-500">{{ $deal->stage->name ?? 'Bosqichsiz' }}</span>
                            <a href="{{ route('projects.tasks', ['dealId' => $deal->id]) }}" class="text-gray-400 hover:text-white flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                {{ __('Vazifalar') }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Create Deal Slide-Over -->
        <x-slide-over wire:model="isCreatingDeal" id="createDealPanel" title="{{ __('Yangi bitim (Deal) qo\'shish') }}" maxWidth="6xl">
            <x-slot:actions>
                <button wire:click="saveDeal" class="px-5 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                    {{ __('Saqlash') }}
                </button>
            </x-slot:actions>

            <!-- Split Pane Layout -->
            <div class="flex flex-col lg:flex-row w-full h-full">
                <!-- Left Pane: General Data -->
                <div class="w-full lg:w-2/3 p-6 space-y-6 overflow-y-auto border-r border-white/5">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Bitim nomi') }}</label>
                            <input type="text" wire:model="newDealTitle" placeholder="{{ __('Masalan: Web sayt yaratish xizmati') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-lg font-medium">
                            @error('newDealTitle') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Summasi va Valyuta (Amount and currency)') }}</label>
                            <div class="flex space-x-2 relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">$</span>
                                <input type="tel" wire:model="newDealAmount" placeholder="0.00" class="w-full bg-white/5 border border-dark-border rounded-xl pl-8 pr-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-lg font-medium">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Mijoz (Client)') }} *</label>
                            <input type="text" wire:model="newDealClientName" placeholder="{{ __('Mijoz ismi') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            @error('newDealClientName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Telefon raqam (Phone)') }} *</label>
                            <input type="tel" wire:model="newDealClientPhone" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" placeholder="+998 90 123 45 67" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            @error('newDealClientPhone') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Boshlanish vaqti') }}</label>
                            <input type="datetime-local" wire:model="newDealStartDate" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Tugash vaqti') }}</label>
                            <input type="datetime-local" wire:model="newDealEndDate" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>
                    </div>

                    <div class="p-5 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Mas\'ullar (Xodimlar, Rollar, Jamoalar)') }}</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Xodimlar') }}</h5>
                                    @foreach($this->allUsers as $u)
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedUsers" value="{{ $u->id }}" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform">{{ $u->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Rollar') }}</h5>
                                    @foreach(\App\Models\Role::whereNotIn('name', ['Admin'])->get() as $r)
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedRoles" value="{{ $r->id }}" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform">{{ $r->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Jamoalar') }}</h5>
                                    @foreach(\App\Models\Team::all() as $t)
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedTeams" value="{{ $t->id }}" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform">{{ $t->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="text-white font-semibold mt-4">{{ __('Qo\'shimcha parametrlar') }}</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Custom Select for Deal Type -->
                            <div x-data="{ open: false, selected: @entangle('newDealType').defer }" class="relative">
                                <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Bitim turi') }} (Deal Type)</label>
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <span x-text="selected === 'regular' ? '{{ __('Oddiy savdo') }}' : (selected === 'service' ? '{{ __('Xizmat ko\'rsatish') }}' : (selected === 'complex' ? '{{ __('Kompleks sotuv') }}' : (selected === 'delivery' ? '{{ __('Yetkazib berish') }}' : '{{ __('Tanlang') }}')))"></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden" style="display: none;">
                                    <div @click="selected = 'regular'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Oddiy savdo') }}</div>
                                    <div @click="selected = 'service'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Xizmat ko\'rsatish') }}</div>
                                    <div @click="selected = 'complex'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Kompleks sotuv') }}</div>
                                    <div @click="selected = 'delivery'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Yetkazib berish') }}</div>
                                </div>
                            </div>
                            
                            <!-- Custom Select for Source -->
                            <div x-data="{ open: false, selected: @entangle('newDealSource').defer }" class="relative">
                                <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Manba') }} (Source)</label>
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <span x-text="
                                        selected === 'telegram' ? 'Telegram' : 
                                        (selected === 'call' ? '{{ __('Qo\'ng\'iroq') }}' : 
                                        (selected === 'email' ? 'Email' : 
                                        (selected === 'website' ? '{{ __('Veb-sayt') }}' : 
                                        (selected === 'admin' ? '{{ __('Admin (Kompaniya egasi)') }}' : 
                                        (selected === 'other' ? '{{ __('Boshqa') }}' : '{{ __('Tanlang') }}')))))
                                    "></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                    <div @click="selected = 'telegram'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Telegram</div>
                                    <div @click="selected = 'call'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Qo\'ng\'iroq') }}</div>
                                    <div @click="selected = 'email'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Email</div>
                                    <div @click="selected = 'website'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Veb-sayt') }}</div>
                                    <div @click="selected = 'admin'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Admin (Kompaniya egasi)') }}</div>
                                    <div @click="selected = 'other'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Boshqa') }}</div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Tafsilotlar (Description)') }}</label>
                            <textarea wire:model="newDealDescription" rows="4" placeholder="{{ __('Bitim tafsilotlari...') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all custom-scrollbar"></textarea>
                        </div>
                        
                    </div>
                </div>

                <!-- Right Pane: Products -->
                <div class="w-full lg:w-1/3 p-6 bg-black/20 space-y-6">
                    <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-3">{{ __('Mahsulotlar') }} (Products)</h3>
                    
                    <div class="flex space-x-2 border-b border-dark-border mb-4">
                        <button wire:click="$set('productTab', 'select')" class="pb-2 text-sm font-medium transition-colors border-b-2 {{ $productTab === 'select' ? 'text-accent border-accent' : 'text-gray-500 border-transparent hover:text-gray-300' }}">{{ __('Tanlash') }}</button>
                        <button wire:click="$set('productTab', 'create')" class="pb-2 text-sm font-medium transition-colors border-b-2 {{ $productTab === 'create' ? 'text-accent border-accent' : 'text-gray-500 border-transparent hover:text-gray-300' }}">{{ __('Yangi qo\'shish') }}</button>
                    </div>

                    @if($productTab === 'select')
                        <div class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar pr-2">
                            <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Barcha mahsulotlar') }} (All Products)</label>
                            @forelse($this->allProducts as $product)
                                <div class="flex justify-between items-center p-3 bg-white/5 border border-dark-border rounded-lg hover:border-accent/50 transition-colors group">
                                    <div class="flex-1 cursor-pointer" wire:click="addProductFromList({{ $product->id }})" title="{{ __('Bitimga qo\'shish') }}">
                                        <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ __('O\'lchov') }}: {{ $product->unit }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Add button (visible on hover) -->
                                        <button wire:click="addProductFromList({{ $product->id }})" class="text-accent opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-white/10 rounded" title="{{ __('Qo\'shish') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                        <!-- Delete from DB button -->
                                        <button wire:click.stop="deleteProductFromDb({{ $product->id }})" class="text-gray-500 hover:text-red-400 p-1 hover:bg-white/10 rounded transition-colors" title="{{ __('Bazadan o\'chirish') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 text-center py-4 border border-dashed border-dark-border rounded-lg">
                                    {{ __('Hozircha mahsulot yo\'q. "Yangi qo\'shish" orqali yarating.') }}
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="space-y-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Mahsulot nomi') }}</label>
                                <input type="text" wire:model="newProductName" placeholder="{{ __('Masalan: Veb sayt yaratish') }}" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                @error('newProductName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-2">{{ __('O\'lchov birligi') }}</label>
                                <select wire:model="newProductUnit" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <option value="dona">dona</option>
                                    <option value="kg">kg</option>
                                    <option value="litr">litr</option>
                                    <option value="sentner">sentner</option>
                                    <option value="tonna">tonna</option>
                                    <option value="metr">metr</option>
                                    <option value="kv.m">kv.m</option>
                                    <option value="oy">oy</option>
                                </select>
                            </div>
                            <button wire:click="createAndAddProduct" class="w-full py-2 bg-accent/20 text-accent border border-accent/50 rounded-lg hover:bg-accent hover:text-white transition-colors text-sm font-medium">
                                {{ __('Yaratish va Biriktirish') }}
                            </button>
                        </div>
                    @endif

                    <!-- Selected Products List -->
                    <div class="pt-6 border-t border-white/10 space-y-4">
                        <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Biriktirilgan mahsulotlar') }}</label>
                        
                        @if(count($dealProducts) > 0)
                            <div class="space-y-2">
                                @foreach($dealProducts as $index => $product)
                                    <div class="flex justify-between items-center p-3 bg-white/5 border border-dark-border rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $product['name'] }}</div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center space-x-1 border border-dark-border rounded px-2 py-1 bg-black/30">
                                                <input type="tel" wire:model.live="dealProducts.{{ $index }}.quantity" class="w-12 bg-transparent text-white text-sm text-center outline-none">
                                                <span class="text-xs text-gray-400">{{ $product['unit'] ?? 'dona' }}</span>
                                            </div>
                                            <button wire:click="removeProduct({{ $index }})" class="text-gray-500 hover:text-red-400 transition-colors" title="{{ __('Ro\'yxatdan olib tashlash') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500 text-center py-4 border border-dashed border-dark-border rounded-lg">
                                {{ __('Hozircha mahsulot qo\'shilmagan') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slide-over>

    <!-- Deal Details Slide-Over -->
    @if($selectedDeal)
    <x-slide-over wire:model="isViewingDeal" id="viewDealPanel" title="{{ __('Bitim Tafsilotlari') }}" maxWidth="4xl">
        <x-slot:actions>
            <a href="{{ route('projects.tasks', ['dealId' => $selectedDeal->id]) }}" class="px-5 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                {{ __('Vazifalarga o\'tish') }}
            </a>
            <button wire:click="closeDealView" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                {{ __('Yopish') }}
            </button>
        </x-slot:actions>

        <div class="p-6 space-y-8">
            <!-- Header Section -->
            <div class="bg-black/20 border border-dark-border rounded-3xl p-6 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-accent/20 rounded-full blur-[50px] pointer-events-none"></div>
                
                <h2 class="text-3xl font-bold text-white mb-2">{{ $selectedDeal->title }}</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-black text-accent">$ {{ number_format($selectedDeal->amount, 0, ',', ' ') }}</span>
                    <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-gray-300 border border-white/10">
                        {{ $selectedDeal->stage->name ?? 'Noma\'lum' }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Mijoz') }}</p>
                        <p class="text-white font-medium">{{ $selectedDeal->contact->name ?? __('Noma\'lum') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Telefon') }}</p>
                        <p class="text-white font-medium">{{ $selectedDeal->contact->phone ?? __('Noma\'lum') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Boshlanish') }}</p>
                        <p class="text-white font-medium">{{ $selectedDeal->start_date ? $selectedDeal->start_date->format('d.m.Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Tugash') }}</p>
                        <p class="text-white font-medium">{{ $selectedDeal->end_date ? $selectedDeal->end_date->format('d.m.Y H:i') : __('Cheklanmagan') }}</p>
                    </div>
                </div>
            </div>

            <!-- Assignments Section -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">{{ __('Mas\'ullar (Biriktirilganlar)') }}</h3>
                
                @if(auth()->user()->hasRole('Admin') || auth()->user()->can('assign_deal') || auth()->user()->managerOf)
                    <!-- Multi-assignment Form Component pattern -->
                    <div x-data="{ 
                        users: {{ json_encode($selectedDeal->assigned_users ?? []) }}, 
                        roles: {{ json_encode($selectedDeal->assigned_roles ?? []) }}, 
                        teams: {{ json_encode($selectedDeal->assigned_teams ?? []) }},
                        save() {
                            $wire.updateDealAssignments({{ $selectedDeal->id }}, this.users, this.roles, this.teams);
                        }
                    }">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Xodimlar') }}</h5>
                                @foreach($this->allUsers as $u)
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="{{ $u->id }}" x-model.number="users" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform">{{ $u->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Rollar') }}</h5>
                                @foreach(\App\Models\Role::whereNotIn('name', ['Admin'])->get() as $r)
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="{{ $r->id }}" x-model.number="roles" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform">{{ $r->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2">{{ __('Jamoalar') }}</h5>
                                @foreach(\App\Models\Team::all() as $t)
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="{{ $t->id }}" x-model.number="teams" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform">{{ $t->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-gray-300 text-sm">
                            @php
                                $assignedUsers = \App\Models\User::whereIn('id', $selectedDeal->assigned_users ?? [])->pluck('name')->toArray();
                                $assignedRoles = \App\Models\Role::whereIn('id', $selectedDeal->assigned_roles ?? [])->pluck('name')->toArray();
                                $assignedTeams = \App\Models\Team::whereIn('id', $selectedDeal->assigned_teams ?? [])->pluck('name')->toArray();
                                $assignees = array_merge($assignedUsers, $assignedRoles, $assignedTeams);
                            @endphp
                            {{ !empty($assignees) ? implode(', ', $assignees) : 'Biriktirilmagan' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-bold text-white mb-4">Qo'shimcha ma'lumotlar</h3>
                    <div class="bg-black/20 border border-dark-border rounded-xl p-5 space-y-4">
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold">Bitim turi</span>
                            <p class="text-white mt-1 capitalize">{{ $selectedDeal->deal_type }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold">Manba (Source)</span>
                            <p class="text-white mt-1 capitalize">{{ $selectedDeal->source }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold">{{ __('Tafsilotlar') }}</span>
                            <p class="text-white mt-1 text-sm whitespace-pre-line">{{ $selectedDeal->description ?: __('Kiritilmagan') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-4">{{ __('Mahsulotlar') }} (Products)</h3>
                    @if($selectedDeal->products->count() > 0)
                        <div class="bg-black/20 border border-dark-border rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm text-gray-300">
                                <thead class="bg-black/40 text-xs text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">{{ __('Nomi') }}</th>
                                        <th class="px-4 py-3 font-semibold text-center">{{ __('Miqdori') }}</th>
                                        <th class="px-4 py-3 font-semibold text-right">{{ __('Narxi') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-dark-border/50">
                                    @foreach($selectedDeal->products as $product)
                                        <tr>
                                            <td class="px-4 py-3">{{ $product->name }}</td>
                                            <td class="px-4 py-3 text-center">{{ $product->pivot->quantity }} {{ $product->unit }}</td>
                                            <td class="px-4 py-3 text-right text-accent font-semibold">${{ number_format($product->price, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-white/5 border border-dashed border-white/10 rounded-xl p-6 text-center text-gray-400 text-sm">
                            {{ __('Mahsulotlar qo\'shilmagan') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-slide-over>
    @endif
</div>
