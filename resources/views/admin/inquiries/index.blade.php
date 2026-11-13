@extends('admin.layout')

@section('title', 'Soraglar')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        📩 {{ auth()->user()->isAdmin() ? 'Ähli Soraglar' : 'Emläklerime Gelen Soraglar' }}
    </h1>
    <p class="text-gray-500 mt-1">
        Müşderiler tarapyndan ugradylan habarlaşma we gyzyklanma hatlary
    </p>
</div>

<!-- Inquiries Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Jemi: <strong class="text-gray-800">{{ $inquiries->total() }}</strong> sany sorag</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Emläk</th>
                    <th class="px-6 py-3">Ugradan müşderi</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Telefon</th>
                    <th class="px-6 py-3">Hatyň mazmuny</th>
                    <th class="px-6 py-3">Senesi</th>
                    <th class="px-6 py-3">Hereket</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($inquiries as $inquiry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">#{{ $inquiry->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $inquiry->property->title ?? '—' }}</div>
                        <div class="text-xs text-emerald-600 font-semibold mt-0.5">
                            {{ number_format($inquiry->property->price ?? 0, 0, '.', ' ') }} TMT
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $inquiry->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <a href="mailto:{{ $inquiry->email }}" class="hover:underline text-emerald-700">
                            {{ $inquiry->email }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($inquiry->phone)
                            <a href="tel:{{ $inquiry->phone }}" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:underline">
                                📞 {{ $inquiry->phone }}
                            </a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 max-w-xs">
                        <p class="whitespace-pre-line text-xs bg-gray-50 p-2.5 rounded-lg border border-gray-150">
                            {{ $inquiry->message }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">
                        {{ $inquiry->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs bg-rose-500 hover:bg-rose-600 text-white px-3 py-1 rounded-md transition font-medium"
                                    onclick="return confirm('Bu soragy pozmak isleýärsiňizmi?')">
                                🗑 Poz
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">📩</div>
                        <div>Häzirlikçe hiç hili sorag gelmedi</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($inquiries->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $inquiries->links() }}
    </div>
    @endif
</div>
@endsection
