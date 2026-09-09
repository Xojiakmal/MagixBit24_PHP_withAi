<div class="h-full bg-transparent text-white pb-10">
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Vazifalar taxtasi') }}</h2>
                <p class="text-dark-muted mt-1 text-sm">{{ __('Loyihalaringizdagi barcha vazifalarni interaktiv boshqaring') }}</p>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-accent hover:bg-accent-hover focus:outline-none transition-all duration-300 shadow-[0_0_20px_rgba(155,114,255,0.4)]">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Yangi Vazifa') }}
            </button>
        </div>

        <!-- View Tabs -->
        <div class="mb-6 flex items-center space-x-1 bg-white/5 p-1 rounded-xl w-fit border border-dark-border">
            <button wire:click="changeView('planner')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'planner' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                {{ __('Rejalashtiruvchi') }}
            </button>
            <button wire:click="changeView('list')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'list' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                {{ __('Ro\'yxat') }}
            </button>
            <button wire:click="changeView('deadline')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'deadline' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('Muddatlar') }}
            </button>
        </div>
        
        <!-- Main Content Area -->
        @if($currentView === 'planner')
            <!-- Planner Kanban Board -->
            <div x-data
                 @wheel="if (!$event.target.closest('.overflow-y-auto') && Math.abs($event.deltaY) > Math.abs($event.deltaX) && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar w-full" style="height: calc(100vh - 230px);">
                @foreach($statuses as $statusKey => $statusLabel)
                <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col"
                     x-data
                     @drop.prevent="$wire.updateTaskStatus(
                        event.dataTransfer.getData('task_id'),
                        '{{ $statusKey }}'
                     )"
                     @dragover.prevent>
                     
                    <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                            <!-- Colored dot based on status -->
                            <span class="w-2.5 h-2.5 rounded-full mr-2 
                                @if($statusKey == 'not_planned') bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]
                                @elseif($statusKey == 'in_progress') bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.8)]
                                @elseif($statusKey == 'this_week') bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.8)]
                                @else bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.8)] @endif
                            "></span>
                            {{ __($statusLabel) }}
                        </h3>
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                            {{ count($tasks[$statusKey] ?? []) }}
                        </span>
                    </div>
                    
                    <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-0">
                        @foreach($tasks[$statusKey] ?? [] as $task)
                        <!-- Task Card Component Included via Blade Component or Inline -->
                        <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

        @elseif($currentView === 'deadline')
            <!-- Deadline Kanban Board -->
            <div x-data
                 @wheel="if (!$event.target.closest('.overflow-y-auto') && Math.abs($event.deltaY) > Math.abs($event.deltaX) && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar h-full w-full">
                @foreach($deadlineGroups as $groupKey => $groupLabel)
                <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col"
                     x-data
                     @drop.prevent="$wire.updateTaskDeadline(
                        event.dataTransfer.getData('task_id'),
                        '{{ $groupKey }}'
                     )"
                     @dragover.prevent>
                     
                    <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full mr-2 
                                @if($groupKey == 'overdue') bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]
                                @elseif($groupKey == 'today') bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]
                                @elseif($groupKey == 'this_week') bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]
                                @elseif($groupKey == 'next_week') bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]
                                @elseif($groupKey == 'completed') bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.8)]
                                @else bg-gray-500 shadow-[0_0_8px_rgba(107,114,128,0.8)] @endif
                            "></span>
                            {{ __($groupLabel) }}
                        </h3>
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                            {{ count($deadlineTasks[$groupKey] ?? []) }}
                        </span>
                    </div>
                    
                    <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-0">
                        @foreach($deadlineTasks[$groupKey] ?? [] as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

        @elseif($currentView === 'list')
            <!-- List Data Table -->
            <div class="bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-slate-900/60 text-xs uppercase text-gray-400 border-b border-dark-border/50">
                            <tr>
                                <th scope="col" class="px-6 py-4">{{ __('Nom') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Muddat') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Mas\'ul') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Ustuvorlik') }}</th>
                                <th scope="col" class="px-6 py-4 text-right">{{ __('Amallar') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border/50">
                            @forelse($listTasks as $task)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4 font-medium text-white">
                                        {{ $task->title }}
                                        @if($task->status === 'review' || $task->status === 'done')
                                            <span class="ml-2 bg-green-500/10 text-green-400 text-xs px-2 py-0.5 rounded-full border border-green-500/20">Yakunlangan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->due_date)
                                            <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-400 font-bold' : '' }}">
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs mr-2 text-white">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        {{ $task->assigned_to ? 'User #' . $task->assigned_to : 'Belgilanmagan' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase
                                            @if($task->priority == 'high') bg-red-500/10 text-red-400 border border-red-500/20
                                            @elseif($task->priority == 'medium') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                            @else bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 @endif
                                        ">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                    @can('delete_task')
                                        <button wire:click="deleteTask({{ $task->id }})" class="text-red-400 hover:text-red-300 font-medium">O'chirish</button>
                                    @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Hech qanday vazifa topilmadi.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Create Task Slide-Over -->
        <x-slide-over wire:model="isCreatingTask" id="createTaskPanel" :title="__('Yangi vazifa qo\'shish')" maxWidth="6xl">
            <x-slot:actions>
                <button wire:click="saveTask" class="px-5 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                    {{ __('Saqlash') }}
                </button>
            </x-slot:actions>

            <!-- Split Pane Layout -->
            <div class="flex flex-col lg:flex-row w-full h-full">
                <!-- Left Pane: General Data -->
                <div class="w-full lg:w-2/3 p-6 space-y-6 overflow-y-auto border-r border-white/5">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Vazifa nomi') }}</label>
                            <input type="text" wire:model="newTaskTitle" placeholder="{{ __('Masalan: Yangi mijoz bilan bog\'lanish') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-lg font-medium">
                            @error('newTaskTitle') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">{{ __('Tavsifi') }}</label>
                            <textarea wire:model="newTaskDescription" rows="5" placeholder="{{ __('Vazifa bo\'yicha batafsil ma\'lumot...') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all custom-scrollbar"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Boshlanish sanasi') }}</label>
                            <input type="date" wire:model="newTaskStartDate" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Muddat') }}</label>
                            <input type="date" wire:model="newTaskDueDate" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                        </div>
                    </div>

                    <div class="p-5 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                        <h4 class="text-white font-semibold">{{ __('Qo\'shimcha parametrlar') }}</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Ustuvorlik') }}</label>
                            <div class="flex space-x-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="newTaskPriority" value="low" class="peer sr-only">
                                    <div class="text-center py-2 px-3 rounded-lg border border-dark-border text-gray-400 peer-checked:bg-blue-500/20 peer-checked:text-blue-400 peer-checked:border-blue-500/50 transition-all text-sm font-medium">{{ __('Past') }}</div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="newTaskPriority" value="medium" class="peer sr-only">
                                    <div class="text-center py-2 px-3 rounded-lg border border-dark-border text-gray-400 peer-checked:bg-amber-500/20 peer-checked:text-amber-400 peer-checked:border-amber-500/50 transition-all text-sm font-medium">{{ __('O\'rta') }}</div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="newTaskPriority" value="high" class="peer sr-only">
                                    <div class="text-center py-2 px-3 rounded-lg border border-dark-border text-gray-400 peer-checked:bg-red-500/20 peer-checked:text-red-400 peer-checked:border-red-500/50 transition-all text-sm font-medium">{{ __('Yuqori') }}</div>
                                </label>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Right Pane: Assignment & Observers -->
                <div class="w-full lg:w-1/3 p-6 bg-black/20 space-y-6">
                    <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-3">{{ __('Biriktirish') }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <!-- Custom Select for Task Assign Type -->
                            <div x-data="{ open: false, selected: @entangle('newTaskAssignType').live }" class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/40 border border-dark-border rounded-xl px-4 py-3 text-white text-sm focus:border-accent outline-none">
                                    <span x-text="selected === 'user' ? '{{ __('Xodimlarni tanlash') }}' : (selected === 'team' ? '{{ __('Jamoalarni tanlash') }}' : '{{ __('Barchaga ko\'rinadigan qilib') }}')"></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                    <div @click="selected = 'user'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Xodimlarni tanlash') }}</div>
                                    <div @click="selected = 'team'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Jamoalarni tanlash') }}</div>
                                    <div @click="selected = 'everyone'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Barchaga ko\'rinadigan qilib') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($newTaskAssignType === 'user')
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-1">{{ __('Xodimlarni tanlang') }}</label>
                                @livewire('search-dropdown', ['model' => 'App\Models\User', 'searchFields' => ['name', 'phone', 'address'], 'eventName' => 'assigneeSelected', 'placeholder' => __('Ism, telefon raqam yoki manzilni yozing...')])
                                @error('newTaskAssigneeIds') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                
                                @if(count($newTaskAssigneeIds) > 0)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($newTaskAssigneeIds as $id)
                                            @php $user = collect($users ?? [])->firstWhere('id', $id); @endphp
                                            @if($user)
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-accent/20 text-accent border border-accent/30">
                                                    {{ $user['name'] }}
                                                    <button type="button" class="ml-1 text-accent/70 hover:text-accent" wire:click="$set('newTaskAssigneeIds', {{ json_encode(array_diff($newTaskAssigneeIds, [$id])) }})">&times;</button>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif($newTaskAssignType === 'team')
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-1">{{ __('Jamoalarni tanlang') }}</label>
                                @php
                                    $teams = \App\Models\Team::select('id', 'name')->get()->toArray();
                                @endphp
                                @livewire('search-dropdown', ['model' => 'App\Models\Team', 'searchField' => 'name', 'eventName' => 'teamSelected', 'placeholder' => __('Jamoa qidirish...')])
                                @error('newTaskTeamIds') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                
                                @if(count($newTaskTeamIds) > 0)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($newTaskTeamIds as $id)
                                            @php $team = collect($teams)->firstWhere('id', $id); @endphp
                                            @if($team)
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                                    {{ $team['name'] }}
                                                    <button type="button" class="ml-1 text-blue-400/70 hover:text-blue-400" wire:click="$set('newTaskTeamIds', {{ json_encode(array_diff($newTaskTeamIds, [$id])) }})">&times;</button>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif($newTaskAssignType === 'everyone')
                            <div class="bg-accent/10 border border-accent/20 rounded-lg p-3">
                                <p class="text-sm text-accent">{{ __('Ushbu vazifa tizimdagi barcha xodimlarga jo\'natiladi.') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-white/10 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">{{ __('Kuzatuvchilar') }}</label>
                            @livewire('search-dropdown', ['model' => 'App\Models\User', 'searchFields' => ['name', 'phone', 'address'], 'eventName' => 'observerSelected', 'placeholder' => __('Kuzatuvchi qidirish...')])
                            
                            @if(count($newTaskObservers) > 0)
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($newTaskObservers as $idx => $observerId)
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-white/10 text-xs font-medium text-white border border-white/20">
                                            User #{{ $observerId }}
                                            <button type="button" class="ml-1 text-gray-400 hover:text-white" wire:click="$set('newTaskObservers', {{ json_encode(array_diff($newTaskObservers, [$observerId])) }})">
                                                &times;
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-slide-over>

        <!-- View Task Slide-Over -->
        @if($selectedTask)
        <x-slide-over wire:model="isViewingTask" id="viewTaskPanel" :title="__('Vazifa Tafsilotlari')" maxWidth="3xl">
            <x-slot:actions>
                <button wire:click="closeTaskView" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                    {{ __('Yopish') }}
                </button>
            </x-slot:actions>

            <div class="p-6 space-y-8">
                <!-- Header -->
                <div class="bg-black/20 border border-dark-border rounded-3xl p-6 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-accent/20 rounded-full blur-[50px] pointer-events-none"></div>
                    
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-2xl font-bold text-white">{{ $selectedTask->title }}</h2>
                        <span class="px-3 py-1 text-xs font-bold uppercase rounded border
                            @if($selectedTask->priority == 'high') bg-red-500/10 text-red-400 border-red-500/20
                            @elseif($selectedTask->priority == 'medium') bg-amber-500/10 text-amber-400 border-amber-500/20
                            @else bg-emerald-500/10 text-emerald-400 border-emerald-500/20 @endif">
                            {{ $selectedTask->priority }}
                        </span>
                    </div>

                    @if($selectedTask->description)
                        <p class="text-sm text-gray-300 mt-4 whitespace-pre-line">{{ $selectedTask->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                        <div>
                            <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Boshlanish') }}</p>
                            <p class="text-white font-medium">{{ $selectedTask->start_date ? \Carbon\Carbon::parse($selectedTask->start_date)->format('d.m.Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Muddat') }}</p>
                            <p class="text-white font-medium {{ \Carbon\Carbon::parse($selectedTask->due_date)->isPast() ? 'text-red-400' : '' }}">{{ $selectedTask->due_date ? \Carbon\Carbon::parse($selectedTask->due_date)->format('d.m.Y H:i') : __('Muddatsiz') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-muted font-semibold uppercase mb-1">{{ __('Holati') }}</p>
                            <p class="text-white font-medium capitalize">{{ $selectedTask->status }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deal Info Section -->
                @if($selectedTask->deal)
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ __('Biriktirilgan Bitim') }}</span>
                    </h3>
                    <a href="{{ route('crm.deals', ['pipelineId' => $selectedTask->deal->pipeline_id, 'highlightDeal' => $selectedTask->deal->id]) }}" class="block bg-white/5 hover:bg-white/10 transition-colors border border-white/10 rounded-xl p-5 group cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-bold text-white group-hover:text-accent transition-colors">{{ $selectedTask->deal->title }}</h4>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-sm font-semibold text-accent">$ {{ number_format($selectedTask->deal->amount, 0, ',', ' ') }}</span>
                                    <span class="text-xs text-gray-400 capitalize">{{ $selectedTask->deal->deal_type }}</span>
                                    <span class="text-xs text-gray-400">{{ $selectedTask->deal->start_date ? \Carbon\Carbon::parse($selectedTask->deal->start_date)->format('d.m.Y') : '' }}</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-white transition-colors transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </div>
                @endif
                
            </div>
        </x-slide-over>
        @endif
    </div>
</div>
