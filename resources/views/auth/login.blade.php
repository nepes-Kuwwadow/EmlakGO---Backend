<!DOCTYPE html>
<html lang="tk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EmläkGO - Panel Giriş</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 w-full max-w-md border border-emerald-900/20">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl mx-auto mb-3 shadow-inner">
                🏠
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                Emläk<span class="text-emerald-600">GO</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">Dolandyryş Paneline Giriş</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Email salgysy
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition text-sm text-gray-900"
                       placeholder="mysal@mail.com">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Parol
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition text-sm text-gray-900"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Meni ýatda sakla</label>
            </div>

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition duration-200 text-sm shadow-md shadow-emerald-600/30 cursor-pointer">
                Giriş etmek
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} EmläkGO — Ähli hukuklar goralýar.
        </p>
    </div>

</body>
</html>
