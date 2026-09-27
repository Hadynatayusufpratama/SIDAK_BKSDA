<nav x-data="{ open: false }" class="bg-white border-b border-emerald-900/10 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo SIDAK BKSDA -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Route::has('konservasi.dashboard') ? route('konservasi.dashboard') : '#' }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-bksda.jpeg') }}" alt="Logo BKSDA" class="h-9 w-auto object-contain rounded-md" onerror="this.src='https://via.placeholder.com/40?text=BKSDA'">
                        <div class="hidden md:block">
                            <span class="font-extrabold text-sm text-slate-800 tracking-wide block leading-none">SIDAK BKSDA</span>
                            <span class="text-[10px] font-bold text-emerald-700 tracking-wider">SULAWESI TENGAH</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-2 sm:-my-px sm:flex items-center">
                    @if(Route::has('konservasi.dashboard'))
                    <x-nav-link :href="route('konservasi.dashboard')" :active="request()->routeIs('konservasi.dashboard')" class="text-xs font-bold transition">
                        <i class="fas fa-chart-pie mr-2 text-emerald-600"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif

                    <!-- Dropdown Sub-Bidang / Rekapitulasi -->
                    <div class="relative" x-data="{ openSub: false }">
                        <button @click="openSub = !openSub" @click.away="openSub = false" class="inline-flex items-center px-3 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 focus:outline-none transition">
                            <i class="fas fa-database mr-2 text-emerald-600"></i>
                            <span>Rekap Sub-Bidang</span>
                            <svg class="ml-1 h-4 w-4 fill-current text-slate-400" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="openSub" x-transition class="absolute left-0 mt-2 w-72 max-h-96 overflow-y-auto rounded-xl shadow-lg bg-white border border-slate-100 py-2 z-50">
                            <div class="px-4 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pilih Sub-Bidang</div>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.01']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.01 Kawasan Konservasi
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.02']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.02 Perencanaan Pengelolaan
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.03']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.03 Monitoring Batas
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.04']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.04 Evaluasi Kesesuaian Fungsi
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.05']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.05 Ekosistem Kawasan
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'A.06']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                A.06 Penataan Zonasi/Blok
                            </a>
                            <a href="{{ route('rekap.index', ['sub_bidang' => 'B.01']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600">
                                B.01 Kelompok Binaan
                            </a>
                        </div>
                    </div>

                    @if(Route::has('konservasi.peta'))
                    <x-nav-link :href="route('konservasi.peta')" :active="request()->routeIs('konservasi.peta')" class="text-xs font-bold transition">
                        <i class="fas fa-map-location-dot mr-2 text-emerald-600"></i> {{ __('GIS') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-xs font-bold rounded-xl text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none transition duration-150">
                            <div class="w-6 h-6 rounded-lg bg-emerald-800 text-white font-bold flex items-center justify-center text-[10px] uppercase">
                                {{ strtoupper(substr(Auth::user()?->name ?? 'AD', 0, 2)) }}
                            </div>
                            <div>{{ Auth::user()?->name ?? 'Guest' }}</div>

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @auth
                            @if(Route::has('profile.edit'))
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs font-semibold">
                                <i class="fas fa-user-gear mr-2 text-slate-400"></i> {{ __('Profile Pengguna') }}
                            </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-xs font-semibold text-rose-600 hover:text-rose-700">
                                    <i class="fas fa-right-from-bracket mr-2 text-rose-500"></i> {{ __('Keluar / Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <x-dropdown-link :href="route('login')" class="text-xs font-semibold text-emerald-600">
                                <i class="fas fa-right-to-bracket mr-2 text-emerald-500"></i> {{ __('Masuk / Login') }}
                            </x-dropdown-link>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @if(Route::has('konservasi.dashboard'))
            <x-responsive-nav-link :href="route('konservasi.dashboard')" :active="request()->routeIs('konservasi.dashboard')" class="rounded-xl">
                <i class="fas fa-chart-pie mr-2 text-emerald-600"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endif

            <!-- Menu Sub-Bidang Mobile -->
            <div class="pt-2 pb-1 border-t border-slate-100">
                <div class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rekap Sub-Bidang</div>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.01'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.01 Kawasan Konservasi
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.02'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.02 Perencanaan Pengelolaan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.03'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.03 Monitoring Batas
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.04'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.04 Evaluasi Kesesuaian Fungsi
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.05'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.05 Ekosistem Kawasan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'A.06'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> A.06 Penataan Zonasi/Blok
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rekap.index', ['sub_bidang' => 'B.01'])" class="rounded-xl pl-4">
                    <i class="fas fa-file-lines mr-2 text-slate-400"></i> B.01 Kelompok Binaan
                </x-responsive-nav-link>
            </div>

            @if(Route::has('konservasi.peta'))
            <x-responsive-nav-link :href="route('konservasi.peta')" :active="request()->routeIs('konservasi.peta')" class="rounded-xl">
                <i class="fas fa-map-location-dot mr-2 text-emerald-600"></i> {{ __('GIS') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-100 bg-slate-50/50">
            @auth
                <div class="px-6 flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-800 text-white font-bold flex items-center justify-center text-xs uppercase shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1 px-4">
                    @if(Route::has('profile.edit'))
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl">
                        <i class="fas fa-user-gear mr-2 text-slate-400"></i> {{ __('Profile Pengguna') }}
                    </x-responsive-nav-link>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="rounded-xl text-rose-600">
                            <i class="fas fa-right-from-bracket mr-2 text-rose-500"></i> {{ __('Keluar / Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-6 mb-3">
                    <div class="font-bold text-sm text-slate-800">Tamu / Guest</div>
                    <a href="{{ route('login') }}" class="inline-block mt-2 text-xs font-bold text-emerald-700 hover:underline">
                        Klik di sini untuk Login
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>