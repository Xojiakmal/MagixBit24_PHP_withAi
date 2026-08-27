<div class="relative w-full">
    @if($selectedValue)
        <div class="flex items-center justify-between p-3 bg-white/5 border border-dark-border rounded-xl">
            <span class="text-white">{{ $selectedName }}</span>
            <button type="button" wire:click="clearSelection" class="text-gray-400 hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @else
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ $placeholder }}"
                class="w-full bg-white/5 border border-dark-border text-white text-sm rounded-xl focus:ring-accent focus:border-accent block p-3"
            >
            <div wire:loading wire:target="search" class="absolute right-3 top-3">
                <svg class="animate-spin h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        @if($showDropdown && strlen($search) >= 2)
            <div class="absolute z-50 w-full mt-2 bg-dark-bg border border-dark-border rounded-xl shadow-lg max-h-60 overflow-y-auto">
                @if(count($results) > 0)
                    <ul class="py-1">
                        @foreach($results as $result)
                            <li>
                                <button 
                                    type="button" 
                                    wire:click="selectItem('{{ $result[$valueField] }}', '{{ addslashes($result[$displayField]) }}')"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white"
                                >
                                    <div class="font-medium">{{ $result[$displayField] }}</div>
                                    @if(isset($result['phone']) && $result['phone'])
                                        <div class="text-xs text-gray-500">Tel: {{ $result['phone'] }}</div>
                                    @endif
                                    @if(isset($result['address']) && $result['address'])
                                        <div class="text-xs text-gray-500">Manzil: {{ $result['address'] }}</div>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-4 py-3 text-sm text-gray-400">
                        Natija topilmadi
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
