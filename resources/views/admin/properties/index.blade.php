@extends('admin.layout')

@section('title', auth()->user()->isAdmin() ? 'Emläkler' : 'Meniň Emläklerim')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            🏠 {{ auth()->user()->isAdmin() ? 'Emläk Dolandyryşy' : 'Meniň Emläklerim' }}
        </h1>
        <p class="text-gray-500 mt-1">
            @if(auth()->user()->isAdmin())
                Platformadaky ähli emläkleri dolandyryň we tassyklaň
            @else
                Siziň platforma goşan emläk bildirişleriňiz
            @endif
        </p>
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-xl shadow mb-6">
    <div class="flex border-b border-gray-200">
        <a href="{{ route('admin.properties.index') }}"
           class="px-6 py-3 text-sm font-medium {{ !request('status') ? 'text-emerald-700 border-b-2 border-emerald-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
            Hemmesi ({{ $properties->total() }})
        </a>
        <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}"
           class="px-6 py-3 text-sm font-medium {{ request('status') === 'pending' ? 'text-yellow-600 border-b-2 border-yellow-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
            Garaşylýar
        </a>
        <a href="{{ route('admin.properties.index', ['status' => 'active']) }}"
           class="px-6 py-3 text-sm font-medium {{ request('status') === 'active' ? 'text-emerald-700 border-b-2 border-emerald-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
            Aktif
        </a>
        <a href="{{ route('admin.properties.index', ['status' => 'rejected']) }}"
           class="px-6 py-3 text-sm font-medium {{ request('status') === 'rejected' ? 'text-red-600 border-b-2 border-red-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
            Ret edildi
        </a>
    </div>
</div>

<!-- Properties Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Emläk ady</th>
                    <th class="px-6 py-3">Kategoriýa</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-3">Eýesi</th>
                    @endif
                    <th class="px-6 py-3">Şäher</th>
                    <th class="px-6 py-3">Baha (TMT)</th>
                    <th class="px-6 py-3">Ýagdaý</th>
                    <th class="px-6 py-3">Hereketler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">#{{ $property->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ Str::limit($property->title, 35) }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $property->created_at->format('d.m.Y') }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $property->category->name ?? '—' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 text-gray-600">{{ $property->user->name ?? '—' }}</td>
                    @endif
                    <td class="px-6 py-4 text-gray-600">{{ $property->city }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ number_format($property->price, 0, '.', ' ') }}</td>
                    <td class="px-6 py-4">
                        @if($property->status === 'active')
                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Aktif</span>
                        @elseif($property->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-semibold">Garaşylýar</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Ret edildi</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{-- Diňe admin tassyklap we ret edip bilýär --}}
                            @if(auth()->user()->isAdmin())
                                @if($property->status !== 'active')
                                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded-md transition font-medium"
                                            onclick="return confirm('Tassyklamak isleýärsiňizmi?')">
                                        ✓ Tassykla
                                    </button>
                                </form>
                                @endif

                                @if($property->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-xs bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-md transition font-medium"
                                            onclick="return confirm('Ret etmek isleýärsiňizmi?')">
                                        ✗ Ret et
                                    </button>
                                </form>
                                @endif
                            @endif

                            {{-- Admin ýa-da emlägiň eýesi pozup bilýär --}}
                            @if(auth()->user()->isAdmin() || $property->user_id === auth()->id())
                            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs bg-rose-500 hover:bg-rose-600 text-white px-3 py-1 rounded-md transition font-medium"
                                        onclick="return confirm('Pozmagy tassyklaýarsyňyzmy? Bu yzyna dolap bolmaz!')">
                                    🗑 Poz
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">🏚️</div>
                        <div>{{ auth()->user()->isAdmin() ? 'Emläk tapylmady' : 'Sizde heniz bu ýagdaýda emläk ýok' }}</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($properties->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
