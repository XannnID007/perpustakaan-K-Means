<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-cream-50 to-primary-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <div class="flex justify-center">
                    <span class="text-4xl">📚</span>
                </div>
                <h2 class="mt-4 text-2xl font-semibold text-gray-900">Daftar Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}"
                        class="font-medium text-primary-600">Masuk di sini</a></p>
            </div>

            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                class="input-field @error('name') border-red-300 @enderror"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="input-field" placeholder="Ulangi password">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full btn btn-primary">
                            Daftar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
