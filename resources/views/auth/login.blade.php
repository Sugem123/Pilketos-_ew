@php $page_title = 'Login'; @endphp
<x-auth-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Pilketos" class="h-12">
                    <h1 class="text-3xl font-bold text-accent">PILKETOS</h1>
                </div>
                <p class="text-gray-500">Sistem Pemilihan Ketua OSIS</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-accent mb-6 text-center">Login Administrator</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all"
                               placeholder="admin@gmail.com">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all"
                               placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="w-full bg-accent text-secondary py-3 rounded-xl font-semibold hover:bg-gray-800 transition-colors">
                        Login
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-6">Pilketos v2.0 &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</x-auth-layout>
