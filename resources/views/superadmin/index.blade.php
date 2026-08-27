<x-app-layout>
    <div class="h-full w-full p-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-white mb-8">Superadmin Panel - Kompaniya So'rovlari</h2>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-200 p-4 rounded-2xl mb-8 backdrop-blur-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-black/20 border border-dark-border rounded-[32px] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-dark-border/50 text-dark-muted text-sm uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium">Kompaniya Nomi</th>
                            <th class="px-6 py-4 font-medium">Yaratuvchi Ismi</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Telefon</th>
                            <th class="px-6 py-4 font-medium">Sana</th>
                            <th class="px-6 py-4 font-medium text-right">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50 text-sm text-white/90">
                        @forelse($pendingCompanies as $company)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-semibold">{{ $company->name }}</td>
                            <td class="px-6 py-4">{{ $company->owner->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $company->owner->email ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $company->owner->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $company->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form method="POST" action="{{ route('superadmin.approve', $company->id) }}">
                                        @csrf
                                        <button class="bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/30 px-3 py-1.5 rounded-lg transition-colors font-medium">Tasdiqlash</button>
                                    </form>
                                    <form method="POST" action="{{ route('superadmin.reject', $company->id) }}">
                                        @csrf
                                        <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1.5 rounded-lg transition-colors font-medium">Rad etish</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-dark-muted">Kutish holatidagi kompaniyalar mavjud emas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
