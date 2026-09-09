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
                    <p class="text-dark-muted">{{ __('Sizga Bosh sahifani ko\'rish uchun ruxsat berilmagan. Iltimos, chap tarafdagi menyu orqali o\'zingizga ruxsat etilgan bo\'limlarga kiring yoki administratorga murojaat qiling.') }}</p>
                </div>
            </div>
        @else

        <!-- Filter Section -->
        <div class="flex justify-end mb-6">
            <div class="bg-black/30 border border-dark-border rounded-xl p-1 inline-flex gap-1 backdrop-blur-xl">
                @foreach(['day' => __('Kun'), 'week' => __('Hafta'), 'month' => __('Oy'), 'year' => __('Yil')] as $key => $label)
                    <a href="{{ route('dashboard', ['time' => $key]) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ $timeFilter === $key ? 'bg-accent text-white shadow-[0_0_15px_rgba(var(--color-accent),0.5)]' : 'text-dark-muted hover:text-white hover:bg-white/5' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-accent/10 rounded-full blur-2xl group-hover:bg-accent/20 transition-all duration-500"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Jarayondagi pul') }}</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">${{ number_format($inProgressRevenue, 0, '.', ' ') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-blue-500 opacity-50"></div>
            </div>

            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl group-hover:bg-yellow-500/20 transition-all duration-500"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Yopilgan pul') }}</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">${{ number_format($closedRevenue, 0, '.', ' ') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-orange-500 opacity-50"></div>
            </div>

            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-dark-muted mb-2">{{ __('Jami Bitimlar') }}</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">{{ $totalDeals }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-green-500 opacity-50"></div>
            </div>
        </div>

        <!-- Chart and Clients Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- Chart Section -->
            <div class="lg:col-span-2 bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    {{ __('Daromad Dinamikasi') }}
                </h3>
                <div class="w-full h-72">
                    <div id="revenueChart" class="w-full h-full"></div>
                </div>
            </div>

            <!-- Client List Section -->
            <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {{ __('Yangi Mijozlar') }}
                </h3>
                
                <div class="space-y-4 h-72 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($recentClients as $client)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition duration-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-white font-medium text-sm">{{ $client->name }}</h4>
                                    <p class="text-xs text-dark-muted">{{ $client->phone ?? __('Raqam kiritilmagan') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    {{ $client->deals_count }} {{ __('bitim') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-40 text-center">
                            <div class="w-12 h-12 rounded-full bg-dark-border/50 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-dark-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-sm text-dark-muted">{{ __('Ushbu oraliqda yangi mijozlar yo\'q') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Scripts for Chart -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const renderChart = (data, labels) => {
                    const options = {
                        series: [{
                            name: '{{ __("Daromad") }}',
                            data: data
                        }],
                        chart: {
                            type: 'area',
                            height: 280,
                            toolbar: { show: false },
                            background: 'transparent',
                            fontFamily: 'Inter, sans-serif',
                            animations: { enabled: true }
                        },
                        colors: ['#4F46E5'], // accent color
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.7,
                                opacityTo: 0.1,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        xaxis: {
                            categories: labels,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: '#9CA3AF' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#9CA3AF' },
                                formatter: (value) => { return "$" + value.toLocaleString() }
                            }
                        },
                        grid: {
                            borderColor: 'rgba(255,255,255,0.05)',
                            strokeDashArray: 4,
                            yaxis: { lines: { show: true } }
                        },
                        theme: { mode: 'dark' },
                        tooltip: {
                            theme: 'dark',
                            y: { formatter: function (val) { return "$" + val } }
                        }
                    };

                    const chartEl = document.querySelector("#revenueChart");
                    if (chartEl && typeof ApexCharts !== 'undefined') {
                        const chart = new ApexCharts(chartEl, options);
                        chart.render();
                    }
                };

                const initialData = @json($chartData);
                
                if (typeof ApexCharts !== 'undefined') {
                    renderChart(initialData.data, initialData.labels);
                } else {
                    window.addEventListener('load', () => {
                        if (typeof ApexCharts !== 'undefined') {
                            renderChart(initialData.data, initialData.labels);
                        }
                    });
                }
            });
        </script>

        @if(auth()->user()->hasRole('Admin') || auth()->user()->can('manage_employees'))
            <!-- Activity & History (Live) -->
            <livewire:admin-dashboard-activities />
        @endif
        @endif
    </div>
</x-app-layout>
