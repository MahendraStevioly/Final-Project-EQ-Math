<aside id="sidebar" class="sidebar fixed lg:static inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-blue-700 to-blue-900 text-white transform -translate-x-full lg:translate-x-0 overflow-y-auto">
    <!-- Logo -->
    <div class="p-6 border-b border-blue-600">
        <a href="{{ url('/') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                <i class="fas fa-square-root-alt text-blue-700 text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-wide">EQ - Math</h1>
                <p class="text-blue-200 text-xs">Panel {{ ucfirst(Auth::user()->role) }}</p>
            </div>
        </a>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-blue-600">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-lg font-bold">
                {{ substr(Auth::user()->nama_lengkap, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold truncate">{{ Auth::user()->nama_lengkap }}</p>
                <p class="text-blue-200 text-sm truncate">{{ Auth::user()->email }}</p>
                @if(Auth::user()->role === 'siswa')
                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-500 text-white text-xs rounded-full">Aktif</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-1">
        <p class="text-blue-300 text-xs uppercase tracking-wider mb-2 px-3">Menu Utama</p>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-chart-pie w-6 text-center"></i>
                <span class="ml-3">Ringkasan</span>
            </a>
            <a href="{{ route('admin.pengajar.index') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.pengajar.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-chalkboard-teacher w-6 text-center"></i>
                <span class="ml-3">Data Pengajar</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.kelas.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-book w-6 text-center"></i>
                <span class="ml-3">Data Kelas</span>
            </a>
            <a href="{{ route('admin.jadwal.index') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.jadwal.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-calendar-alt w-6 text-center"></i>
                <span class="ml-3">Jadwal Kelas</span>
            </a>
            <a href="{{ route('admin.pembayaran.index') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.pembayaran.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-credit-card w-6 text-center"></i>
                <span class="ml-3">Pembayaran</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-user-graduate w-6 text-center"></i>
                <span class="ml-3">Data Siswa</span>
            </a>

            <p class="text-blue-300 text-xs uppercase tracking-wider mb-2 mt-6 px-3">Akun</p>
            <a href="{{ route('admin.pengaturan.index') }}" class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('admin.pengaturan.*') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-cog w-6 text-center"></i>
                <span class="ml-3">Pengaturan</span>
            </a>
        @else
            <a href="{{ route('siswa.dashboard') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('siswa.dashboard') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-home w-6 text-center"></i>
                <span class="ml-3">Beranda</span>
            </a>
            <a href="{{ route('siswa.pendaftaran') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('siswa.pendaftaran') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-book-open w-6 text-center"></i>
                <span class="ml-3">Pilih Kelas</span>
            </a>
            <a href="{{ route('siswa.riwayat') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('siswa.riwayat') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-history w-6 text-center"></i>
                <span class="ml-3">Riwayat</span>
            </a>
            <a href="{{ route('siswa.kelas_saya') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('siswa.kelas_saya') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fas fa-graduation-cap w-6 text-center"></i>
                <span class="ml-3">Kelas Saya</span>
            </a>

            <p class="text-blue-300 text-xs uppercase tracking-wider mb-2 mt-6 px-3">Bantuan</p>
            <a href="{{ route('siswa.bantuan') }}"
               class="nav-item flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-600 transition {{ request()->routeIs('siswa.bantuan') ? 'bg-blue-800 border-l-4 border-amber-400' : '' }}">
                <i class="fab fa-whatsapp w-6 text-center"></i>
                <span class="ml-3">Customer Service</span>
            </a>
        @endif

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center px-3 py-2.5 rounded-lg hover:bg-red-500 transition text-red-200 hover:text-white mt-2">
            <i class="fas fa-sign-out-alt w-6 text-center"></i>
            <span class="ml-3">Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-blue-600 mt-auto">
        <p class="text-blue-300 text-xs text-center">
            &copy; {{ date('Y') }} EQ - Math
        </p>
    </div>
</aside>