@extends('admin.layout')

@section('title', 'Teswirler')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        💬 {{ auth()->user()->isAdmin() ? 'Emläk Teswirleri' : 'Meniň Emläklerimiň Teswirleri' }}
    </h1>
    <p class="text-gray-500 mt-1">
        {{ auth()->user()->isAdmin()
            ? 'Ulanyjylar we müşderiler tarapyndan goýlan teswirler we baha bermeler'
            : 'Siziň emläkleriňize ulanyjylar tarapyndan ýazylan teswirler' }}
    </p>
</div>

<!-- Comments Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Jemi: <strong class="text-gray-800">{{ $comments->total() }}</strong> sany teswir</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Emläk</th>
                    <th class="px-6 py-3">Awtor</th>
                    <th class="px-6 py-3">Baha (Ýyldyz)</th>
                    <th class="px-6 py-3">Teswir</th>
                    <th class="px-6 py-3">Senesi</th>
                    <th class="px-6 py-3">Hereket</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($comments as $comment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">#{{ $comment->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800 max-w-xs truncate">{{ $comment->property->title ?? '—' }}</div>
                        <div class="text-xs text-emerald-600 font-semibold mt-0.5">
                            ID: #{{ $comment->property_id }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $comment->name }}</div>
                        @if($comment->user)
                            <span class="text-xs text-emerald-600">Agza (ID: #{{ $comment->user_id }})</span>
                        @else
                            <span class="text-xs text-gray-400">Myhman</span>
                        @endif

                        @if($comment->parent_id)
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium" title="Teswir ID: #{{ $comment->parent_id }}">
                                    ↳ #{{ $comment->parent_id }} jogap
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->rating)
                            <div class="flex items-center text-amber-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $comment->rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                                <span class="ml-1 text-xs font-semibold text-gray-700">({{ $comment->rating }}/5)</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full font-medium">
                                Jogap (Baha ýok)
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 max-w-md">
                        <p class="whitespace-pre-line">{{ $comment->comment }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $comment->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Bu teswiri hakykatdan hem pozmak isleýärsiňizmi?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-medium transition cursor-pointer">
                                🗑 Poz
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        {{ auth()->user()->isAdmin() ? 'Hiç hili teswir ýok' : 'Siziň emläkleriňize heniz teswir ýazylmandyr' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($comments->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection
