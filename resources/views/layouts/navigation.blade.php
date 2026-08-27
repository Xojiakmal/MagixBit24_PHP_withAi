<aside class="w-64 h-full bg-dark-bg flex flex-col py-8 px-4 flex-shrink-0">
    <!-- Logo Area -->
    <div class="flex items-center px-4 mb-12">
        <div class="w-8 h-8 rounded-lg bg-accent flex items-center justify-center mr-3 shadow-[0_0_15px_rgba(155,114,255,0.5)]">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <span class="text-xl font-bold tracking-wider text-white">MAGIX</span>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 space-y-2">
        <!-- Always visible links for authorized users -->
        @can('view_dashboard')
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
            <svg class="w-5 h-5 mr-4 {{ request()->routeIs('dashboard') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>
        @endcan

        @if(Auth::user() && Auth::user()->current_tenant_id)
            @can('view_crm')
            <a href="{{ route('crm.deals') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('crm.deals') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('crm.deals') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                CRM (Deals)
            </a>
            @endcan

            @can('view_tasks')
            <a href="{{ route('projects.tasks') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('projects.tasks') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('projects.tasks') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Tasks & Projects
            </a>
            @endcan

            @can('view_storage')
            <a href="{{ route('storage.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('storage.index') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('storage.index') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Storage
            </a>
            @endcan

            @can('manage_employees')
            <a href="{{ route('roles.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('roles.index') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('roles.index') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Xodimlar
            </a>
            @endcan
            
            <a href="{{ route('contacts') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('contacts') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('contacts') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Tashqi Kontaktlar
            </a>
        @endif
    </nav>

    <!-- Bottom Settings/Logout -->
    <div class="mt-auto space-y-2">

        <!-- Language Switcher -->
        <div class="px-4 py-2 flex items-center justify-between text-dark-muted text-sm border-t border-dark-border/50 pt-4 mb-2">
            <a href="{{ route('lang.switch', 'uz') }}" class="px-2 py-1 rounded-lg transition-all {{ app()->getLocale() == 'uz' ? 'text-white bg-accent/20 border border-accent/50' : 'hover:text-white' }}">O'Z</a>
            <span class="text-dark-border">|</span>
            <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded-lg transition-all {{ app()->getLocale() == 'en' ? 'text-white bg-accent/20 border border-accent/50' : 'hover:text-white' }}">EN</a>
        </div>

        <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('profile') ? 'bg-dark-surface text-white border border-dark-border shadow-lg' : 'text-dark-muted hover:text-white hover:bg-dark-surface/50' }}">
            <svg class="w-5 h-5 mr-4 {{ request()->routeIs('profile') ? 'text-accent' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            {{ __('Mening Profilim') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 rounded-2xl text-dark-muted hover:text-red-400 hover:bg-dark-surface/50 transition-all duration-300">
                <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</aside>
