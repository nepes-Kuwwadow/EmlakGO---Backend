@extends('admin.layout')

@section('title', 'Baş Sahypa')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        📊 {{ auth()->user()->isAdmin() ? 'Admin Paneli' : 'Şahsy Dolandyryş Paneli' }}
    </h1>
    <p class="text-gray-500 mt-1">
        @if(auth()->user()->isAdmin())
            EmläkGO platformasynyň umumy statistikasy
        @else
            Siziň goýan emläkleriňiz we bildiriş statistikasy
        @endif
    </p>
</div>

<!-- Stats Cards (Clickable / Basylýan) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ auth()->user()->isAdmin() ? '4' : '3' }} gap-6 mb-8">
    @if(auth()->user()->isAdmin() && isset($stats['users']))
    <a href="{{ route('admin.users.index') }}"
       class="block bg-white rounded-xl shadow p-6 border-l-4 border-emerald-600 hover:shadow-lg hover:-translate-y-1 transition duration-200 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-emerald-700 transition">Ulanyjylar</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['users'] }}</p>
            </div>
            <div class="text-4xl group-hover:scale-110 transition-transform">👥</div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-emerald-600 font-semibold">
            <span>Ulanyjylary gör</span>
            <span>→</span>
        </div>
    </a>
    @endif

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-emerald-500 hover:shadow-lg transition duration-200 flex flex-col justify-between">
        <a href="{{ route('admin.properties.index') }}" class="group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-emerald-700 transition">
                        {{ auth()->user()->isAdmin() ? 'Jemi Emläkler' : 'Meniň Emläklerim' }}
                    </p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['properties'] }}</p>
                </div>
                <div class="text-4xl group-hover:scale-110 transition-transform">🏠</div>
            </div>
        </a>

        <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-1.5 text-xs">
            <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}"
               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-2.5 py-1 rounded-full font-medium transition"
               title="Garaşylýan emläkleri gör">
                Garaşylýar: <strong>{{ $stats['pending'] }}</strong>
            </a>
            <a href="{{ route('admin.properties.index', ['status' => 'active']) }}"
               class="bg-green-100 hover:bg-green-200 text-green-800 px-2.5 py-1 rounded-full font-medium transition"
               title="Aktif emläkleri gör">
                Aktif: <strong>{{ $stats['active'] }}</strong>
            </a>
            <a href="{{ route('admin.properties.index', ['status' => 'rejected']) }}"
               class="bg-red-100 hover:bg-red-200 text-red-800 px-2.5 py-1 rounded-full font-medium transition"
               title="Ret edilen emläkleri gör">
                Ret: <strong>{{ $stats['rejected'] }}</strong>
            </a>
        </div>
    </div>

    <a href="{{ route('admin.inquiries.index') }}"
       class="block bg-white rounded-xl shadow p-6 border-l-4 border-teal-500 hover:shadow-lg hover:-translate-y-1 transition duration-200 group flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-teal-700 transition">
                    {{ auth()->user()->isAdmin() ? 'Soraglar' : 'Emläklerime Gelen Soraglar' }}
                </p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['inquiries'] }}</p>
            </div>
            <div class="text-4xl group-hover:scale-110 transition-transform">📩</div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-teal-600 font-semibold">
            <span>Soraglary aç</span>
            <span>→</span>
        </div>
    </a>

    <a href="{{ route('admin.favorites.index') }}"
       class="block bg-white rounded-xl shadow p-6 border-l-4 border-rose-500 hover:shadow-lg hover:-translate-y-1 transition duration-200 group flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-rose-700 transition">
                    {{ auth()->user()->isAdmin() ? 'Halanlar' : 'Emläklerimiň Halanan Sany' }}
                </p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['favorites'] }}</p>
            </div>
            <div class="text-4xl group-hover:scale-110 transition-transform">❤️</div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-rose-600 font-semibold">
            <span>Halananlary gör</span>
            <span>→</span>
        </div>
    </a>
</div>

<!-- Recent Properties Table -->
<div class="bg-white rounded-xl shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">
            {{ auth()->user()->isAdmin() ? 'Soňky Emläkler' : 'Meniň Soňky Emläklerim' }}
        </h2>
        <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
            Hemmesini gör →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Emläk</th>
                    <th class="px-6 py-3">Kategoriýa</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-3">Eýesi</th>
                    @endif
                    <th class="px-6 py-3">Baha</th>
                    <th class="px-6 py-3">Ýagdaý</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentProperties as $property)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ Str::limit($property->title, 40) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $property->category->name ?? '—' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 text-gray-600">{{ $property->user->name ?? '—' }}</td>
                    @endif
                    <td class="px-6 py-4 text-gray-800 font-medium">{{ number_format($property->price, 0, '.', ' ') }} TMT</td>
                    <td class="px-6 py-4">
                        @if($property->status === 'active')
                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">Aktif</span>
                        @elseif($property->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-medium">Garaşylýar</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-medium">Ret edildi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="px-6 py-8 text-center text-gray-400">
                        {{ auth()->user()->isAdmin() ? 'Emläk ýok' : 'Siz heniz emläk goşmadyňyz' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
