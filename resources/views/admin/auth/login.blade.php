<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — EssenseLuxe Admin</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @endif
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl shadow-black/20 p-8 sm:p-10">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-bold text-white mx-auto mb-4 shadow-lg shadow-indigo-500/30">
                E
            </div>
            <h1 class="text-2xl font-bold text-slate-800">EssenseLuxe</h1>
            <p class="text-sm text-slate-500 mt-1">Panel Administrasi</p>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-5">
                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5">
                <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5">
                <div class="flex items-center gap-3 text-red-700 mb-2">
                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                    <strong class="text-sm">Terjadi kesalahan</strong>
                </div>
                <ul class="text-sm text-red-600 ml-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-11 h-11 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email"
                           class="flex-1 px-3 py-2.5 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           value="{{ old('email') }}"
                           placeholder="superadmin@essenseluxe.com"
                           required autofocus autocomplete="email">
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-11 h-11 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="flex-1 px-3 py-2.5 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           placeholder="Masukkan password"
                           required autocomplete="current-password">
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200 cursor-pointer">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
