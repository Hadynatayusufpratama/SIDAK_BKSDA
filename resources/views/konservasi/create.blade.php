<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - SIDAK BKSDA Sulawesi Tengah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Form Dinamis Conditional -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative">

    @php
        // Array Daftar 18 Kawasan Konservasi BKSDA Sulteng
        $kawasanList = [
            'TWA Wera (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Tanjung Santigi (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Tanjung Api (Satker: Balai KSDA Sulawesi Tengah)',
            'TWA Pulau Tokobae (Satker: Balai KSDA Sulawesi Tengah)',
            'TWA Pulau Pasoso (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Pulau Dolangan (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Pinjan Tanjung Matop (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Pati-Pati (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Pangi Binangga (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Pamona (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Morowali (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Lombuyan (Satker: Balai KSDA Sulawesi Tengah)',
            'TB Landusa Tomata (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Gunung Tinombala (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Gunung Sojol (Satker: Balai KSDA Sulawesi Tengah)',
            'CA Gunung Dako (Satker: Balai KSDA Sulawesi Tengah)',
            'TWA Bancea (Satker: Balai KSDA Sulawesi Tengah)',
            'SM Bakiriang (Satker: Balai KSDA Sulawesi Tengah)',
        ];
    @endphp

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
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Tambah Data</a>
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
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HEADER BANNER & TOMBOL KEMBALI -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    FORMULIR INPUT DATA KONSERVASI
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sistem pencatatan terpadu capaian kinerja dan inventarisasi Balai KSDA Sulawesi Tengah
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('konservasi.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-200/80 hover:bg-slate-300 transition shadow-xs">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Rekapitulasi
                </a>
            </div>
        </div>

        <!-- Flash Alert Success -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm">
                <p class="text-xs font-bold">Data belum tersimpan. Periksa kembali isian berikut:</p>
                <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- CARD CONTAINER FORM -->
        <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm"
             x-data="{ 
                 selectedSubBidangKode: '', 
                 adaPerubahan: 'tidak', 
                 skProvinsiTersedia: 'tidak',
                 skPenetapanTersedia: 'tidak',
                 ketersediaanRpjp: 'ya',
                 adaKegiatanMonitoring: 'ya',
                 ketersediaanEvaluasi: 'ya',
                 ketersediaanEkosistem: 'ya',
                 ketersediaanZonasi: 'sudah',
                 adaKegiatanB01: 'ya',
                 adaAksesB02: 'ya',
                 palBaik: 0,
                 palRusak: 0,
                 palHilang: 0,
                 get totalPal() {
                     return (Number(this.palBaik) || 0) + (Number(this.palRusak) || 0) + (Number(this.palHilang) || 0);
                 }
             }">
            <form action="{{ route('konservasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION: KLASIFIKASI DATA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200/80">
                        <span class="w-7 h-7 rounded-lg bg-forest-700 text-amber-400 flex items-center justify-center text-xs font-black shadow-xs">1</span>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Klasifikasi Data</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dropdown Bidang Utama -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Bidang Utama <span class="text-rose-500">*</span>
                            </label>
                            <select id="bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium transition shadow-xs">
                                <option value="">-- Pilih Bidang Utama --</option>
                                @foreach ($bidang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Sub-Bidang -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Sub-Bidang Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="sub_bidang_id" id="sub_bidang_select" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium transition shadow-xs disabled:opacity-50 disabled:cursor-not-allowed" disabled required>
                                <option value="">-- Pilih Bidang Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 1: A.01 / Kawasan Konservasi                      -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.01'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. PERUBAHAN DATA KAWASAN -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Perubahan data kawasan?</label>
                                <p class="text-[11px] text-slate-500">Apakah ada perubahan data penetapan kawasan?</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_perubahan" value="ya" x-model="adaPerubahan" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada perubahan</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_perubahan" value="tidak" x-model="adaPerubahan" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada</span>
                                </label>
                            </div>
                        </div>

                        <!-- AKAN MUNCUL JIKA "Ya, ada perubahan" DIKLIK -->
                        <div x-show="adaPerubahan === 'ya'" x-transition class="mt-4 p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                SK PENUNJUKAN PARSIAL
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK:</label>
                                    <input type="text" name="sk_parsial_nomor" placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal SK:</label>
                                    <input type="date" name="sk_parsial_tanggal" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Luas:</label>
                                    <div class="flex">
                                        <input type="number" step="0.01" name="sk_parsial_luas" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">Ha</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">File SK (pdf):</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Format file pdf dan maksimal 1 file berukuran 2 Mb</p>
                                    <input type="file" name="sk_parsial_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. SK PENUNJUKAN PROVINSI -->
                    <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                                SK PENUNJUKAN PROVINSI
                            </h4>
                            <div class="flex items-center gap-6">
                                <span class="text-xs font-bold text-slate-700">Data Tersedia?</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sk_provinsi_tersedia" value="ya" x-model="skProvinsiTersedia" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sk_provinsi_tersedia" value="tidak" x-model="skProvinsiTersedia" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <!-- AKAN MUNCUL JIKA "Ya, tersedia" DIKLIK -->
                        <div x-show="skProvinsiTersedia === 'ya'" x-transition class="space-y-4 pt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK:</label>
                                    <input type="text" name="sk_provinsi_nomor" placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal SK:</label>
                                    <input type="date" name="sk_provinsi_tanggal" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Luas:</label>
                                    <div class="flex">
                                        <input type="number" step="0.01" name="sk_provinsi_luas" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">Ha</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">File SK (pdf):</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Format file pdf dan maksimal 1 file berukuran 2 Mb</p>
                                    <input type="file" name="sk_provinsi_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. SK PENETAPAN -->
                    <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                                SK PENETAPAN
                            </h4>
                            <div class="flex items-center gap-6">
                                <span class="text-xs font-bold text-slate-700">Data Tersedia?</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sk_penetapan_tersedia" value="ya" x-model="skPenetapanTersedia" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sk_penetapan_tersedia" value="tidak" x-model="skPenetapanTersedia" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <!-- AKAN MUNCUL JIKA "Ya, tersedia" DIKLIK -->
                        <div x-show="skPenetapanTersedia === 'ya'" x-transition class="space-y-4 pt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK:</label>
                                    <input type="text" name="sk_penetapan_nomor" placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal SK:</label>
                                    <input type="date" name="sk_penetapan_tanggal" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Luas:</label>
                                    <div class="flex">
                                        <input type="number" step="0.01" name="sk_penetapan_luas" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">Ha</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">File SK (pdf):</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Format file pdf dan maksimal 1 file berukuran 2 Mb</p>
                                    <input type="file" name="sk_penetapan_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. BATAS GEOGRAFIS KAWASAN -->
                    <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-2">
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                            BATAS GEOGRAFIS KAWASAN
                        </h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Shapefile Area (zip):</label>
                            <p class="text-[11px] text-slate-500 mb-2">
                                ESRI Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip
                            </p>
                            <input type="file" name="shapefile_zip" accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 2: A.02 / Perencanaan Pengelolaan Kawasan        -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.02'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_rpjp" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_rpjp" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. KETERSEDIAAN DATA (RADIO BUTTON) -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ketersediaan data?</label>
                                <p class="text-[11px] text-slate-500">Apakah data tersedia atau tidak/nihil</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_rpjp" value="ya" x-model="ketersediaanRpjp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_rpjp" value="tidak" x-model="ketersediaanRpjp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak tersedia (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <!-- OUTPUT FORM: SK PENETAPAN DOKUMEN RPJP (MUNCUL SAAT "Tersedia" DIKLIK) -->
                        <div x-show="ketersediaanRpjp === 'ya'" x-transition class="mt-4 p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                SK PENETAPAN DOKUMEN RPJP
                            </h4>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Nomor Surat Keputusan: <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="sk_rpjp_nomor" placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Tanggal Pengesahan: <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="sk_rpjp_tanggal_pengesahan" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Periode Berakhir RPJ Panjang: <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="sk_rpjp_periode_berakhir" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">File SK Penetapan Dokumen RPJP (pdf):</label>
                                <p class="text-[10px] text-slate-400 mb-1">Format file pdf dan maksimal 1 file berukuran 20 Mb</p>
                                <input type="file" name="sk_rpjp_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 3: A.03 / Monitoring Batas Kawasan Konservasi      -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.03'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_monitoring" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_monitoring" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. ADA KEGIATAN MONITORING BATAS -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Kegiatan Monitoring Batas Kawasan Konservasi?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak, jika tidak ada kegiatan Monitoring Batas Kawasan Konservasi</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_kegiatan_monitoring" value="ya" x-model="adaKegiatanMonitoring" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, Ada/Tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_kegiatan_monitoring" value="tidak" x-model="adaKegiatanMonitoring" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <!-- KONTEN MUNCUL JIKA "Ya, Ada/Tersedia" DIKLIK -->
                        <div x-show="adaKegiatanMonitoring === 'ya'" x-transition class="mt-4 space-y-6">
                            
                            <!-- CARD: BERITA ACARA TATA BATAS (BATB) -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    BERITA ACARA TATA BATAS (BATB)
                                </h4>

                                <!-- Jenis Kegiatan -->
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <label class="text-xs font-bold text-slate-700 shrink-0">
                                        Jenis Kegiatan: <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-6">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="jenis_kegiatan" value="Penataan" class="text-forest-600 focus:ring-forest-600">
                                            <span class="text-xs font-medium text-slate-700">Penataan</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="jenis_kegiatan" value="Rekonstruksi" class="text-forest-600 focus:ring-forest-600">
                                            <span class="text-xs font-medium text-slate-700">Rekonstruksi</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="jenis_kegiatan" value="Pemeliharaan" class="text-forest-600 focus:ring-forest-600">
                                            <span class="text-xs font-medium text-slate-700">Pemeliharaan</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nomor BATB & Tanggal BATB -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Nomor BATB: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="nomor_batb" placeholder="Nomor BATB" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Tanggal BATB: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal_batb" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                </div>

                                <!-- Kondisi Pal Batas -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Kondisi Pal Batas</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Baik: <span class="text-rose-500">*</span></label>
                                            <input type="number" name="pal_baik" x-model="palBaik" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Rusak: <span class="text-rose-500">*</span></label>
                                            <input type="number" name="pal_rusak" x-model="palRusak" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Hilang: <span class="text-rose-500">*</span></label>
                                            <input type="number" name="pal_hilang" x-model="palHilang" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Total:</label>
                                            <input type="number" name="pal_total" :value="totalPal" readonly class="w-full p-2.5 bg-sky-50 border border-sky-400 rounded-xl text-xs font-bold text-slate-800 focus:outline-none cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>

                                <!-- Panjang Pal Batas & Dokumen BATB -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Panjang Pal Batas (Km): <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <input type="number" step="0.01" name="panjang_pal_km" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                            <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">Km</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Dokumen BATB (pdf):</label>
                                        <input type="file" name="dokumen_batb" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                    </div>
                                </div>
                            </div>

                            <!-- CARD: LOKASI GEOGRAFIS HASIL KEGIATAN -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-2">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    LOKASI GEOGRAFIS HASIL KEGIATAN
                                </h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Point/Polyline/LineString (shapefile):</label>
                                    <p class="text-[11px] text-slate-500 mb-2">
                                        ESRI Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip
                                    </p>
                                    <input type="file" name="shapefile_monitoring_zip" accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 4: A.04 / Evaluasi Kesesuaian Fungsi             -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.04'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_evaluasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_evaluasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. KETERSEDIAAN DATA? -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ketersediaan data?</label>
                                <p class="text-[11px] text-slate-500">Apakah data tersedia atau tidak/nihil</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_evaluasi" value="ya" x-model="ketersediaanEvaluasi" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_evaluasi" value="tidak" x-model="ketersediaanEvaluasi" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak tersedia (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <!-- INPUT TAMPIL JIKA "Tersedia" DIKLIK -->
                        <div x-show="ketersediaanEvaluasi === 'ya'" x-transition class="mt-4 space-y-4">
                            
                            <!-- 4. TANGGAL PELAKSANAAN -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Tanggal Pelaksanaan: <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="tanggal_pelaksanaan_evaluasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            </div>

                            <!-- 5. REKOMENDASI -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Rekomendasi: <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="rekomendasi_evaluasi" rows="3" placeholder="Rekomendasi hasil pelaksanaan evaluasi kesesuaian fungsi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium shadow-xs"></textarea>
                            </div>

                            <!-- 6. TINDAK LANJUT -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Tindak Lanjut: <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="tindak_lanjut_evaluasi" rows="3" placeholder="Tindak lanjut setelah dilakukan evaluasi kesesuaian fungsi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white text-xs font-medium shadow-xs"></textarea>
                            </div>

                            <!-- 7. FILE DOKUMEN (PDF) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">File Dokumen (pdf):</label>
                                <input type="file" name="file_dokumen_evaluasi" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-slate-50 rounded-xl">
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 5: A.05 / Ekosistem Kawasan                       -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.05'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_ekosistem" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_ekosistem" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. KETERSEDIAAN DATA? -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ketersediaan data?</label>
                                <p class="text-[11px] text-slate-500">Apakah data tersedia atau tidak/nihil</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_ekosistem" value="ya" x-model="ketersediaanEkosistem" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_ekosistem" value="tidak" x-model="ketersediaanEkosistem" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak tersedia (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <!-- 4. DATA GEOGRAFIS EKOSISTEM KAWASAN (MUNCUL JIKA TERSEDIA) -->
                        <div x-show="ketersediaanEkosistem === 'ya'" x-transition class="mt-4 p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-2">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                DATA GEOGRAFIS EKOSISTEM KAWASAN
                            </h4>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Shapefile Area (zip):</label>
                                <p class="text-[11px] text-slate-500 mb-2">
                                    ESRI Shapefile wajib memiliki atribut/field REG_KK, T_EKOS_1, T_EKOS_2, T_EKOS_3, T_EKOS_4 dan LUAS. Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip
                                </p>
                                <input type="file" name="shapefile_ekosistem_zip" accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 6: A.06 / Penataan Zonasi/Blok                    -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'A.06'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_zonasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_zonasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. KETERSEDIAAN DATA ZONASI/BLOK? -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ketersediaan data Zonasi/Blok?</label>
                                <p class="text-[11px] text-slate-500">Apakah kawasan sudah dilakukan penataan zonasi/blok</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_zonasi" value="sudah" x-model="ketersediaanZonasi" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Sudah</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ketersediaan_zonasi" value="belum" x-model="ketersediaanZonasi" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak/Belum</span>
                                </label>
                            </div>
                        </div>

                        <!-- FORMS JIKA SUDAH -->
                        <div x-show="ketersediaanZonasi === 'sudah'" x-transition class="mt-4 space-y-6">
                            
                            <!-- NOMOR, TANGGAL & FILE SK -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Nomor SK: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="nomor_sk_zonasi" placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Tanggal SK: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal_sk_zonasi" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">File SK (pdf):</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Format file pdf dan maksimal 1 file berukuran 2 Mb</p>
                                    <input type="file" name="file_sk_zonasi" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>

                            <!-- DATA GEOGRAFIS ZONASI/BLOK -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-2">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    DATA GEOGRAFIS ZONASI/BLOK
                                </h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Shapefile Area (zip): <span class="text-rose-500">*</span>
                                    </label>
                                    <p class="text-[11px] text-slate-500 mb-2">
                                        ESRI Shapefile wajib memiliki atribut/field REG_KK, ZONA_BLOK dan LUAS. Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip
                                    </p>
                                    <input type="file" name="shapefile_zonasi_zip" accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG B.01: Kelompok Binaan                             -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'B.01'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN & PERIODE SEMESTER -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Tahun: <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_b01" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Periode Semester: <span class="text-rose-500">*</span>
                            </label>
                            <select name="periode_semester" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="Semester I">Semester I</option>
                                <option value="Semester II">Semester II</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. KAWASAN KONSERVASI (18 KAWASAN PERULANGAN) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_b01" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">-- Pilih Kawasan Konservasi --</option>
                            @foreach ($kawasanList as $kawasan)
                                <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. ADA KEGIATAN PEMBINAAN KELOMPOK -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada kegiatan pembinaan kelompok dalam rangka pemberdayaan masyarakat di daerah penyangga kawasan?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak ada, jika tidak ada kegiatan di kawasan tersebut</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_kegiatan_b01" value="ya" x-model="adaKegiatanB01" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_kegiatan_b01" value="tidak" x-model="adaKegiatanB01" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <!-- CONTAINER DETAIL FORM B.01 -->
                        <div x-show="adaKegiatanB01 === 'ya'" x-transition class="mt-4 space-y-6">

                            <!-- INFORMASI KELOMPOK BINAAN -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    INFORMASI KELOMPOK BINAAN
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Nama Kelompok: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="nama_kelompok" placeholder="Nama kelompok binaan" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Jumlah Laki-laki: <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <input type="number" name="jumlah_laki" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                            <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">org</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Jumlah Perempuan: <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <input type="number" name="jumlah_perempuan" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                            <span class="bg-slate-100 border border-l-0 border-slate-300 px-3 py-2 rounded-r-xl text-xs font-semibold text-slate-500 flex items-center">org</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Lokasi Kelompok Binaan</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <!-- PROVINSI -->
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Provinsi: <span class="text-rose-500">*</span></label>
                                            <select id="provinsi_select" name="provinsi" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                                <option value="">Pilih provinsi</option>
                                            </select>
                                        </div>
                                        <!-- KABUPATEN -->
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kabupaten/Kota: <span class="text-rose-500">*</span></label>
                                            <select id="kabupaten_select" name="kabupaten" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed" disabled>
                                                <option value="">Pilih kabupaten/kota</option>
                                            </select>
                                        </div>
                                        <!-- KECAMATAN -->
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kecamatan: <span class="text-rose-500">*</span></label>
                                            <select id="kecamatan_select" name="kecamatan" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed" disabled>
                                                <option value="">Pilih kecamatan</option>
                                            </select>
                                        </div>
                                        <!-- KELURAHAN / DESA -->
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kelurahan/Desa: <span class="text-rose-500">*</span></label>
                                            <select id="desa_select" name="desa" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed" disabled>
                                                <option value="">Pilih kelurahan/desa</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- JENIS PEMBINAAN USAHA -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    JENIS PEMBINAAN USAHA
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Pemungutan HHBK -->
                                    <div x-data="{ hhbkNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Pemungutan HHBK:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="hhbk_nihil" x-model="hhbkNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_hhbk" :disabled="hhbkNihil" placeholder="Jenis usaha Pemungutan HHBK" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Pertanian -->
                                    <div x-data="{ pertanianNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Pertanian:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="pertanian_nihil" x-model="pertanianNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_pertanian" :disabled="pertanianNihil" placeholder="Jenis usaha Pertanian" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Perkebunan -->
                                    <div x-data="{ perkebunanNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Perkebunan:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="perkebunan_nihil" x-model="perkebunanNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_perkebunan" :disabled="perkebunanNihil" placeholder="Jenis usaha Perkebunan" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Peternakan -->
                                    <div x-data="{ peternakanNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Peternakan:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="peternakan_nihil" x-model="peternakanNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_peternakan" :disabled="peternakanNihil" placeholder="Jenis usaha Peternakan" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Perikanan -->
                                    <div x-data="{ perikananNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Perikanan:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="perikanan_nihil" x-model="perikananNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_perikanan" :disabled="perikananNihil" placeholder="Jenis usaha Perikanan" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Jasa Wisata -->
                                    <div x-data="{ wisataNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Jasa Wisata:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="wisata_nihil" x-model="wisataNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_wisata" :disabled="wisataNihil" placeholder="Jenis usaha Jasa Wisata" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Usaha Penghasil Produk -->
                                    <div x-data="{ produkNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Usaha Penghasil Produk:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="produk_nihil" x-model="produkNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_produk" :disabled="produkNihil" placeholder="Jenis usaha Penghasil Produk" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Pembibitan -->
                                    <div x-data="{ pembibitanNihil: false }">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Pembibitan:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="pembibitan_nihil" x-model="pembibitanNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_pembibitan" :disabled="pembibitanNihil" placeholder="Jenis usaha Pembibitan" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Jenis Usaha Lainnya -->
                                    <div x-data="{ lainnyaNihil: false }" class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Usaha Lainnya:</label>
                                        <div class="flex">
                                            <label class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-medium text-slate-700 flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="lainnya_nihil" x-model="lainnyaNihil" class="rounded text-forest-600 focus:ring-forest-600">
                                                <span>Nihil</span>
                                            </label>
                                            <input type="text" name="jenis_lainnya" :disabled="lainnyaNihil" placeholder="Jenis usaha Lainnya" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BENTUK BANTUAN USAHA -->
                            <div class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    BENTUK BANTUAN USAHA
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Jenis Bantuan: <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="jenis_bantuan" placeholder="Uang/Barang/dsb" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">
                                            Jumlah Bantuan (Rp): <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="flex">
                                            <span class="bg-slate-100 border border-r-0 border-slate-300 px-3 py-2 rounded-l-xl text-xs font-semibold text-slate-500 flex items-center">Rp</span>
                                            <input type="number" name="jumlah_bantuan" class="w-full p-2.5 bg-white border border-slate-300 rounded-r-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Sumber Pendanaan:</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <label class="inline-flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <input type="radio" name="sumber_dana" value="APBN KLHK Lainnya" class="text-forest-600 focus:ring-forest-600">
                                            <span>APBN KLHK Lainnya</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <input type="radio" name="sumber_dana" value="APBN KSDAE" class="text-forest-600 focus:ring-forest-600">
                                            <span>APBN KSDAE</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <input type="radio" name="sumber_dana" value="Pendanaan Pihak Lainnya" class="text-forest-600 focus:ring-forest-600">
                                            <span>Pendanaan Pihak Lainnya</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <input type="radio" name="sumber_dana" value="Pendanaan Gabungan" class="text-forest-600 focus:ring-forest-600">
                                            <span>Pendanaan Gabungan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG B.02: Akses Tradisional & Kemitraan                -->
                <!-- ========================================================================= -->
                <div x-show="selectedSubBidangKode === 'B.02'" x-transition class="space-y-6 pt-2">
                    <fieldset :disabled="selectedSubBidangKode !== 'B.02'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_b02" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                            <select name="kawasan_nama_b02" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">-- Pilih Kawasan Konservasi --</option>
                                @foreach ($kawasanList as $kawasan)
                                    <option value="{{ $kawasan }}">{{ $kawasan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-slate-50/80 border border-slate-200 rounded-xl">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">Ada Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi di kawasan tersebut?</label>
                            <p class="text-[11px] text-slate-500 mt-1">Pilih tidak jika tidak ada/nihil.</p>
                        </div>
                        <div class="flex items-center gap-6 shrink-0">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                <input type="radio" name="ada_akses_b02" value="ya" x-model="adaAksesB02" class="text-forest-600 focus:ring-forest-600">
                                Ya, ada
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                <input type="radio" name="ada_akses_b02" value="tidak" x-model="adaAksesB02" class="text-forest-600 focus:ring-forest-600">
                                Tidak ada (Nihil)
                            </label>
                        </div>
                    </div>

                    <div x-show="adaAksesB02 === 'ya'" x-transition class="space-y-6">
                        <fieldset :disabled="adaAksesB02 !== 'ya'" class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Jenis Pengelolaan Bersama Masyarakat: <span class="text-rose-500">*</span></label>
                                <div class="flex flex-wrap gap-x-6 gap-y-3">
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                        <input type="radio" name="jenis_pengelolaan_b02" value="akses_tradisional" required class="text-forest-600 focus:ring-forest-600">
                                        Pemberian Akses Pemanfaatan Tradisional
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                        <input type="radio" name="jenis_pengelolaan_b02" value="kemitraan_konservasi" class="text-forest-600 focus:ring-forest-600">
                                        Kemitraan Konservasi
                                    </label>
                                </div>
                            </div>

                            <fieldset class="p-5 bg-white border border-slate-200 rounded-xl space-y-4">
                                <legend class="px-2 text-xs font-extrabold text-slate-800">PEMBERIAN AKSES PEMANFAATAN TRADISIONAL</legend>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Jenis Akses: <span class="text-rose-500">*</span></label>
                                        <div class="space-y-2">
                                            @foreach([
                                                'pemungutan_hhbk' => 'Pemungutan HHBK',
                                                'budidaya_tradisional' => 'Budidaya Tradisional',
                                                'perburuan_tradisional' => 'Perburuan Tradisional',
                                                'pemanfaatan_sda_perairan' => 'Pemanfaatan SDA Perairan',
                                                'lainnya' => 'Lainnya',
                                            ] as $jenisAksesValue => $jenisAksesLabel)
                                                <label class="flex items-center gap-2 text-xs text-slate-700">
                                                    <input type="radio" name="jenis_akses_b02" value="{{ $jenisAksesValue }}" required class="text-forest-600 focus:ring-forest-600">
                                                    {{ $jenisAksesLabel }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis yang dimanfaatkan: <span class="text-rose-500">*</span></label>
                                        <textarea name="jenis_dimanfaatkan_b02" rows="5" required placeholder="Masukan jenis yang dimanfaatkan..." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-xl space-y-4">
                                <legend class="px-2 text-xs font-extrabold text-slate-800">INFORMASI KELOMPOK MASYARAKAT</legend>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kelompok: <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nama_kelompok_b02" required placeholder="Nama kelompok" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Masyarakat Hukum Adat?: <span class="text-rose-500">*</span></label>
                                        <div class="flex gap-6 py-2">
                                            <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="masyarakat_hukum_adat_b02" value="ya" required class="text-forest-600"> Ya</label>
                                            <label class="inline-flex items-center gap-2 text-xs text-slate-700"><input type="radio" name="masyarakat_hukum_adat_b02" value="tidak" class="text-forest-600"> Tidak/Bukan</label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Laki-laki: <span class="text-rose-500">*</span></label>
                                        <div class="flex"><input type="number" name="jumlah_laki_b02" min="0" required class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs"><span class="px-3 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-xl text-xs text-slate-500">Orang</span></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Perempuan: <span class="text-rose-500">*</span></label>
                                        <div class="flex"><input type="number" name="jumlah_perempuan_b02" min="0" required class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs"><span class="px-3 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-xl text-xs text-slate-500">Orang</span></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten/Kota: <span class="text-rose-500">*</span></label>
                                        <select id="kabupaten_b02_select" name="kabupaten_b02" required disabled class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs disabled:bg-slate-100"><option value="">Pilih kabupaten/kota</option></select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan: <span class="text-rose-500">*</span></label>
                                        <select id="kecamatan_b02_select" name="kecamatan_b02" required disabled class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs disabled:bg-slate-100"><option value="">Pilih kecamatan</option></select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Kelurahan/Desa: <span class="text-rose-500">*</span></label>
                                        <select id="desa_b02_select" name="desa_b02" required disabled class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs disabled:bg-slate-100"><option value="">Pilih kelurahan/desa</option></select>
                                    </div>
                                </div>
                                <input type="hidden" id="provinsi_b02" name="provinsi_b02" value="Sulawesi Tengah">
                            </fieldset>

                            <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-xl space-y-4">
                                <legend class="px-2 text-xs font-extrabold text-slate-800">INFORMASI PERJANJIAN KERJASAMA</legend>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Surat Dirjen KSDAE: <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nomor_surat_dirjen_b02" required class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Perjanjian Kerjasama: <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nomor_pks_b02" required placeholder="Nomor surat" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai KS: <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal_mulai_ks_b02" required class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Berakhir PKS: <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal_akhir_pks_b02" required class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Luas Area Pemanfaatan: <span class="text-rose-500">*</span></label>
                                        <div class="flex"><input type="number" step="0.01" min="0" name="luas_area_b02" required class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs"><span class="px-3 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-xl text-xs text-slate-500">Ha</span></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="zona_blok_b02" required class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Kerjasama (PDF)</label>
                                    <p class="text-[11px] text-slate-500 mb-2">Format PDF, maksimal 2 MB.</p>
                                    <input type="file" name="dokumen_kerjasama_b02" accept=".pdf,application/pdf" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                                </div>
                            </fieldset>

                            <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-xl space-y-3">
                                <legend class="px-2 text-xs font-extrabold text-slate-800">LOKASI GEOGRAFIS PERJANJIAN KERJASAMA</legend>
                                <p class="text-xs text-slate-600">Data area lokasi pemberian akses dan kemitraan konservasi tersedia dalam bentuk polygon?</p>
                                <label class="block text-xs font-bold text-slate-700">Unggah Polygon Area (Shapefile ZIP)</label>
                                <p class="text-[11px] text-slate-500">Shapefile berisi .shp, .dbf, .prj, .shx, dan .cpg dalam satu file ZIP; maksimal 10 MB.</p>
                                <input type="file" name="shapefile_kerjasama_b02" accept=".zip,application/zip" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs">
                            </fieldset>
                        </fieldset>
                    </div>
                    </fieldset>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-200/80 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Data Konservasi</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <!-- Script AJAX Sub-Bidang & Wilayah Indonesia -->
    <script>
        // --- 1. SCRIPT FOR SUB-BIDANG ---
        const subBidangUrlTemplate = @json(route('konservasi.sub-bidang', ['bidang_id' => '__BIDANG_ID__']), JSON_UNESCAPED_SLASHES);

        document.getElementById('bidang_select').addEventListener('change', function() {
            let bidangId = this.value;
            let subSelect = document.getElementById('sub_bidang_select');
            
            let alpineComponent = Alpine.$data(document.querySelector('[x-data]'));
            if (alpineComponent) {
                alpineComponent.selectedSubBidangKode = '';
            }

            subSelect.innerHTML = '<option value="">Memuat sub-bidang...</option>';
            subSelect.disabled = true;

            if (bidangId) {
                const subBidangUrl = subBidangUrlTemplate.replace('__BIDANG_ID__', encodeURIComponent(bidangId));
                fetch(subBidangUrl)
                    .then(response => response.json())
                    .then(data => {
                        subSelect.innerHTML = '<option value="">-- Pilih Sub-Bidang Kategori --</option>';
                        data.forEach(item => {
                            let kodeSub = item.kode_sub ?? item.kode ?? '';
                            let namaSub = item.nama_sub_bidang ?? item.nama ?? '';
                            subSelect.innerHTML += `<option value="${item.id}" data-kode="${kodeSub}" data-nama="${namaSub}">${kodeSub ? kodeSub + '. ' : ''}${namaSub}</option>`;
                        });
                        subSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching sub-bidang:', error);
                        subSelect.innerHTML = '<option value="">-- Gagal memuat sub-bidang --</option>';
                    });
            } else {
                subSelect.innerHTML = '<option value="">-- Pilih Bidang Terlebih Dahulu --</option>';
                subSelect.disabled = true;
            }
        });

        document.getElementById('sub_bidang_select').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let kodeSub = selectedOption.getAttribute('data-kode') || '';
            
            let alpineComponent = Alpine.$data(document.querySelector('[x-data]'));
            if (alpineComponent) {
                alpineComponent.selectedSubBidangKode = kodeSub.trim().toUpperCase();
            }
        });

        // --- 2. SCRIPT FOR DROPDOWN WILAYAH INDONESIA (BERTINGKAT) ---
        const provSelect = document.getElementById('provinsi_select');
        const kabSelect = document.getElementById('kabupaten_select');
        const kecSelect = document.getElementById('kecamatan_select');
        const desaSelect = document.getElementById('desa_select');

        if (provSelect) {
            // Fetch Daftar Provinsi
            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(res => res.json())
                .then(provinces => {
                    provSelect.innerHTML = '<option value="">Pilih provinsi</option>';
                    provinces.forEach(p => {
                        provSelect.innerHTML += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`;
                    });
                })
                .catch(err => console.error('Error fetching provinces:', err));

            // On Change Provinsi
            provSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const provId = selectedOpt.getAttribute('data-id');

                kabSelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';
                kabSelect.disabled = true;
                kecSelect.innerHTML = '<option value="">Pilih kecamatan</option>';
                kecSelect.disabled = true;
                desaSelect.innerHTML = '<option value="">Pilih kelurahan/desa</option>';
                desaSelect.disabled = true;

                if (provId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
                        .then(res => res.json())
                        .then(regencies => {
                            kabSelect.innerHTML = '<option value="">Pilih kabupaten/kota</option>';
                            regencies.forEach(r => {
                                kabSelect.innerHTML += `<option value="${r.name}" data-id="${r.id}">${r.name}</option>`;
                            });
                            kabSelect.disabled = false;
                        });
                }
            });

            // On Change Kabupaten
            kabSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const regId = selectedOpt.getAttribute('data-id');

                kecSelect.innerHTML = '<option value="">Memuat kecamatan...</option>';
                kecSelect.disabled = true;
                desaSelect.innerHTML = '<option value="">Pilih kelurahan/desa</option>';
                desaSelect.disabled = true;

                if (regId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regId}.json`)
                        .then(res => res.json())
                        .then(districts => {
                            kecSelect.innerHTML = '<option value="">Pilih kecamatan</option>';
                            districts.forEach(d => {
                                kecSelect.innerHTML += `<option value="${d.name}" data-id="${d.id}">${d.name}</option>`;
                            });
                            kecSelect.disabled = false;
                        });
                }
            });

            // On Change Kecamatan
            kecSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const distId = selectedOpt.getAttribute('data-id');

                desaSelect.innerHTML = '<option value="">Memuat kelurahan/desa...</option>';
                desaSelect.disabled = true;

                if (distId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${distId}.json`)
                        .then(res => res.json())
                        .then(villages => {
                            desaSelect.innerHTML = '<option value="">Pilih kelurahan/desa</option>';
                            villages.forEach(v => {
                                desaSelect.innerHTML += `<option value="${v.name}">${v.name}</option>`;
                            });
                            desaSelect.disabled = false;
                        });
                }
            });
        }

        const kabupatenB02 = document.getElementById('kabupaten_b02_select');
        const kecamatanB02 = document.getElementById('kecamatan_b02_select');
        const desaB02 = document.getElementById('desa_b02_select');
        const wilayahApi = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        function setWilayahOptions(select, placeholder, entries) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            entries.forEach(entry => {
                const option = document.createElement('option');
                option.value = entry.name;
                option.textContent = entry.name;
                option.dataset.id = entry.id ?? '';
                select.appendChild(option);
            });
            select.disabled = false;
        }

        if (kabupatenB02 && kecamatanB02 && desaB02) {
            fetch(`${wilayahApi}/regencies/72.json`)
                .then(response => response.json())
                .then(regencies => setWilayahOptions(kabupatenB02, 'Pilih kabupaten/kota', regencies))
                .catch(() => {
                    kabupatenB02.innerHTML = '<option value="">Gagal memuat kabupaten/kota</option>';
                });

            kabupatenB02.addEventListener('change', function() {
                const regencyId = this.options[this.selectedIndex]?.dataset.id;
                kecamatanB02.disabled = true;
                desaB02.disabled = true;
                kecamatanB02.innerHTML = '<option value="">Pilih kecamatan</option>';
                desaB02.innerHTML = '<option value="">Pilih kelurahan/desa</option>';

                if (regencyId) {
                    fetch(`${wilayahApi}/districts/${regencyId}.json`)
                        .then(response => response.json())
                        .then(districts => setWilayahOptions(kecamatanB02, 'Pilih kecamatan', districts));
                }
            });

            kecamatanB02.addEventListener('change', function() {
                const districtId = this.options[this.selectedIndex]?.dataset.id;
                desaB02.disabled = true;
                desaB02.innerHTML = '<option value="">Pilih kelurahan/desa</option>';

                if (districtId) {
                    fetch(`${wilayahApi}/villages/${districtId}.json`)
                        .then(response => response.json())
                        .then(villages => setWilayahOptions(desaB02, 'Pilih kelurahan/desa', villages));
                }
            });
        }
    </script>
</body>
</html>