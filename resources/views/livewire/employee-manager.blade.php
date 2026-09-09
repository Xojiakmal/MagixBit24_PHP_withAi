<div class="h-full bg-transparent text-white pb-10">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Xodimlar va Jamoalar') }}</h2>
            <p class="text-dark-muted mt-1 text-sm">{{ __('Kompaniya xodimlari va guruhlarni boshqarish') }}</p>
        </div>
    </div>

    <!-- View Tabs -->
    <div class="mb-6 flex items-center space-x-1 bg-white/5 p-1 rounded-xl w-fit border border-dark-border">
        <button wire:click="switchTab('employees')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeTab === 'employees' ? 'bg-white/10 text-white shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
            {{ __('Xodimlar') }}
        </button>
        <button wire:click="switchTab('teams')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeTab === 'teams' ? 'bg-white/10 text-white shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
            {{ __('Guruhlar') }}
        </button>
        @if(Auth::user()->hasRole('Admin') || Auth::user()->can('manage_employees'))
        <button wire:click="switchTab('roles')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $activeTab === 'roles' ? 'bg-white/10 text-white shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
            {{ __('Rollar va Ruxsatlar') }}
        </button>
        @endif
    </div>

    @if($activeTab === 'employees')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative group">
                    <!-- Glassmorphism decorations -->
                    <!-- Hover Glow Animation -->
                    <div class="absolute inset-0 bg-accent/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none rounded-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none group-hover:scale-150"></div>
                    <div class="relative z-10 flex items-center mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $user->name }}</h3>
                            <p class="text-xs text-dark-muted mt-1">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 relative z-10 mt-6">
                        @if(Auth::user()->hasRole('Admin') || Auth::user()->can('manage_employees') || Auth::user()->managerOf)
                            @if(!$user->hasRole('Admin'))
                                <div>
                                    <label class="block text-xs text-dark-muted mb-1 font-medium">{{ __('Xodim roli:') }}</label>
                                        <!-- Custom Select for Role -->
                                        <div x-data="{ open: false, selected: '{{ $user->roles->first()->name ?? '' }}' }" class="relative w-full">
                                            <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/40 border border-dark-border rounded-xl text-white px-3 py-2 text-sm focus:border-accent outline-none">
                                                <span x-text="selected ? selected : '{{ __('Rol tanlang') }}'"></span>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                            <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar text-left" style="display: none;">
                                                <div @click="selected = ''; open = false; $wire.assignRoleToUser({{ $user->id }}, '')" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ __('Rol tanlang') }}</div>
                                                @foreach($roles as $role)
                                                    <div @click="selected = '{{ $role->name }}'; open = false; $wire.assignRoleToUser({{ $user->id }}, '{{ $role->name }}')" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ $role->name }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                </div>
                            @else
                                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-2 rounded-xl text-sm font-bold text-center">
                                    ADMINISTRATOR
                                </div>
                            @endif
                        @else
                            <div class="bg-white/5 border border-white/10 text-gray-300 px-4 py-2 rounded-xl text-sm">
                                {{ $user->roles->pluck('name')->implode(', ') ?: __('Xodim') }}
                            </div>
                        @endif

                        <div class="mt-2 text-xs text-gray-400">
                            <strong>{{ __('Guruh:') }}</strong> {{ $user->team ? $user->team->name : __('Guruhsiz') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif($activeTab === 'teams')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Teams List -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($teams as $team)
                    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <svg class="w-5 h-5 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $team->name }}
                                </h3>
                                <p class="text-sm text-dark-muted mt-1">
                                    {{ __('Menejer:') }} <strong>{{ $team->manager ? $team->manager->name : __('Admin') }}</strong>
                                </p>
                            </div>
                            @if(Auth::user()->hasRole('Admin') || Auth::user()->can('manage_employees'))
                            <div class="flex space-x-2">
                                <button wire:click="editTeam({{ $team->id }})" class="p-2 bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button onclick="confirm('{{ __('Guruhni o\'chirmoqchimisiz?') }}') || event.stopImmediatePropagation()" wire:click="deleteTeam({{ $team->id }})" class="p-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endif
                        </div>

                        <div class="bg-black/30 rounded-2xl p-4 border border-dark-border">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Guruh a\'zolari') }} }})</h4>
                            <div class="flex flex-wrap gap-2">
                                @forelse($team->users as $member)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-accent/10 text-accent border border-accent/20">
                                        {{ $member->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-dark-muted">{{ __('Hozircha a\'zolar yo\'q.') }}</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Team Form -->
            @if(Auth::user()->hasRole('Admin') || Auth::user()->can('manage_employees'))
            <div>
                <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl sticky top-6">
                    <h3 class="text-xl font-bold text-white mb-6">
                        {{ $isEditingTeam ? __('Guruhni Tahrirlash') : __('Yangi Guruh Yaratish') }}
                    </h3>

                    <form wire:submit.prevent="saveTeam" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">{{ __('Guruh nomi *') }}</label>
                            <input type="text" wire:model.defer="teamName" required class="w-full bg-black/40 border border-dark-border rounded-xl text-white px-4 py-2 focus:outline-none focus:border-accent">
                            @error('teamName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">{{ __('Menejer *') }}</label>
                            <!-- Custom Select for Team Manager -->
                            <div x-data="{ open: false, selected: @entangle('teamManagerId').defer }" class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/40 border border-dark-border rounded-xl text-white px-4 py-2 focus:border-accent outline-none">
                                    <span x-text="
                                        @foreach($this->getAvailableManagers() as $u)
                                            selected == {{ $u->id }} ? '{{ addslashes($u->name) }}' :
                                        @endforeach
                                        '-- {{ __('Menejerni tanlang') }} --'
                                    "></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                    <div @click="selected = ''; open = false; $wire.set('teamManagerId', null)" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">-- {{ __('Menejerni tanlang') }} --</div>
                                    @foreach($this->getAvailableManagers() as $u)
                                        <div @click="selected = {{ $u->id }}; open = false; $wire.set('teamManagerId', {{ $u->id }})" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ $u->name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">{{ __('Xodimlarni biriktirish') }}</label>
                            @if($teamManagerId)
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($this->getAvailableMembers() as $u)
                                        <label class="flex items-center p-3 border border-dark-border rounded-xl hover:bg-white/5 cursor-pointer transition-colors group {{ in_array($u->id, $teamMembers) ? 'border-accent bg-accent/5' : '' }}">
                                            <input type="checkbox" wire:click="toggleTeamMember({{ $u->id }})" {{ in_array($u->id, $teamMembers) ? 'checked' : '' }} class="rounded border-white/20 bg-black/50 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="ml-3 text-sm text-gray-200 group-hover:translate-x-0.5 transition-transform">{{ $u->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl text-yellow-400 text-sm">
                                    {{ __('Iltimos, avval guruh menejerini tanlang.') }}
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 flex space-x-3">
                            <button type="submit" class="flex-1 bg-accent hover:bg-accent-hover text-white font-bold py-2 px-4 rounded-xl shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-all">
                                {{ __('Saqlash') }}
                            </button>
                            @if($isEditingTeam)
                                <button type="button" wire:click="resetTeamForm" class="px-4 py-2 bg-dark-border text-white rounded-xl hover:bg-gray-600 transition-colors">
                                    {{ __('Bekor qilish') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

    @elseif($activeTab === 'roles')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Roles List -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 h-fit">
                @foreach($roles as $role)
                    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-white">{{ $role->name }}</h3>
                            <div class="flex space-x-2">
                                <button wire:click="editRole({{ $role->id }})" class="p-2 bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button onclick="confirm('{{ __('O\'chirmoqchimisiz?') }}') || event.stopImmediatePropagation()" wire:click="deleteRole({{ $role->id }})" class="p-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <p class="text-xs text-dark-muted font-bold mb-2 uppercase">{{ __('Ruxsatlar:') }}</p>
                            @forelse($role->permissions as $perm)
                                <div class="text-sm text-gray-300 flex items-center">
                                    <svg class="w-3 h-3 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $perm->name }}
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 italic">{{ __('Ruxsatlar belgilanmagan') }}</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Role Form -->
            <div>
                <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl sticky top-6">
                    <h3 class="text-xl font-bold text-white mb-6">
                        {{ $isEditingRole ? __('Rolni Tahrirlash') : __('Yangi Rol Yaratish') }}
                    </h3>

                    <form wire:submit.prevent="saveRole" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">{{ __('Rol nomi *') }}</label>
                            <input type="text" wire:model.defer="roleName" required class="w-full bg-black/40 border border-dark-border rounded-xl text-white px-4 py-2 focus:outline-none focus:border-accent">
                            @error('roleName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">{{ __('Ruxsatlarni belgilang') }}</label>
                            <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($permissionsList as $group => $perms)
                                    <div class="bg-black/30 rounded-2xl p-4 border border-dark-border">
                                        <h4 class="font-bold text-accent mb-3 text-sm">{{ __($group) }}</h4>
                                        <div class="space-y-2">
                                            @foreach($perms as $key => $label)
                                                <label class="flex items-center hover:bg-white/5 p-2 rounded-lg cursor-pointer transition-colors group">
                                                    <input type="checkbox" wire:click="toggleRolePermission('{{ $key }}')" {{ in_array($key, $rolePermissions) ? 'checked' : '' }} class="rounded border-white/20 bg-black/50 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                                    <span class="ml-3 text-sm text-gray-300 group-hover:translate-x-0.5 transition-transform">{{ __($label) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 flex space-x-3">
                            <button type="submit" class="flex-1 bg-accent hover:bg-accent-hover text-white font-bold py-2 px-4 rounded-xl shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-all">
                                {{ __('Saqlash') }}
                            </button>
                            @if($isEditingRole)
                                <button type="button" wire:click="resetRoleForm" class="px-4 py-2 bg-dark-border text-white rounded-xl hover:bg-gray-600 transition-colors">
                                    {{ __('Bekor qilish') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
