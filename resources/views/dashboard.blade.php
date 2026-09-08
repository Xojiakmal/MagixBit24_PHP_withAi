<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="h-full">
        @php
            $tenant = \App\Models\Tenant::find(auth()->user()->current_tenant_id);
        @endphp

        @if($tenant && $tenant->owner_id === auth()->id())
            <div class="mb-8 p-6 bg-accent/10 border border-accent/20 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        {{ __('Kompaniyaga qo\'shilish kodi') }}
                    </h3>
                    <p class="text-sm text-accent/80 mt-1">{{ __('Ushbu kodni yangi xodimlarga bering. Ular "Kompaniyaga qo\'shilish" oynasida ushbu kodni kiritishlari kerak.') }}</p>
                </div>
                <div class="flex items-center space-x-3 bg-black/40 px-5 py-3 rounded-xl border border-dark-border">
                    <code class="text-accent font-mono text-xl font-bold select-all tracking-wider">{{ $tenant->unique_link }}</code>
                </div>
            </div>
        @endif

        @php
            $hasAnyAccess = auth()->user()->hasRole('Admin') || auth()->user()->can('view_dashboard');
        @endphp

        @if(!$hasAnyAccess)
            <div class="flex flex-col items-center justify-center h-[70vh]">
                <div class="bg-red-500/10 border border-red-500/20 rounded-3xl p-10 max-w-lg text-center backdrop-blur-xl">
                    <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-4">{{ __('Ruxsat etilmagan') }}</h2>
                    <p class="text-dark-muted">{{ __('Sizga Bosh sahifani (Dashboard) ko\'rish uchun ruxsat berilmagan. Iltimos, chap tarafdagi menyu orqali o\'zingizga ruxsat etilgan bo\'limlarga kiring yoki administratorga murojaat qiling.') }}</p>
                </div>
            </div>
        @else

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-accent/10 rounded-full blur-2xl group-hover:bg-accent/20 transition-all duration-500"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Jarayondagi pul') }}</p>
                    <h3 class="text-3xl font-bold text-white tracking-tight">${{ number_format($inProgressRevenue, 0, '.', ' ') }}</h3>
                </div>
                <!-- Neon line underneath -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-blue-500 opacity-50"></div>
            </div>

            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl group-hover:bg-yellow-500/20 transition-all duration-500"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Yopilgan pul') }}</p>
                    <h3 class="text-3xl font-bold text-white tracking-tight">${{ number_format($closedRevenue, 0, '.', ' ') }}</h3>
                </div>
                <!-- Neon line underneath -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-orange-500 opacity-50"></div>
            </div>

            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-all duration-500"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Yangi Mijozlar') }}</p>
                    <h3 class="text-3xl font-bold text-white tracking-tight">{{ $newClients }}</h3>
                </div>
                <!-- Neon line underneath -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-500 opacity-50"></div>
            </div>
        </div>



        <!-- Wallets/Summary Section placeholder -->
        <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl mb-8">
            <h3 class="text-xl font-bold text-white mb-6">{{ __('Xulosa') }}</h3>
            <div class="flex items-center justify-center h-48 border-2 border-dashed border-dark-border rounded-2xl">
                <p class="text-dark-muted">{{ __('Tez orada grafika va tahlillar shu yerda bo\'ladi...') }}</p>
            </div>
        </div>

        @if(auth()->user()->hasRole('Admin') || auth()->user()->can('manage_employees'))
            <!-- Activity & History (Live) -->
            <livewire:admin-dashboard-activities />
        @endif
        @endif
    </div>
</x-app-layout>
