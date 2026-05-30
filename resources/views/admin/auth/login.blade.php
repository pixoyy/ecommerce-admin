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
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">EssenseLuxe</h1>
                <p class="text-sm text-gray-500 mt-1">Panel Admin</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="superadmin@essenseluxe.com"
                               required autofocus autocomplete="email">
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password"
                               required autocomplete="current-password">
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-2 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                        Masuk
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">EssenseLuxe v1.0</p>
        </div>
    </div>
</body>
</html>
