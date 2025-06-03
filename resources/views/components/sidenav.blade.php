<div class="w-64 text-slate-700 border border-slate-300 backdrop-blur-md bg-white/30 h-full p-6 shadow-lg flex flex-col z-50">
    {{-- Header --}}
    <div class="flex items-center space-x-3 mb-8">
        <i class="fas fa-money-bill-wave text-2xl"></i>
        <h2 class="text-2xl font-bold tracking-wider">FinYouth</h2>
    </div>

    {{-- User Profile --}}
    <div class="flex items-center p-3 bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg mb-8">
        <div class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center">
            <i class="fas fa-user text-lg"></i>
        </div>
        <div class="ml-3">
            @auth
                <p class="font-medium text-sm text-white">{{ Auth::user()->name }}</p>
            @endauth
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="space-y-3">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 {{ request()->is('dashboard') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('pemasukkan') }}" class="flex items-center space-x-3 p-3 {{ request()->is('pemasukkan*') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
            <i class="fas fa-arrow-down"></i>
            <span>Pemasukkan</span>
        </a>
        <a href="{{ route('pengeluaran') }}" class="flex items-center space-x-3 p-3 {{ request()->is('pengeluaran*') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
            <i class="fas fa-arrow-up"></i>
            <span>Pengeluaran</span>
        </a>
        <a href="{{ route('tabungan') }}" class="flex items-center space-x-3 p-3 {{ request()->is('tabungan*') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
            <i class="fas fa-arrow-up"></i>
            <span>Tabungan</span>
        </a>
        <a href="{{ route('chatbot.show') }}" class="flex items-center space-x-3 p-3 {{ request()->is('chatbot*') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
            <i class="fas fa-arrow-up"></i>
            <span>FinAi</span>
        </a>
        <div class="pt-4 mt-4 border-t border-blue-700">
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-3 {{ request()->is('profile*') ? 'bg-gradient-to-l from-amber-500 to-yellow-700 text-white translate-x-1' : '' }} hover:bg-gradient-to-l from-amber-500 to-yellow-700 rounded-lg transition-all hover:translate-x-1 hover:text-white">
                <i class="fas fa-arrow-up"></i>
                <span>Profil</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center hover:text-white space-x-3 p-3 hover:bg-red-600 rounded-lg transition-all hover:translate-x-1 w-full">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
    <div class="mt-auto">
    <x-footer/>
    </div>
</div>