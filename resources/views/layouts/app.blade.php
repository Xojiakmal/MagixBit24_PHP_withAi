<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BitrixClone') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .cosmos-bg {
                background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?q=80&w=2000&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            /* Override tailwind config background to make it transparent */
            .bg-dark-bg { background-color: transparent !important; }
            
            .glass-panel {
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>
    <body class="font-sans antialiased cosmos-bg text-white overflow-hidden">
        <div class="flex h-screen w-full bg-black/10"> <!-- Dark overlay -->
            <!-- Sidebar Navigation -->
            <div class="glass-panel m-4 rounded-[32px] w-64 shadow-2xl flex flex-col">
                @include('layouts.navigation')
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0 flex flex-col p-4 pl-0 relative">
                <!-- Inner container with glassmorphism surface -->
                <div class="flex-1 min-w-0 rounded-[32px] shadow-2xl relative overflow-hidden flex flex-col">
                    
                    <!-- Background Glass Layer (Separated to fix Chrome white background bug with overflow) -->
                    <div class="absolute inset-0 glass-panel pointer-events-none"></div>

                    <!-- Scrollable Content Layer -->
                    <div class="flex-1 overflow-y-auto relative z-10 custom-scrollbar flex flex-col">
                        <!-- Top Navigation / Header -->
                        @isset($header)
                            <header class="pt-8 px-10 pb-4 border-b border-white/10 bg-slate-900/40 backdrop-blur-md relative z-20 shrink-0">
                                <div class="flex items-center justify-between">
                                    {{ $header }}
                                    
                                    <!-- User Profile Mini -->
                                    <div class="flex items-center space-x-4">
                                        @php
                                            $tenant = \App\Models\Tenant::find(Auth::user()->current_tenant_id);
                                        @endphp
                                        @if($tenant && ($tenant->owner_id === Auth::id() || Auth::user()->hasRole('Admin')))
                                            <a href="{{ route('company.settings') }}" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-full transition-colors shadow-sm" title="{{ __('Kompaniya Sozlamalari') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            </a>
                                        @endif
                                        <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center font-bold shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </header>
                        @endisset

                        <!-- Page Content -->
                        <main class="flex-1 p-10">
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
