@extends('admin.layout')

@section('title', 'Ulanyjylar')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">👥 Ulanyjy Dolandyryşy</h1>
    <p class="text-gray-500 mt-1">Platformadaky ähli ulanyjylary dolandyryň</p>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Jemi: <strong class="text-gray-800">{{ $users->total() }}</strong> ulanyjy</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Ady</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Telefon</th>
                    <th class="px-6 py-3">Rol</th>
                    <th class="px-6 py-3 text-center">Emläkler</th>
                    <th class="px-6 py-3 text-center">Halanlar</th>
                    <th class="px-6 py-3">Hasap açan wagty</th>
                    <th class="px-6 py-3">Hereketler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">#{{ $user->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($user->isAdmin())
                            <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2.5 py-1 rounded-full text-xs font-semibold">Admin</span>
                        @else
                            <span class="bg-teal-50 text-teal-700 border border-teal-200 px-2.5 py-1 rounded-full text-xs font-semibold">Ulanyjy</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $user->properties_count }}</td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $user->favorites_count }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @if(!$user->isAdmin())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs bg-rose-500 hover:bg-rose-600 text-white px-3 py-1 rounded-md transition font-medium"
                                    onclick="return confirm('Bu ulanyjyny pozmak isleýärsiňizmi?')">
                                🗑 Poz
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">👤</div>
                        <div>Ulanyjy tapylmady</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
