<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rollar va Ruxsatlar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-500/20 backdrop-blur-md border border-green-500/50 text-green-200 p-4 rounded-xl shadow-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 backdrop-blur-md border border-red-500/50 text-red-200 p-4 rounded-xl shadow-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Invite Link Section -->
            @php
                $tenant = \App\Models\Tenant::find(Auth::user()->current_tenant_id);
            @endphp
            @if($tenant && $tenant->unique_link)
            <div class="bg-black/20 isolate border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden">
                <h3 class="text-xl font-bold text-white mb-4">Kompaniya Taklif Havolasi</h3>
                <p class="text-sm text-gray-300 mb-4">Ushbu havolani xodimlar bilan ulashing. Ular shu havola orqali botdan o'tib, to'g'ridan-to'g'ri kompaniyangizga xodim bo'lib qo'shiladilar.</p>
                <div class="flex items-center space-x-4 bg-black/30 p-4 rounded-xl border border-white/10">
                    <code class="text-accent flex-1 select-all font-mono text-lg font-bold">{{ $tenant->unique_link }}</code>
                </div>
            </div>
            @endif

            <!-- Multi-sig Approvals Section -->
            @if(Auth::user()->hasRole('Collaborator') && $approvalRequests->count() > 0)
            <div class="bg-black/20 isolate backdrop-blur-lg border border-white/20 overflow-hidden shadow-2xl rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Kutilayotgan So'rovlar (Multi-sig)</h3>
                <div class="space-y-4">
                    @foreach($approvalRequests as $req)
                        @if($req->action === 'demote_collaborator' && $req->payload['target_user_id'] === Auth::id())
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 flex justify-between items-center">
                            <div>
                                <p class="text-gray-200 font-semibold">Sizni Collaborator lavozimidan tushirish bo'yicha so'rov.</p>
                                <p class="text-sm text-gray-400">So'rov yubordi: {{ $req->requester->name }}</p>
                            </div>
                            <form action="{{ route('roles.approve', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition duration-300">
                                    Tasdiqlash
                                </button>
                            </form>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Users List Section -->
            <div class="bg-black/20 isolate backdrop-blur-lg border border-white/20 overflow-hidden shadow-2xl rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Kompaniya Xodimlari</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/20 text-gray-300">
                                <th class="p-3">Ism</th>
                                <th class="p-3">Telegram Username</th>
                                <th class="p-3">Joriy Rol</th>
                                <th class="p-3">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr class="border-b border-white/10 hover:bg-white/5 transition duration-200">
                                <td class="p-3 text-white">{{ $u->name }}</td>
                                <td class="p-3 text-gray-400">{{ $u->telegram_username ?? 'Yo\'q' }}</td>
                                <td class="p-3 text-indigo-300 font-semibold">
                                    {{ $u->getRoleNames()->first() ?? 'Xodim' }}
                                </td>
                                <td class="p-3">
                                    @if(Auth::user()->hasRole('Admin') || Auth::user()->is_superadmin)
                                        @if($u->id !== Auth::id())
                                            @if($u->hasRole('Collaborator'))
                                                <form action="{{ route('roles.demote', $u->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="bg-red-500/80 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition duration-300">
                                                        Tushirish (So'rov)
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('roles.assign', $u->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="role" value="Collaborator">
                                                    <button class="bg-green-500/80 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm transition duration-300">
                                                        Collaborator qilish
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
