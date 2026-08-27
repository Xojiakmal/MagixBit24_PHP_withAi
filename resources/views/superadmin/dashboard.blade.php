<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SuperAdmin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4">Tasdiqlash kutilayotgan kompaniyalar</h3>
                    @if($pendingTenants->isEmpty())
                        <p class="text-sm text-gray-500">Hozircha kutilayotgan kompaniyalar yo'q.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($pendingTenants as $tenant)
                                <li class="border p-4 rounded flex justify-between items-center">
                                    <div>
                                        <strong>{{ $tenant->name }}</strong> (Egasi: {{ $tenant->owner->name }})
                                        <br>
                                        <span class="text-xs text-gray-500">Unique Link: {{ $tenant->unique_link }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('superadmin.tenant.approve', $tenant) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tasdiqlash</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <h3 class="text-lg font-bold mt-8 mb-4">Faol kompaniyalar</h3>
                    @if($activeTenants->isEmpty())
                        <p class="text-sm text-gray-500">Hozircha faol kompaniyalar yo'q.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($activeTenants as $tenant)
                                <li class="border p-4 rounded">
                                    <strong>{{ $tenant->name }}</strong> (Egasi: {{ $tenant->owner->name }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
