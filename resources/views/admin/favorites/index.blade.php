@extends('admin.layout')

@section('title', 'Halananlar')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        ❤️ {{ auth()->user()->isAdmin() ? 'Ähli Halanan Emläkler' : 'Emläklerimiň Halananlary' }}
    </h1>
    <p class="text-gray-500 mt-1">
        Ulanyjylar tarapyndan halananlara goşulan emläkler
    </p>
</div>

<!-- Favorites Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Jemi: <strong class="text-gray-800">{{ $favorites->total() }}</strong> gezek halandy</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Emläk</th>
                    <th class="px-6 py-3">Kategoriýa</th>
                    <th class="px-6 py-3">Baha</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-3">Emlägiň eýesi</th>
                    @endif
                    <th class="px-6 py-3">Halan ulanyjy</th>
                    <th class="px-6 py-3">Senesi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($favorites as $fav)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">#{{ $fav->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $fav->property->title ?? '—' }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $fav->property->city ?? '—' }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $fav->property->category->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ number_format($fav->property->price ?? 0, 0, '.', ' ') }} TMT
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 text-gray-600">{{ $fav->property->user->name ?? '—' }}</td>
                    @endif
                    <td class="px-6 py-4 font-medium text-emerald-700">
                        {{ $fav->user->name ?? 'Ulanyjy' }} ({{ $fav->user->email ?? '—' }})
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $fav->created_at->format('d.m.Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">❤️</div>
                        <div>Häzirlikçe halanan emläk ýok</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($favorites->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $favorites->links() }}
    </div>
    @endif
</div>
@endsection
