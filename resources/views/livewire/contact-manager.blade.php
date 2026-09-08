<div class="h-full bg-transparent text-white pb-10">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Tashqi Kontaktlar') }}</h2>
            <p class="text-dark-muted mt-1 text-sm">{{ __('Kompaniya tashqarisidagi foydalanuvchilar va ma\'lumotlar bazasi') }}</p>
        </div>
    </div>
    
    <!-- Main Layout: Sidebar & Content -->
    <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar / Groups -->
            <div class="w-full md:w-1/4 space-y-6">
                <div class="bg-black/20 border border-dark-border rounded-[32px] p-6 shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                    <h3 class="text-xl font-bold mb-4">{{ __('Guruhlar') }}</h3>
                    <div class="space-y-2 max-h-[50vh] overflow-y-auto custom-scrollbar">
                        @foreach($groups as $group)
                            <div class="flex justify-between items-center bg-black/40 border border-white/5 p-3 rounded-xl cursor-pointer hover:border-accent transition-colors {{ $activeGroupId === $group->id ? 'border-accent bg-accent/10' : '' }}" wire:click="setView('contacts', {{ $group->id }})">
                                <span class="font-medium truncate pr-2 text-sm">{{ $group->name }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-dark-muted">{{ $group->contacts()->count() }}</span>
                                    @can('manage_contacts')
                                    <button wire:click.stop="deleteGroup({{ $group->id }})" class="text-gray-500 hover:text-red-400 p-1" onclick="confirm('{{ __('Guruhni o\'chirish barcha ichidagi kontaktlarni ham o\'chirishi mumkin. Tasdiqlaysizmi?') }}') || event.stopImmediatePropagation()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @can('manage_contacts')
                    @if($isEditingGroup)
                        <div class="mt-4 space-y-2">
                            <input type="text" wire:model.defer="groupName" placeholder="{{ __('Guruh nomi (Masalan: Haydovchilar)') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                            <div class="flex space-x-2">
                                <button wire:click="saveGroup" class="flex-1 bg-accent hover:bg-accent-hover text-white text-sm py-1.5 rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">{{ __('Saqlash') }}</button>
                                <button wire:click="$set('isEditingGroup', false)" class="px-3 bg-dark-surface hover:bg-white/10 text-gray-400 rounded-lg border border-dark-border">✕</button>
                            </div>
                        </div>
                    @else
                        <button wire:click="$set('isEditingGroup', true)" class="mt-4 w-full border border-dashed border-dark-border text-gray-400 hover:text-white hover:border-white p-3 rounded-xl transition-all text-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            {{ __('Yangi guruh qo\'shish') }}
                        </button>
                    @endif
                    @endcan
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full md:w-3/4">
                @if($activeGroupId && $activeGroup)
                    <div class="bg-black/20 border border-dark-border rounded-[32px] p-6 lg:p-8 shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl min-h-[70vh]">
                        
                        <div class="flex justify-between items-center mb-8 border-b border-dark-border pb-4">
                            <div>
                                <h2 class="text-2xl font-bold">{{ $activeGroup->name }}</h2>
                                <p class="text-sm text-dark-muted mt-1">{{ __('Ushbu guruhdagi barcha kontaktlar va ularning maydonlari') }}</p>
                            </div>
                            <div class="flex items-center space-x-1 bg-white/5 p-1 rounded-xl w-fit border border-dark-border">
                                <button wire:click="setView('contacts', {{ $activeGroupId }})" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentView === 'contacts' ? 'bg-white/10 text-white shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">{{ __('Ro\'yxat') }}</button>
                                @can('manage_contacts')
                                <button wire:click="setView('form_builder', {{ $activeGroupId }})" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center {{ $currentView === 'form_builder' ? 'bg-white/10 text-white shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ __('Forma Sozlamalari') }}
                                </button>
                                @endcan
                            </div>
                        </div>

                        <!-- Form Builder View -->
                        @if($currentView === 'form_builder')
                            <div class="space-y-8">
                                <div>
                                    <h4 class="text-lg font-bold mb-4">{{ __('Mavjud maydonlar') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Default required fields -->
                                        <div class="bg-black/30 border border-dark-border p-4 rounded-xl flex justify-between items-center opacity-70">
                                            <div>
                                                <p class="font-semibold text-white">{{ __('Ism-familiya') }} <span class="text-red-400 ml-1">*</span></p>
                                                <p class="text-xs text-gray-500 mt-1">{{ __('Majburiy asosiy maydon') }}</p>
                                            </div>
                                            <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400">string</span>
                                        </div>
                                        <div class="bg-black/30 border border-dark-border p-4 rounded-xl flex justify-between items-center opacity-70">
                                            <div>
                                                <p class="font-semibold text-white">{{ __('Telefon raqam') }} <span class="text-red-400 ml-1">*</span></p>
                                                <p class="text-xs text-gray-500 mt-1">{{ __('Majburiy asosiy maydon') }}</p>
                                            </div>
                                            <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400">string</span>
                                        </div>
                                        
                                        <!-- Custom fields -->
                                        @foreach($customFields as $cf)
                                            <div class="bg-black/40 border border-dark-border p-4 rounded-xl flex justify-between items-start group hover:border-accent/50 transition-colors">
                                                <div>
                                                    <p class="font-semibold text-white">{{ $cf->name }} @if($cf->is_required)<span class="text-red-400 ml-1">*</span>@endif</p>
                                                    <div class="flex items-center mt-2 space-x-2">
                                                        <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400 border border-white/5">{{ $cf->type }}</span>
                                                        @if($cf->type === 'select' && $cf->options)
                                                            @php $opts = json_decode($cf->options, true); @endphp
                                                            <span class="text-xs text-blue-400" title="{{ implode(', ', $opts) }}">{{ count($opts) }} {{ __('ta variant') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <button wire:click="deleteCustomField({{ $cf->id }})" class="text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity p-1" title="{{ __('Maydonni o\'chirish') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                                    <h4 class="text-lg font-bold mb-4 text-accent">{{ __('Yangi maydon qo\'shish') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Nomi') }}</label>
                                            <input type="text" wire:model="newFieldName" placeholder="{{ __('Masalan: Mashina turi') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                            @error('newFieldName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Turi') }}</label>
                                            <!-- Custom Select for Field Type -->
                                            <div x-data="{ open: false, selected: @entangle('newFieldType').live }" class="relative">
                                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                                    <span x-text="selected === 'text' ? 'Text' : (selected === 'number' ? 'Number' : (selected === 'date' ? 'Date' : (selected === 'select' ? 'Select (Dropdown)' : 'Text')))"></span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>
                                                <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                                    <div @click="selected = 'text'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Text</div>
                                                    <div @click="selected = 'number'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Number</div>
                                                    <div @click="selected = 'date'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Date</div>
                                                    <div @click="selected = 'select'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Select (Dropdown)</div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($newFieldType === 'select')
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Variantlar (vergul bilan ajrating)') }}</label>
                                                <input type="text" wire:model="newFieldOptions" placeholder="{{ __('Damas, Cobalt, Gentra, Tracker') }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                            </div>
                                        @endif
                                        <div class="md:col-span-2 flex items-center justify-between mt-2 border-t border-dark-border pt-4">
                                            <label class="flex items-center space-x-3 cursor-pointer group">
                                                <input type="checkbox" wire:model="newFieldRequired" class="w-5 h-5 rounded border-dark-border bg-white/5 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent transition-all cursor-pointer">
                                                <span class="text-sm font-medium text-gray-300 group-hover:translate-x-0.5 transition-transform">{{ __('Majburiy maydon') }}</span>
                                            </label>
                                            <button wire:click="saveCustomField" class="px-6 py-2 bg-accent hover:bg-accent-hover text-white font-semibold rounded-xl transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                                                {{ __('Qo\'shish') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <!-- Contacts List View -->
                        @else
                            <div class="mb-6 flex justify-between items-center">
                                <h4 class="text-lg font-bold">{{ __('Kontaktlar Ro\'yxati') }}</h4>
                                @can('manage_contacts')
                                <button wire:click="editContact" class="px-4 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-xl transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)] flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    {{ __('Yangi Kontakt') }}
                                </button>
                                @endcan
                            </div>

                            @if($isEditingContact)
                                <div class="bg-black/30 border border-dark-border rounded-2xl p-6 mb-8">
                                    <h4 class="text-lg font-bold mb-4">{{ $contactId ? __('Kontaktni tahrirlash') : __('Yangi kontakt qo\'shish') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Default Fields -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Ism-familiya') }} <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="contactName" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                            @error('contactName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400 mb-1">{{ __('Telefon raqam') }} <span class="text-red-500">*</span></label>
                                            <input type="tel" wire:model="contactPhone" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                            @error('contactPhone') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Custom Fields from Schema -->
                                        @foreach($customFields as $cf)
                                            <div class="{{ $cf->type === 'text' ? 'md:col-span-2' : '' }}">
                                                <label class="block text-sm font-medium text-gray-400 mb-1">{{ $cf->name }} @if($cf->is_required)<span class="text-red-500">*</span>@endif</label>
                                                
                                                @if($cf->type === 'text')
                                                    <textarea wire:model="dynamicFieldsData.{{ $cf->id }}" rows="3" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none custom-scrollbar transition-all"></textarea>
                                                @elseif($cf->type === 'select')
                                                    <!-- Custom Select for Dynamic Field -->
                                                    <div x-data="{ open: false, selected: @entangle('dynamicFieldsData.' . $cf->id) }" class="relative">
                                                        <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                                            <span x-text="selected ? selected : '-- {{ __('Tanlang') }} --'"></span>
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                        </button>
                                                        <div x-show="open" x-transition class="absolute z-[100] w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                                            <div @click="selected = ''; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">-- {{ __('Tanlang') }} --</div>
                                                            @foreach(explode(',', $cf->options) as $opt)
                                                                @php $opt = trim($opt); @endphp
                                                                <div @click="selected = '{{ $opt }}'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">{{ $opt }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif($cf->type === 'boolean')
                                                    <div class="flex space-x-4 mt-2">
                                                        <label class="flex items-center space-x-3 cursor-pointer group">
                                                            <input type="radio" wire:model="dynamicFieldsData.{{ $cf->id }}" value="1" class="text-accent bg-white/5 border-dark-border focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent transition-all cursor-pointer">
                                                            <span class="text-sm text-gray-300 group-hover:translate-x-0.5 transition-transform">{{ __('Ha') }}</span>
                                                        </label>
                                                        <label class="flex items-center space-x-3 cursor-pointer group">
                                                            <input type="radio" wire:model="dynamicFieldsData.{{ $cf->id }}" value="0" class="text-accent bg-white/5 border-dark-border focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent transition-all cursor-pointer">
                                                            <span class="text-sm text-gray-300 group-hover:translate-x-0.5 transition-transform">{{ __('Yo\'q') }}</span>
                                                        </label>
                                                    </div>
                                                @elseif($cf->type === 'date')
                                                    <input type="date" wire:model="dynamicFieldsData.{{ $cf->id }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                                @else
                                                    <input type="{{ $cf->type === 'number' ? 'number' : 'text' }}" wire:model="dynamicFieldsData.{{ $cf->id }}" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                                                @endif
                                                
                                                @error('dynamicFieldsData.'.$cf->id) <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 flex space-x-3 justify-end border-t border-dark-border pt-4">
                                        <button wire:click="$set('isEditingContact', false)" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-xl transition-colors">{{ __('Bekor qilish') }}</button>
                                        <button wire:click="saveContact" class="px-6 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-xl transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">{{ __('Saqlash') }}</button>
                                    </div>
                                </div>
                            @endif

                            <div class="overflow-x-auto rounded-xl border border-dark-border">
                                <table class="w-full text-left text-sm text-gray-300">
                                    <thead class="bg-black/40 text-xs uppercase text-gray-400 border-b border-dark-border">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">{{ __('Ism-familiya') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Telefon') }}</th>
                                            @foreach($customFields->take(3) as $cf)
                                                <th class="px-4 py-3 font-semibold">{{ $cf->name }}</th>
                                            @endforeach
                                            @can('manage_contacts')
                                            <th class="px-4 py-3 font-semibold text-right">{{ __('Amallar') }}</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-dark-border/50">
                                        @forelse($contacts as $contact)
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="px-4 py-3 font-semibold text-white">{{ $contact->name }}</td>
                                                <td class="px-4 py-3 font-mono text-xs">{{ $contact->phone }}</td>
                                                @foreach($customFields->take(3) as $cf)
                                                    <td class="px-4 py-3 text-gray-400">
                                                        @php $val = $contact->getCustomField($cf->id); @endphp
                                                        @if($cf->type === 'boolean')
                                                            @if($val === '1') <span class="text-green-400">{{ __('Ha') }}</span> 
                                                            @elseif($val === '0') <span class="text-red-400">{{ __('Yo\'q') }}</span>
                                                            @else - @endif
                                                        @else
                                                            {{ Str::limit($val, 20) ?: '-' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                @can('manage_contacts')
                                                <td class="px-4 py-3 text-right">
                                                    <button wire:click="editContact({{ $contact->id }})" class="text-blue-400 hover:text-blue-300 mr-2 p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </button>
                                                    <button onclick="confirm('{{ __('Kontaktni o\'chirmoqchimisiz?') }}') || event.stopImmediatePropagation()" wire:click="deleteContact({{ $contact->id }})" class="text-red-400 hover:text-red-300 p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </td>
                                                @endcan
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="px-4 py-8 text-center text-gray-500">
                                                    {{ __('Ushbu guruhda kontaktlar yo\'q.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-black/20 border border-dark-border rounded-[32px] p-12 shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl min-h-[70vh] flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-dark-surface rounded-full flex items-center justify-center mb-6 border border-dark-border">
                            <svg class="w-10 h-10 text-dark-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ __('Tashqi Kontaktlar') }}</h3>
                        <p class="text-dark-muted max-w-md">{{ __('Chap tarafdan kontakt guruhini tanlang yoki yangisini yarating.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
