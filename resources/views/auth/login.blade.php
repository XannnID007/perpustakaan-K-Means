<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-cream-50 to-primary-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <div class="flex justify-center">
                    <span class="text-4xl">📚</span>
                </div>
                <h2 class="mt-4 text-2xl font-semibold text-gray-900">Masuk ke Akun Anda</h2>
                <p class="mt-2 text-sm text-gray-600">Atau <a href="{{ route('register') }}"
                        class="font-medium text-primary-600">daftar akun baru</a></p>
            </div>

            <!-- Login Card -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                class="input-field @error('email') border-red-300 @enderror"
                                placeholder="contoh@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" name="password" type="password" required
                                class="input-field @error('password') border-red-300 @enderror"
                                placeholder="Masukkan password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-primary-600">Lupa
                                    password?</a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full btn btn-primary">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
