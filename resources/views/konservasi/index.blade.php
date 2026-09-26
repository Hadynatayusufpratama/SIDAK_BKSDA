<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Data - SIDAK BKSDA Sulawesi Tengah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js untuk Modal Pop-Up -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative" x-data="{
    openModal: false,
    modalContent: '',
    modalTitle: '',
    get modalDetails() {
        if (!this.modalContent || this.modalContent.trim() === '-') return [];

        return this.modalContent.split(' | ').filter(detail => detail.trim() && detail.trim() !== '-').map(detail => {
            const separator = detail.indexOf(': ');
            return separator < 0
                ? { label: 'Detail', value: detail.trim() }
                : { label: detail.slice(0, separator).trim(), value: detail.slice(separator + 2).trim() };
        });
    }
}">

    <!-- BACKGROUND GLOBAL KAWASAN KONSERVASI -->
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1920&auto=format&fit=crop" alt="Background Konservasi" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-slate-100/75 backdrop-blur-[2px]"></div>
    </div>

    <!-- NAVBAR UTAMA -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo & Title Instansi -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl bg-forest-700/10 border border-forest-700/20 flex items-center justify-center p-1.5 shadow-xs overflow-hidden">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Input data konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>

            <!-- Status & User Profile -->
            <div class="flex items-center space-x-4">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Sistem Aktif
                </span>
                <div class="flex items-center space-x-2.5 border-l pl-4 border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-forest-700 text-white flex items-center justify-center font-bold text-xs shadow-sm uppercase">
                        {{ strtoupper(substr(Auth::user()->name ?? 'User', 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px] text-slate-500">{{ Auth::user()->role ?? 'Operator' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HEADER BANNER & TOMBOL TAMBAH -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Rekapitulasi Data Konservasi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar entri data kinerja, pemantauan satwa, dan kegiatan kawasan BKSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.create') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition">
                    <i class="fa-solid fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>

        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($selectedSub)
        <!-- RINGKASAN STAT CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Baris Data</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ method_exists($data, 'total') ? $data->total() : count($data) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-layer-group text-[10px]"></i> Database Tersimpan
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold border border-amber-100 shadow-xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pencarian</p>
                    <h3 class="text-lg font-extrabold text-slate-900 mt-1 truncate max-w-[180px]">
                        {{ request('sub_bidang') ? 'Filter: '.request('sub_bidang') : (request('bidang') ? 'Bidang Terpilih' : (request('search') ? request('search') : 'Semua Data')) }}
                    </h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-filter text-[10px]"></i> Filter Aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold border border-emerald-100 shadow-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peta GIS Terkait</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">Palu & Sulteng</h3>
                    <p class="text-[11px] text-blue-600 font-semibold mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-map-location-dot text-[10px]"></i> Terhubung Koordinat
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold border border-blue-100 shadow-xs">
                    <i class="fa-solid fa-satellite"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- CARD FILTER BERTINGKAT (BIDANG -> SUB-BIDANG) -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-forest-700/10 text-forest-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Pilih Kategori Data Konservasi</h3>
                </div>
                @if(request('bidang') || request('sub_bidang') || request('search'))
                    <a href="{{ route('rekap.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-800 flex items-center gap-1">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filter
                    </a>
                @endif
            </div>

            <form action="{{ route('rekap.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    <!-- 1. Dropdown Bidang Utama -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">1. Bidang Utama</label>
                        <select name="bidang" id="bidangSelect" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white transition shadow-xs">
                            <option value="">-- Pilih Bidang Utama --</option>
                            @if(isset($masterBidang) && is_array($masterBidang))
                                @foreach($masterBidang as $key => $b)
                                    <option value="{{ $key }}" {{ request('bidang') == $key ? 'selected' : '' }}>
                                        {{ $b['nama'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- 2. Dropdown Sub-Bidang -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">2. Sub-Bidang / Kategori</label>
                        <select name="sub_bidang" id="subBidangSelect" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white transition shadow-xs" {{ !$selectedBidang ? 'disabled' : '' }}>
                            <option value="">-- Pilih Sub-Bidang --</option>
                            @if($selectedBidang && isset($masterBidang[$selectedBidang]))
                                @foreach($masterBidang[$selectedBidang]['subs'] as $subKode => $subNama)
                                    <option value="{{ $subKode }}" {{ $selectedSub == $subKode ? 'selected' : '' }}>
                                        {{ $subNama }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Tombol Tampilkan -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-forest-700 hover:bg-forest-800 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-xs transition flex items-center justify-center gap-2 h-[40px]">
                            <i class="fa-solid fa-filter"></i> Tampilkan Data
                        </button>
                    </div>

                </div>
            </form>
        </div>

        @if($selectedSub)
        <!-- DATA SUB-BIDANG TERPILIH -->
        <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Header Judul, Form Cari, & Tombol Unduh -->
            <div class="p-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Data {{ $masterBidang[$selectedBidang]['subs'][$selectedSub] ?? $selectedSub }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Data untuk sub-bidang yang dipilih</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Form Pencarian Teks -->
                    <form action="{{ route('rekap.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        @if(request('bidang')) <input type="hidden" name="bidang" value="{{ request('bidang') }}"> @endif
                        @if(request('sub_bidang')) <input type="hidden" name="sub_bidang" value="{{ request('sub_bidang') }}"> @endif
                        
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari kata kunci..." 
                                   class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white transition shadow-xs">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            Cari
                        </button>
                    </form>

                    <!-- Tombol Unduh PDF & Excel -->
                    @if($selectedSub)
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="{{ route('konservasi.export.pdf', ['bidang' => $selectedBidang, 'sub_bidang' => $selectedSub]) }}"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition shadow-xs whitespace-nowrap">
                            <i class="fa-solid fa-file-pdf mr-1.5 text-sm"></i> Unduh PDF
                        </a>

                        <a href="{{ route('konservasi.export.excel', ['bidang' => $selectedBidang, 'sub_bidang' => $selectedSub]) }}"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition shadow-xs whitespace-nowrap">
                            <i class="fa-solid fa-file-excel mr-1.5 text-sm"></i> Unduh Excel
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                @if($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.01')
                    <table class="min-w-[2200px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th rowspan="2" class="py-3 px-4">No</th>
                                <th rowspan="2" class="py-3 px-4">Tahun</th>
                                <th rowspan="2" class="py-3 px-4">Kawasan Konservasi</th>
                                <th rowspan="2" class="py-3 px-4">Perubahan Data Kawasan</th>
                                <th colspan="4" class="py-3 px-4 text-center border-l border-slate-200">SK Penunjukan Parsial</th>
                                <th colspan="5" class="py-3 px-4 text-center border-l border-slate-200">SK Penunjukan Provinsi</th>
                                <th colspan="5" class="py-3 px-4 text-center border-l border-slate-200">SK Penetapan</th>
                                <th rowspan="2" class="py-3 px-4 border-l border-slate-200">Batas Geografis Kawasan</th>
                                <th rowspan="2" class="py-3 px-4">Keterangan</th>
                                <th rowspan="2" class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-bold uppercase text-slate-400">
                                <th class="py-3 px-4 border-l border-slate-200">Nomor SK</th>
                                <th class="py-3 px-4">Tanggal SK</th>
                                <th class="py-3 px-4">Luas</th>
                                <th class="py-3 px-4">File SK</th>
                                <th class="py-3 px-4 border-l border-slate-200">Data Tersedia</th>
                                <th class="py-3 px-4">Nomor SK</th>
                                <th class="py-3 px-4">Tanggal SK</th>
                                <th class="py-3 px-4">Luas</th>
                                <th class="py-3 px-4">File SK</th>
                                <th class="py-3 px-4 border-l border-slate-200">Data Tersedia</th>
                                <th class="py-3 px-4">Nomor SK</th>
                                <th class="py-3 px-4">Tanggal SK</th>
                                <th class="py-3 px-4">Luas</th>
                                <th class="py-3 px-4">File SK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }
                                    }

                                    foreach ([
                                        '[SK Parsial]' => 'sk parsial',
                                        '[SK Provinsi]' => 'sk provinsi',
                                        '[SK Penetapan]' => 'sk penetapan',
                                    ] as $legacySection => $fieldPrefix) {
                                        $pattern = '/' . preg_quote($legacySection, '/') . '\\s+No: (.*?), Tgl: (.*?), Luas: (.*?) Ha/';
                                        if (preg_match($pattern, (string) $item->keterangan, $legacyMatch)) {
                                            $detailFields[$fieldPrefix . ' nomor'] ??= trim($legacyMatch[1]);
                                            $detailFields[$fieldPrefix . ' tanggal'] ??= trim($legacyMatch[2]);
                                            $detailFields[$fieldPrefix . ' luas'] ??= trim($legacyMatch[3]);
                                        }
                                    }

                                    $changeStatus = match (strtolower($detailFields['ada perubahan'] ?? '')) {
                                        'ya' => 'Ya, ada perubahan',
                                        'tidak' => 'Tidak ada',
                                        default => isset($detailFields['sk parsial nomor']) ? 'Ya, ada perubahan' : '-',
                                    };
                                    $provinceStatus = match (strtolower($detailFields['sk provinsi tersedia'] ?? '')) {
                                        'ya' => 'Tersedia',
                                        'tidak' => 'Tidak tersedia',
                                        default => isset($detailFields['sk provinsi nomor']) ? 'Tersedia' : '-',
                                    };
                                    $appointmentStatus = match (strtolower($detailFields['sk penetapan tersedia'] ?? '')) {
                                        'ya' => 'Tersedia',
                                        'tidak' => 'Tidak tersedia',
                                        default => isset($detailFields['sk penetapan nomor']) ? 'Tersedia' : '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? $detailFields['kawasan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $changeStatus }}</td>
                                    <td class="py-4 px-4 border-l border-slate-100">{{ $detailFields['sk parsial nomor'] ?? $detailFields['sk parsial no'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['sk parsial tanggal'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ isset($detailFields['sk parsial luas']) ? $detailFields['sk parsial luas'] . ' Ha' : '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['sk parsial file']))<a href="{{ asset('storage/' . $detailFields['sk parsial file']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat file</a>@else-@endif</td>
                                    <td class="py-4 px-4 border-l border-slate-100">{{ $provinceStatus }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['sk provinsi nomor'] ?? $detailFields['sk provinsi no'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['sk provinsi tanggal'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ isset($detailFields['sk provinsi luas']) ? $detailFields['sk provinsi luas'] . ' Ha' : '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['sk provinsi file']))<a href="{{ asset('storage/' . $detailFields['sk provinsi file']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat file</a>@else-@endif</td>
                                    <td class="py-4 px-4 border-l border-slate-100">{{ $appointmentStatus }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['sk penetapan nomor'] ?? $detailFields['sk penetapan no'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['sk penetapan tanggal'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ isset($detailFields['sk penetapan luas']) ? $detailFields['sk penetapan luas'] . ' Ha' : '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['sk penetapan file']))<a href="{{ asset('storage/' . $detailFields['sk penetapan file']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat file</a>@else-@endif</td>
                                    <td class="py-4 px-4 border-l border-slate-100">@if(!empty($detailFields['shapefile zip']))<a href="{{ asset('storage/' . $detailFields['shapefile zip']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat shapefile</a>@else-@endif</td>
                                    <td class="py-4 px-4 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="21" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.01 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.02')
                    <table class="min-w-[1500px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th rowspan="2" class="py-3 px-4">No</th>
                                <th rowspan="2" class="py-3 px-4">Tahun</th>
                                <th rowspan="2" class="py-3 px-4">Kawasan Konservasi</th>
                                <th rowspan="2" class="py-3 px-4">Ketersediaan Data</th>
                                <th colspan="4" class="py-3 px-4 text-center border-l border-slate-200">SK Penetapan Dokumen RPJP</th>
                                <th rowspan="2" class="py-3 px-4 border-l border-slate-200">Keterangan</th>
                                <th rowspan="2" class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-bold uppercase text-slate-400">
                                <th class="py-3 px-4 border-l border-slate-200">Nomor Surat Keputusan</th>
                                <th class="py-3 px-4">Tanggal Pengesahan</th>
                                <th class="py-3 px-4">Periode Berakhir RPJ Panjang</th>
                                <th class="py-3 px-4">File SK Penetapan Dokumen RPJP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }

                                        if (preg_match('/\[RPJP\] No SK: (.*?), Tgl Pengesahan: (.*?), Periode Berakhir: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['sk rpjp nomor'] ??= trim($legacyMatch[1]);
                                            $detailFields['sk rpjp tanggal pengesahan'] ??= trim($legacyMatch[2]);
                                            $detailFields['sk rpjp periode berakhir'] ??= trim($legacyMatch[3]);
                                        }
                                        if (preg_match('/\[RPJP\] Status: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['ketersediaan rpjp'] ??= str_contains(strtolower($legacyMatch[1]), 'tidak') ? 'tidak' : 'ya';
                                        }
                                    }

                                    $availability = match (strtolower($detailFields['ketersediaan rpjp'] ?? '')) {
                                        'ya' => 'Tersedia',
                                        'tidak' => 'Tidak tersedia (Nihil)',
                                        default => isset($detailFields['sk rpjp nomor']) ? 'Tersedia' : '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? $detailFields['kawasan rpjp'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $availability }}</td>
                                    <td class="py-4 px-4 border-l border-slate-100">{{ $detailFields['sk rpjp nomor'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['sk rpjp tanggal pengesahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['sk rpjp periode berakhir'] ?? '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['sk rpjp file']))<a href="{{ asset('storage/' . $detailFields['sk rpjp file']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat file</a>@else-@endif</td>
                                    <td class="py-4 px-4 border-l border-slate-100 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.02 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.03')
                    <table class="min-w-[1900px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th rowspan="2" class="py-3 px-4">No</th>
                                <th rowspan="2" class="py-3 px-4">Tahun</th>
                                <th rowspan="2" class="py-3 px-4">Kawasan Konservasi</th>
                                <th rowspan="2" class="py-3 px-4">Kegiatan Monitoring Batas</th>
                                <th colspan="9" class="py-3 px-4 text-center border-l border-slate-200">Berita Acara Tata Batas (BATB)</th>
                                <th rowspan="2" class="py-3 px-4 border-l border-slate-200">Lokasi Geografis Hasil Kegiatan</th>
                                <th rowspan="2" class="py-3 px-4">Keterangan</th>
                                <th rowspan="2" class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-bold uppercase text-slate-400">
                                <th class="py-3 px-4 border-l border-slate-200">Jenis Kegiatan</th>
                                <th class="py-3 px-4">Nomor BATB</th>
                                <th class="py-3 px-4">Tanggal BATB</th>
                                <th class="py-3 px-4">Pal Baik</th>
                                <th class="py-3 px-4">Pal Rusak</th>
                                <th class="py-3 px-4">Pal Hilang</th>
                                <th class="py-3 px-4">Total Pal</th>
                                <th class="py-3 px-4">Panjang Pal Batas</th>
                                <th class="py-3 px-4">Dokumen BATB</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }

                                        if (preg_match('/\[Monitoring BATB\] Jenis: (.*?), No BATB: (.*?), Tgl BATB: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['jenis kegiatan'] ??= trim($legacyMatch[1]);
                                            $detailFields['nomor batb'] ??= trim($legacyMatch[2]);
                                            $detailFields['tanggal batb'] ??= trim($legacyMatch[3]);
                                        }
                                        if (preg_match('/\[Pal Batas\] Baik: (.*?), Rusak: (.*?), Hilang: (.*?), Panjang: (.*?) Km/', $detailLine, $legacyMatch)) {
                                            $detailFields['pal baik'] ??= trim($legacyMatch[1]);
                                            $detailFields['pal rusak'] ??= trim($legacyMatch[2]);
                                            $detailFields['pal hilang'] ??= trim($legacyMatch[3]);
                                            $detailFields['panjang pal km'] ??= trim($legacyMatch[4]);
                                        }
                                        if (preg_match('/\[Monitoring BATB\] Status: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['ada kegiatan monitoring'] ??= str_contains(strtolower($legacyMatch[1]), 'tidak') ? 'tidak' : 'ya';
                                        }
                                    }

                                    $monitoringStatus = match (strtolower($detailFields['ada kegiatan monitoring'] ?? '')) {
                                        'ya' => 'Ya, Ada/Tersedia',
                                        'tidak' => 'Tidak ada (Nihil)',
                                        default => isset($detailFields['nomor batb']) ? 'Ya, Ada/Tersedia' : '-',
                                    };
                                    $totalPal = $detailFields['pal total'] ?? null;
                                    if ($totalPal === null && (isset($detailFields['pal baik']) || isset($detailFields['pal rusak']) || isset($detailFields['pal hilang']))) {
                                        $totalPal = (int) ($detailFields['pal baik'] ?? 0) + (int) ($detailFields['pal rusak'] ?? 0) + (int) ($detailFields['pal hilang'] ?? 0);
                                    }
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? $detailFields['kawasan monitoring'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $monitoringStatus }}</td>
                                    <td class="py-4 px-4 border-l border-slate-100">{{ $detailFields['jenis kegiatan'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['nomor batb'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['tanggal batb'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['pal baik'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['pal rusak'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['pal hilang'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $totalPal ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ isset($detailFields['panjang pal km']) ? $detailFields['panjang pal km'] . ' Km' : '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['dokumen batb']))<a href="{{ asset('storage/' . $detailFields['dokumen batb']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat dokumen</a>@else-@endif</td>
                                    <td class="py-4 px-4 border-l border-slate-100">@if(!empty($detailFields['shapefile monitoring zip']))<a href="{{ asset('storage/' . $detailFields['shapefile monitoring zip']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat shapefile</a>@else-@endif</td>
                                    <td class="py-4 px-4 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.03 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.04')
                    <table class="min-w-[1500px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Kawasan Konservasi</th>
                                <th class="py-3 px-4">Ketersediaan Data</th>
                                <th class="py-3 px-4">Tanggal Pelaksanaan</th>
                                <th class="py-3 px-4">Rekomendasi</th>
                                <th class="py-3 px-4">Tindak Lanjut</th>
                                <th class="py-3 px-4">File Dokumen</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }

                                        if (preg_match('/^Kawasan Evaluasi: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['kawasan konservasi'] ??= trim($legacyMatch[1]);
                                        }
                                        if (preg_match('/^\[Evaluasi\] Tgl Pelaksanaan: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['tanggal pelaksanaan evaluasi'] ??= trim($legacyMatch[1]);
                                        }
                                        if (preg_match('/^\[Evaluasi\] Status: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['ketersediaan evaluasi'] ??= str_contains(strtolower($legacyMatch[1]), 'tidak') ? 'tidak' : 'ya';
                                        }
                                    }

                                    $availability = match (strtolower($detailFields['ketersediaan evaluasi'] ?? '')) {
                                        'ya' => 'Tersedia',
                                        'tidak' => 'Tidak tersedia (Nihil)',
                                        default => isset($detailFields['tanggal pelaksanaan evaluasi']) ? 'Tersedia' : '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $availability }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['tanggal pelaksanaan evaluasi'] ?? $detailFields['[evaluasi] tgl pelaksanaan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 min-w-64">{{ $detailFields['rekomendasi evaluasi'] ?? $detailFields['rekomendasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4 min-w-64">{{ $detailFields['tindak lanjut evaluasi'] ?? $detailFields['tindak lanjut'] ?? '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['file dokumen evaluasi']))<a href="{{ asset('storage/' . $detailFields['file dokumen evaluasi']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat dokumen</a>@else-@endif</td>
                                    <td class="py-4 px-4 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.04 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.05')
                    <table class="min-w-[1250px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Kawasan Konservasi</th>
                                <th class="py-3 px-4">Ketersediaan Data</th>
                                <th class="py-3 px-4">Shapefile Area (ZIP)</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }

                                        if (preg_match('/^Kawasan Ekosistem: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['kawasan konservasi'] ??= trim($legacyMatch[1]);
                                        }
                                        if (preg_match('/^\[Ekosistem\] Status Data: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['ketersediaan ekosistem'] ??= str_contains(strtolower($legacyMatch[1]), 'tidak') ? 'tidak' : 'ya';
                                        }
                                    }

                                    $availability = match (strtolower($detailFields['ketersediaan ekosistem'] ?? '')) {
                                        'ya' => 'Tersedia',
                                        'tidak' => 'Tidak tersedia (Nihil)',
                                        default => isset($detailFields['shapefile ekosistem zip']) ? 'Tersedia' : '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? $detailFields['kawasan ekosistem'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $availability }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['shapefile ekosistem zip']))<a href="{{ asset('storage/' . $detailFields['shapefile ekosistem zip']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat shapefile</a>@else-@endif</td>
                                    <td class="py-4 px-4 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.05 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'perencanaan_konservasi' && $selectedSub === 'A.06')
                    <table class="min-w-[1550px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Kawasan Konservasi</th>
                                <th class="py-3 px-4">Ketersediaan Data Zonasi/Blok</th>
                                <th class="py-3 px-4">Nomor SK</th>
                                <th class="py-3 px-4">Tanggal SK</th>
                                <th class="py-3 px-4">File SK (PDF)</th>
                                <th class="py-3 px-4">Shapefile Area (ZIP)</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }

                                        if (preg_match('/^Kawasan Zonasi: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['kawasan konservasi'] ??= trim($legacyMatch[1]);
                                        }
                                        if (preg_match('/^\[Zonasi\] No SK: (.*?), Tgl SK: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['nomor sk zonasi'] ??= trim($legacyMatch[1]);
                                            $detailFields['tanggal sk zonasi'] ??= trim($legacyMatch[2]);
                                        }
                                        if (preg_match('/^\[Zonasi\] Status: (.*)$/', $detailLine, $legacyMatch)) {
                                            $detailFields['ketersediaan zonasi'] ??= str_contains(strtolower($legacyMatch[1]), 'belum') ? 'belum' : 'sudah';
                                        }
                                    }

                                    $zoningStatus = match (strtolower($detailFields['ketersediaan zonasi'] ?? '')) {
                                        'sudah' => 'Sudah',
                                        'belum' => 'Tidak/Belum',
                                        default => isset($detailFields['nomor sk zonasi']) ? 'Sudah' : '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4 min-w-56">{{ $detailFields['kawasan konservasi'] ?? $detailFields['kawasan zonasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $zoningStatus }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['nomor sk zonasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['tanggal sk zonasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['file sk zonasi']))<a href="{{ asset('storage/' . $detailFields['file sk zonasi']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat file SK</a>@else-@endif</td>
                                    <td class="py-4 px-4">@if(!empty($detailFields['shapefile zonasi zip']))<a href="{{ asset('storage/' . $detailFields['shapefile zonasi zip']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat shapefile</a>@else-@endif</td>
                                    <td class="py-4 px-4 min-w-48">{{ $detailFields['keterangan'] ?? $detailFields['keterangan tambahan'] ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data A.06 untuk filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($selectedBidang === 'konservasi_kawasan' && $selectedSub === 'B.02')
                    <table class="min-w-[2800px] w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase text-slate-500">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Kawasan Konservasi</th>
                                <th class="py-3 px-4">Ada Pemberian Akses</th>
                                <th class="py-3 px-4">Jenis Pengelolaan Bersama</th>
                                <th class="py-3 px-4">Jenis Akses</th>
                                <th class="py-3 px-4">Jenis yang Dimanfaatkan</th>
                                <th class="py-3 px-4">Nama Kelompok</th>
                                <th class="py-3 px-4">Masyarakat Hukum Adat</th>
                                <th class="py-3 px-4">Laki-laki</th>
                                <th class="py-3 px-4">Perempuan</th>
                                <th class="py-3 px-4">Kabupaten/Kota</th>
                                <th class="py-3 px-4">Kecamatan</th>
                                <th class="py-3 px-4">Kelurahan/Desa</th>
                                <th class="py-3 px-4">Nomor Surat Dirjen KSDAE</th>
                                <th class="py-3 px-4">Nomor Perjanjian Kerjasama</th>
                                <th class="py-3 px-4">Tanggal Mulai KS</th>
                                <th class="py-3 px-4">Tanggal Berakhir PKS</th>
                                <th class="py-3 px-4">Luas Area Pemanfaatan (Ha)</th>
                                <th class="py-3 px-4">Zona/Blok</th>
                                <th class="py-3 px-4">Dokumen Kerjasama (PDF)</th>
                                <th class="py-3 px-4">Shapefile Area (ZIP)</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            @forelse($data as $index => $item)
                                @php
                                    $detailFields = [];
                                    foreach (explode(' | ', (string) $item->keterangan) as $detailLine) {
                                        if (str_contains($detailLine, ': ')) {
                                            [$detailLabel, $detailValue] = explode(': ', $detailLine, 2);
                                            $detailFields[strtolower(trim($detailLabel))] = trim($detailValue);
                                        }
                                    }

                                    $hasAccess = match (strtolower($detailFields['ada pemberian akses pemanfaatan tradisional dan kemitraan konservasi'] ?? '')) {
                                        'ya' => 'Ya, ada',
                                        'tidak' => 'Tidak ada (Nihil)',
                                        default => '-',
                                    };
                                    $managementType = match ($detailFields['jenis pengelolaan bersama masyarakat'] ?? '') {
                                        'akses_tradisional' => 'Pemberian Akses Pemanfaatan Tradisional',
                                        'kemitraan_konservasi' => 'Kemitraan Konservasi',
                                        default => '-',
                                    };
                                    $accessType = match ($detailFields['jenis akses'] ?? '') {
                                        'pemungutan_hhbk' => 'Pemungutan HHBK',
                                        'budidaya_tradisional' => 'Budidaya Tradisional',
                                        'perburuan_tradisional' => 'Perburuan Tradisional',
                                        'pemanfaatan_sda_perairan' => 'Pemanfaatan SDA Perairan',
                                        'lainnya' => 'Lainnya',
                                        default => $detailFields['jenis akses'] ?? '-',
                                    };
                                @endphp
                                <tr class="align-top hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-400">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $item->tahun }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['kawasan konservasi'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $hasAccess }}</td>
                                    <td class="py-4 px-4">{{ $managementType }}</td>
                                    <td class="py-4 px-4">{{ $accessType }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['jenis yang dimanfaatkan'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['nama kelompok'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['masyarakat hukum adat'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['laki-laki'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['perempuan'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['kabupaten/kota'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['kecamatan'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['kelurahan/desa'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['nomor surat dirjen ksdae'] ?? '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['nomor perjanjian kerjasama'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['tanggal mulai ks'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $detailFields['tanggal berakhir pks'] ?? '-' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ isset($detailFields['luas area pemanfaatan']) ? $detailFields['luas area pemanfaatan'] . ' Ha' : '-' }}</td>
                                    <td class="py-4 px-4">{{ $detailFields['zona/blok'] ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        @if(!empty($detailFields['dokumen kerjasama b02']))
                                            <a href="{{ asset('storage/' . $detailFields['dokumen kerjasama b02']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat dokumen</a>
                                        @else - @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if(!empty($detailFields['shapefile kerjasama b02']))
                                            <a href="{{ asset('storage/' . $detailFields['shapefile kerjasama b02']) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">Lihat shapefile</a>
                                        @else - @endif
                                    </td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" title="Lihat Detail Lengkap"><i class="fa-solid fa-eye text-xs"></i></button>
                                            <a href="{{ route('konservasi.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" title="Edit Data"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                            <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" title="Hapus Data"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="23" class="py-12 px-5 text-center text-xs font-bold text-slate-500">Belum ada data B.02 untuk filter ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="py-4 px-5">No</th>
                            <th class="py-4 px-5">Bidang Utama</th>
                            <th class="py-4 px-5">Sub-Bidang & Detail Data</th>
                            <th class="py-4 px-5">Periode Waktu</th>
                            <th class="py-4 px-5">Jumlah / Vol</th>
                            <th class="py-4 px-5">Koordinat GIS</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-5 font-bold text-slate-400">
                                    {{ method_exists($data, 'firstItem') ? $data->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-forest-700 border border-emerald-200/80">
                                        {{ $item->subBidang->bidang->nama_bidang ?? 'Konservasi' }}
                                    </span>
                                </td>
                                
                                <!-- KOLOM SUB-BIDANG & DETAIL LENGKAP -->
                                <td class="py-4 px-5 max-w-md">
                                    <p class="font-bold text-slate-800 text-sm">
                                        {{ $item->subBidang->kode_sub ?? '' }}. {{ $item->subBidang->nama_sub_bidang ?? '-' }}
                                    </p>

                                    <!-- DETAIL LENGKAP KETERANGAN & PARAMETER SK -->
                                    @if($item->keterangan)
                                        <div class="mt-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200/70 text-[11px] text-slate-600 leading-relaxed font-sans whitespace-pre-line">
                                            @php
                                                // Memisah separator '|' menjadi baris-baris terpisah agar mudah dibaca
                                                $formattedKet = str_replace(' | ', "\n• ", $item->keterangan);
                                                if (strpos($formattedKet, '• ') !== 0) {
                                                    $formattedKet = '• ' . $formattedKet;
                                                }
                                            @endphp
                                            {{ $formattedKet }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px] mt-1 block">Tidak ada rincian data</span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 whitespace-nowrap text-slate-600 font-semibold">
                                    @php
                                        $months = [
                                            '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April',
                                            '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus',
                                            '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',
                                            '1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April',
                                            '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September'
                                        ];
                                        $namaBulan = $months[$item->bulan] ?? $item->bulan;
                                    @endphp
                                    {{ $namaBulan }} {{ $item->tahun }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold rounded-lg border border-slate-200">
                                        {{ $item->jumlah ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-[11px] font-mono text-slate-500 whitespace-nowrap">
                                    @if($item->latitude && $item->longitude)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold bg-emerald-50/80 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                            {{ $item->latitude }}, {{ $item->longitude }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <!-- Tombol Pop-up Detail Modal -->
                                        <button type="button" 
                                                @click="openModal = true; modalTitle = '{{ addslashes($item->subBidang->nama_sub_bidang ?? 'Detail Data') }}'; modalContent = '{{ addslashes($item->keterangan ?? 'Tidak ada rincian') }}'"
                                                class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition border border-blue-200/80 shadow-xs" 
                                                title="Lihat Detail Lengkap">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>

                                        <a href="{{ route('konservasi.edit', $item->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition border border-amber-200/80 shadow-xs" 
                                           title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form action="{{ route('konservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-200/80 shadow-xs" 
                                                    title="Hapus Data">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-5 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto text-lg">
                                            <i class="fa-solid fa-folder-open"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-600">Data Tidak Ditemukan</p>
                                        <p class="text-[11px] text-slate-400">Tidak ada entri data yang cocok dengan pilihan bidang atau pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>

            @if(method_exists($data, 'links'))
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $data->links() }}
            </div>
            @endif
        </div>
        @endif

    </main>

    <!-- MODAL POP-UP DETAIL -->
    <div x-show="openModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.outside="openModal = false">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-forest-700"></i>
                    <span x-text="modalTitle"></span>
                </h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 max-h-80 overflow-y-auto">
                <dl x-show="modalDetails.length" class="divide-y divide-slate-200">
                    <template x-for="(detail, index) in modalDetails" :key="index">
                        <div class="grid grid-cols-1 sm:grid-cols-[minmax(9rem,0.8fr)_2fr] gap-1 sm:gap-4 px-4 py-3">
                            <dt class="font-bold text-slate-500" x-text="detail.label"></dt>
                            <dd class="leading-relaxed whitespace-pre-wrap break-words" x-text="detail.value"></dd>
                        </div>
                    </template>
                </dl>
                <p x-show="modalDetails.length === 0" class="p-4 text-slate-500 italic">
                    Belum ada rincian untuk data ini.
                </p>
            </div>

            <div class="flex justify-end pt-2">
                <button @click="openModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT DYNAMIC SUB-BIDANG DROPDOWN -->
    <script>
        const masterData = @json($masterBidang ?? []);

        document.getElementById('bidangSelect').addEventListener('change', function() {
            const selectedBidang = this.value;
            const subSelect = document.getElementById('subBidangSelect');
            
            subSelect.innerHTML = '<option value="">-- Pilih Sub-Bidang --</option>';

            if (selectedBidang && masterData[selectedBidang]) {
                subSelect.disabled = false;
                const subs = masterData[selectedBidang].subs;
                
                for (const [kode, nama] of Object.entries(subs)) {
                    const opt = document.createElement('option');
                    opt.value = kode;
                    opt.textContent = nama;
                    subSelect.appendChild(opt);
                }
            } else {
                subSelect.disabled = true;
            }
        });
    </script>

</body>
</html>