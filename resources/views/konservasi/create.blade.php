<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - SIDAK BKSDA Sulawesi Tengah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Alpine.js untuk Form Dinamis Conditional -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
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
<body class="bg-slate-100/90 text-slate-800 antialiased min-h-screen flex flex-col relative" style="font-family: 'Plus Jakarta Sans', sans-serif;">

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
        $kategoriPerjumpaanC01 = [
            'Human Observation' => [
                ['Perjumpaan langsung', '16.336'], ['Jejak', '17.086'], ['Suara', '16.886'],
                ['Feses', '9.272'], ['Sarang', '7.312'], ['Cakaran', '3.704'],
                ['Kubangan', '1.844'], ['Satwa Mati', '58'],
            ],
            'Machine observation' => [
                ['Data GPS tracking', '12.860'], ['Kamera jebak', '2.196'],
                ['Aerial data/drone thermal', '6'], ['Audio recording', '0'],
                ['Radio telemetri', '0'], ['Rekaman dari berita/media', '0'],
            ],
            'Living Specimen' => [['Living Speciemen (satwa & tumbuhan hidup di luar habitatnya/fasilitas eksitu)', '7']],
            'Material Citation' => [['Material Citation (data dari publikasi)', '4']],
            'Preserve Specimen' => [['Preserve Specimen (data dari museum alam)', '1']],
            'Fossil Specimen' => [['Fossil Specimen', '28']],
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
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">GIS</a>
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
    <main class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 flex-1">

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
                 adaPengesahanD03: '',
                 adaKegiatanB01: 'ya',
                 adaAksesB02: 'ya',
                 adaDataLembagaC02: '',
                 bentukLembagaC02: '',
                 bentukLembagaUmumC02: ['Kebun Binatang', 'Taman Safari', 'Taman Satwa', 'Taman Satwa Khusus', 'Museum Zoologi', 'Kebun Botani', 'Taman Tumbuhan Khusus', 'Herbarium'],
                 adaDataKoleksiC03: '',
                 spesiesKoleksiC03: '',
                 adaDataPenangkaranC04: '',
                 adaDataJenisTslC05: '',
                 spesiesDitangkarkanC05: '',
                 adaDataPengedarC06: '',
                 adaRealisasiPengambilanC08: '',
                 tampilkanKuotaTangkapC08: false,
                 adaRealisasiEksporC09: '',
                 adaRealisasiEksporC10: '',
                 adaDataSitaanC11: '',
                 adaPnbTslC12: '',
                 adaInteraksiNegatifC14: '',
                 spesiesInteraksiC14: '',
                 adaPelepasliaranC15: 'ya',
                 spesiesPelepasliaranC15: '',
                 adaKelahiranC16: 'ya',
                 spesiesKelahiranC16: '',
                 adaKematianC17: 'ya',
                 spesiesKematianC17: '',
                 penyebabKematianC17: '',
                 temuanPerjumpaanSpesiesC01: '',
                 kategoriPerjumpaanC01: '',
                 spesiesC01: '',
                 spesiesLainnyaC01: '',
                 adaDataOdtwaBaru: '',
                 adaPenerbitanIzinPbp: '',
                 adaSaranaPrasaranaD07: '',
                 dataTersediaD08: '',
                 adaPotensiAirD09: '',
                 dataTersediaD10D16: '',
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
                <div x-cloak x-show="selectedSubBidangKode === 'A.01'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                                    <p class="text-[10px] text-slate-400 mb-1">Format PDF, maksimal 10 MB per file.</p>
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
                                    <p class="text-[10px] text-slate-400 mb-1">Format PDF, maksimal 10 MB per file.</p>
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
                                    <p class="text-[10px] text-slate-400 mb-1">Format PDF, maksimal 10 MB per file.</p>
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
                <div x-cloak x-show="selectedSubBidangKode === 'A.02'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_rpjp" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                                <p class="text-[10px] text-slate-400 mb-1">Format PDF, maksimal 10 MB per file.</p>
                                <input type="file" name="sk_rpjp_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG 3: A.03 / Monitoring Batas Kawasan Konservasi      -->
                <!-- ========================================================================= -->
                <div x-cloak x-show="selectedSubBidangKode === 'A.03'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_monitoring" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                <div x-cloak x-show="selectedSubBidangKode === 'A.04'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_evaluasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                <div x-cloak x-show="selectedSubBidangKode === 'A.05'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_ekosistem" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                <div x-cloak x-show="selectedSubBidangKode === 'A.06'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_zonasi" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
                                    <p class="text-[10px] text-slate-400 mb-1">Format PDF, maksimal 10 MB per file.</p>
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

                <!-- FORM DINAMIS SUB-BIDANG D.01: Pengunjung Kawasan Konservasi -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.01'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                        <select name="kawasan_nama_d01" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $kategoriPengunjung = [
                            'penelitian' => 'Penelitian & Pengembangan',
                            'pendidikan' => 'Pendidikan & Ilmu Pengetahuan',
                            'foto_video' => 'Pengambilan Foto & Video',
                            'wisata_alam' => 'Wisata Alam',
                            'lain_lain' => 'Lain-lain (Keagamaan/Kunjungan Kedinasan)',
                        ];
                    @endphp
                    @foreach ($kategoriPengunjung as $kodeKategori => $labelKategori)
                        <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl shadow-sm">
                            <legend class="px-2 text-xs font-extrabold text-slate-800 uppercase tracking-wider">{{ $labelKategori }}</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (['dalam_negeri' => 'Dalam Negeri', 'luar_negeri' => 'Luar Negeri'] as $wilayah => $labelWilayah)
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelWilayah }}: <span class="text-rose-500">*</span></label>
                                        <div class="flex">
                                            <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-l-lg text-xs text-slate-500">Jumlah</span>
                                            <input type="number" min="0" name="{{ $kodeKategori }}_{{ $wilayah }}" value="0" class="w-full min-w-0 p-2.5 border-y border-slate-300 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                            <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-lg text-xs text-slate-500">Orang</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach

                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.02: PNBP Wisata Alam -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.02'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                        <select name="kawasan_nama_d02" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $kelompokPnbp = [
                            ['judul' => 'TIKET MASUK PENGUNJUNG', 'items' => [
                                ['kode' => 'dalam_negeri_hari_kerja', 'nama' => 'Dalam Negeri (Hari Kerja)', 'unit' => 'Orang'],
                                ['kode' => 'luar_negeri_hari_kerja', 'nama' => 'Luar Negeri (Hari Kerja)', 'unit' => 'Orang'],
                                ['kode' => 'pelajar_hari_kerja', 'nama' => 'Pelajar/Mahasiswa (Hari Kerja)', 'unit' => 'Orang'],
                                ['kode' => 'dalam_negeri_hari_libur', 'nama' => 'Dalam Negeri (Hari Libur/Cuti Bersama/Hari Raya)', 'unit' => 'Orang'],
                                ['kode' => 'pelajar_hari_libur', 'nama' => 'Pelajar/Mahasiswa (Hari Libur/Cuti Bersama/Hari Raya)', 'unit' => 'Orang'],
                            ]],
                            ['judul' => 'TIKET MASUK KENDARAAN DARAT', 'items' => [
                                ['kode' => 'roda_2', 'nama' => 'Roda 2', 'unit' => 'Unit'],
                                ['kode' => 'roda_4', 'nama' => 'Roda 4', 'unit' => 'Unit'],
                                ['kode' => 'roda_6', 'nama' => 'Roda 6 atau Lebih', 'unit' => 'Unit'],
                                ['kode' => 'sepeda', 'nama' => 'Sepeda', 'unit' => 'Unit'],
                                ['kode' => 'kuda', 'nama' => 'Kuda', 'unit' => 'Ekor'],
                            ]],
                            ['judul' => 'TIKET MASUK KENDARAAN AIR', 'items' => [
                                ['kode' => 'kapal_40_100', 'nama' => 'Kapal Motor 40 s.d. 100 PK', 'unit' => 'Unit'],
                                ['kode' => 'kapal_101_500', 'nama' => 'Kapal Motor 101 s.d. 500 PK', 'unit' => 'Unit'],
                                ['kode' => 'kapal_diatas_500', 'nama' => 'Kapal Motor diatas 500 PK', 'unit' => 'Unit'],
                                ['kode' => 'kapal_pesiar', 'nama' => 'Kapal Pesiar/Cruiser Ship', 'unit' => 'Unit'],
                            ]],
                            ['judul' => 'TIKET MASUK KENDARAAN TRANSPORTASI KHUSUS', 'items' => [
                                ['kode' => 'transportasi_khusus', 'nama' => 'Transportasi Khusus', 'unit' => 'Unit'],
                            ]],
                            ['judul' => 'KEGIATAN WISATA ALAM', 'items' => [
                                ['kode' => 'berkemah', 'nama' => 'Berkemah', 'unit' => 'Orang'],
                                ['kode' => 'mendaki', 'nama' => 'Mendaki Gunung (Hiking-Climbing)', 'unit' => 'Orang'],
                                ['kode' => 'gua', 'nama' => 'Penelusuran Gua (Caving)', 'unit' => 'Orang'],
                                ['kode' => 'memancing', 'nama' => 'Memancing (Sport Fishing)', 'unit' => 'Orang'],
                                ['kode' => 'menyelam', 'nama' => 'Menyelam (Scuba Diving)', 'unit' => 'Orang'],
                                ['kode' => 'arung_jeram', 'nama' => 'Arung Jeram (Tubbing)', 'unit' => 'Orang'],
                                ['kode' => 'paralayang', 'nama' => 'Paralayang', 'unit' => 'Orang'],
                                ['kode' => 'balon_udara', 'nama' => 'Balon Udara', 'unit' => 'Orang'],
                            ]],
                        ];
                    @endphp

                    @foreach ($kelompokPnbp as $kelompok)
                        <section class="space-y-4">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">{{ $kelompok['judul'] }}</h4>
                            @foreach ($kelompok['items'] as $item)
                                <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl shadow-sm">
                                    <legend class="px-2 text-xs font-extrabold text-slate-800 uppercase">{{ $item['nama'] }}</legend>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah PNBP:</label>
                                            <div class="flex">
                                                <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-l-lg text-xs text-slate-500">Rp</span>
                                                <input type="number" min="0" name="d02_{{ $item['kode'] }}_pnbp" value="0" class="w-full min-w-0 p-2.5 border-y border-slate-300 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-lg text-xs text-slate-500">,00</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah {{ $item['unit'] }}:</label>
                                            <div class="flex">
                                                <input type="number" min="0" name="d02_{{ $item['kode'] }}_jumlah" value="0" class="w-full min-w-0 p-2.5 border border-slate-300 rounded-l-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-lg text-xs text-slate-500">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endforeach
                        </section>
                    @endforeach

                    @php
                        $kegiatanPnbp = [
                            [
                                'judul' => 'PENGAMBILAN GAMBAR KOMERSIL',
                                'items' => [
                                    ['kode' => 'videografi_komersil', 'nama' => 'VIDEOGRAFI YANG DIPERGUNAKAN UNTUK IKLAN PRODUK/IKLAN JASA/VIDEO CLIP/FILM/DRAMA/SINETRON/FTV/WEB DRAMA/REALITY SHOW DAN SEJENISNYA'],
                                    ['kode' => 'fotografi_komersil', 'nama' => 'FOTOGRAFI YANG DIPERGUNAKAN UNTUK PAKET WISATA/MAJALAH/IKLAN PRODUK/IKLAN JASA DAN SEJENISNYA'],
                                ],
                            ],
                            ['judul' => 'VIDEO DAN FOTO PREWEDDING', 'items' => [['kode' => 'video_foto_pre_wedding', 'nama' => 'VIDEO DAN FOTO PREWEDDING']]],
                            ['judul' => 'KEGIATAN PENGGUNAAN/MENERBANGKAN DRONE', 'items' => [['kode' => 'penggunaan_drone', 'nama' => 'PENGGUNAAN/MENERBANGKAN DRONE']]],
                            ['judul' => 'PENGGUNAAN FASILITAS UNTUK KEGIATAN WISATA', 'items' => [['kode' => 'penggunaan_fasilitas_wisata', 'nama' => 'PENGGUNAAN FASILITAS UNTUK KEGIATAN WISATA']]],
                            ['judul' => 'DENDA PENGUNJUNG DAN KENDARAAN ILEGAL/TIDAK MEMILIKI TIKET MASUK', 'items' => [['kode' => 'denda_pengunjung_kendaraan', 'nama' => 'PENUNJANG DALAM NEGERI DAN LUAR NEGERI']]],
                        ];
                    @endphp

                    @foreach ($kegiatanPnbp as $kelompok)
                        <section class="space-y-4">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">{{ $kelompok['judul'] }}</h4>
                            @foreach ($kelompok['items'] as $item)
                                <fieldset class="p-5 bg-slate-50/80 border border-slate-200 rounded-2xl shadow-sm">
                                    <legend class="px-2 text-xs font-extrabold text-slate-800 uppercase">{{ $item['nama'] }}</legend>
                                    @foreach (['dalam_negeri' => 'DALAM NEGERI', 'luar_negeri' => 'LUAR NEGERI'] as $kodeWilayah => $namaWilayah)
                                        @if (!$loop->first)
                                            <div class="border-t border-slate-200 my-5"></div>
                                        @endif
                                        <div class="space-y-3">
                                            <h5 class="text-xs font-extrabold text-slate-800 uppercase">{{ $namaWilayah }}</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah PNBP:</label>
                                                    <div class="flex">
                                                        <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-l-lg text-xs text-slate-500">Rp</span>
                                                        <input type="number" min="0" name="d02_{{ $item['kode'] }}_{{ $kodeWilayah }}_pnbp" value="0" class="w-full p-2.5 border-y border-slate-300 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-lg text-xs text-slate-500">,00</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Unit:</label>
                                                    <div class="flex">
                                                        <input type="number" min="0" name="d02_{{ $item['kode'] }}_{{ $kodeWilayah }}_jumlah" value="0" class="w-full p-2.5 border border-slate-300 rounded-l-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-lg text-xs text-slate-500">Orang</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                                <textarea name="d02_{{ $item['kode'] }}_{{ $kodeWilayah }}_keterangan" rows="3" placeholder="Masukkan keterangan" class="w-full p-3 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </fieldset>
                            @endforeach
                        </section>
                    @endforeach
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.03: Desain Tapak -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.03'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_d03" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Pengesahan / Perubahan Desain Tapak pada periode tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada pengesahan / perubahan desain tapak</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_pengesahan_d03" value="ya" x-model="adaPengesahanD03" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_pengesahan_d03" value="tidak" x-model="adaPengesahanD03" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaPengesahanD03 === 'ya'" x-transition class="mt-4 p-5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4">
                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">DETAIL PENGESAHAN / PERUBAHAN DESAIN TAPAK</h4>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Zonasi/Blok: <span class="text-rose-500">*</span></label>
                                <input type="text" name="zonasi_blok_d03" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bidang/Seksi Pengelolaan Wilayah: <span class="text-rose-500">*</span></label>
                                <input type="text" name="bidang_seksi_d03" placeholder="Lokasi Bidang/Seksi Pengelolaan Wilayah" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs placeholder:text-slate-300 focus:ring-2 focus:ring-forest-600 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nomor_dokumen_d03" placeholder="Nomor surat keputusan" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs placeholder:text-slate-300 focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal SK: <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal_pengesahan_d03" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Judul SK: <span class="text-rose-500">*</span></label>
                                <input type="text" name="judul_sk_d03" placeholder="Judul/perihal surat keputusan" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-xl text-xs placeholder:text-slate-300 focus:ring-2 focus:ring-forest-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Luas Zona/Blok Pemanfaatan: <span class="text-rose-500">*</span></label>
                                <div class="flex">
                                    <input type="number" min="0" step="0.01" name="luas_zona_d03" x-bind:required="adaPengesahanD03 === 'ya'" class="w-full p-2.5 bg-white border border-slate-300 rounded-l-xl text-xs focus:ring-2 focus:ring-forest-600 focus:outline-none">
                                    <span class="px-3 py-2.5 bg-slate-100 border border-slate-300 rounded-r-xl text-xs text-slate-500">Ha</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Dokumen (format pdf &amp; maks. 10 mb):</label>
                                <input type="file" name="dokumen_d03" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                            </div>
                            <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-2">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">LOKASI GEOGRAFIS DESAIN TAPAK</h4>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Polygon Area (shapefile):</label>
                                <p class="text-[11px] text-slate-500 mb-2">ESRI shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip</p>
                                <p class="text-[11px] text-slate-500 mb-2">Atribut yang wajib tersedia yaitu REG_KK, JNS_RUANG, LUAS.</p>
                                <input type="file" name="shapefile_d03" accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Komentar:</label>
                                <textarea name="komentar_d03" rows="3" placeholder="Tuliskan komentar atau catatan tambahan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.04: Potensi Wisata Alam Kawasan Konservasi -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.04'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_d04" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_d04" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Data Potensi Wisata Alam yang belum terdaftar?</label>
                                <p class="text-[11px] text-slate-500">Pilih ya jika terdapat ODTWA baru yang belum terdaftar</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_data_odtwa_baru" value="ya" x-model="adaDataOdtwaBaru" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, data ODTWA baru</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_data_odtwa_baru" value="tidak" x-model="adaDataOdtwaBaru" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaDataOdtwaBaru === 'ya'" x-transition class="mt-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama ODTWA: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nama_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" placeholder="Masukkan nama ODTWA" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis ODTWA: <span class="text-rose-500">*</span></label>
                                    <select name="jenis_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        <option value="">Pilih jenis odtwa</option>
                                        <option value="Flora dan Fauna">Flora dan Fauna</option>
                                        <option value="Gejala Alam">Gejala Alam</option>
                                        <option value="Keindahan Alam">Keindahan Alam</option>
                                        <option value="Keunikan">Keunikan</option>
                                        <option value="Panorama">Panorama</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI TITIK GEOGRAFIS ODTWA (XY): <span class="text-rose-500">*</span></h4>
                                <div class="rounded-2xl overflow-hidden border border-slate-300 bg-cover bg-center h-64 relative" style="background-image: linear-gradient(180deg, rgba(7,21,39,0.35), rgba(7,21,39,0.55)), url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1200&q=80');">
                                    <div class="absolute top-3 right-3 w-3 h-3 rounded-full bg-white shadow-md border border-slate-400"></div>
                                    <div class="absolute bottom-5 right-5 bg-white/80 border border-slate-300 text-[10px] font-bold text-slate-700 px-3 py-1 rounded-md shadow-sm">500 km</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Longitude: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <span class="px-3 text-xs font-semibold text-slate-500">X</span>
                                        <input type="text" name="longitude_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" placeholder="101.2323452" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Latitude: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <span class="px-3 text-xs font-semibold text-slate-500">Y</span>
                                        <input type="text" name="latitude_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" placeholder="1.234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label>
                                    <select name="zona_blok_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        <option value="">Pilih zona/blok</option>
                                        <option value="Inti">Inti</option>
                                        <option value="Rimba">Rimba</option>
                                        <option value="Pemanfaatan">Pemanfaatan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Daya Dukung &amp; Daya Tampung: <span class="text-rose-500">*</span></label>
                                <input type="text" name="daya_dukung_odtwa" x-bind:required="adaDataOdtwaBaru === 'ya'" placeholder="Masukkan daya dukung dan daya tampung" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Foto ODTWA (JPG/PNG):</label>
<<<<<<< HEAD
                                <p class="text-[11px] text-slate-500 mb-2">Ukuran maksimal 10 MB per file.</p>
=======
                                <p class="text-[11px] text-slate-500 mb-2">Ukuran maksimal 1 Mb</p>
>>>>>>> temp-fix
                                <input type="file" name="foto_odtwa" accept="image/png,image/jpeg" class="w-full max-w-md h-12 rounded-xl border border-slate-300 bg-slate-50 text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                <textarea name="keterangan_odtwa" rows="3" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.05: Pemanfaatan Jasa Lingkungan PBP -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.05'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_d05" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_d05" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Penerbitan Izin Pemanfaatan Jasa Lingkungan PBP pada periode tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih ya jika ada penerbitan izin pemanfaatan jasa lingkungan</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_penerbitan_izin_pbp" value="ya" x-model="adaPenerbitanIzinPbp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_penerbitan_izin_pbp" value="tidak" x-model="adaPenerbitanIzinPbp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaPenerbitanIzinPbp === 'ya'" x-transition class="mt-5 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Wilayah/Area Kerja Penyedia Jasa: <span class="text-rose-500">*</span></label>
                                <input type="text" name="wilayah_area_kerja_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan wilayah/area kerja penyedia jasa.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Provinsi: <span class="text-rose-500">*</span></label>
                                <select name="provinsi_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                    <option value="">Pilih provinsi</option>
                                    <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Jenis Perizinan:</label>
                                <div class="space-y-3">
                                    @foreach (['Penyediaan Informasi Wisata', 'Penyediaan Jasa Pramuwisata', 'Penyediaan Jasa Makan Minuman', 'Penyediaan Jasa Transportasi', 'Penyediaan Jasa Cinderamata', 'Penyediaan Jasa Perjalanan Wisata', 'Penyediaan Jasa Persewaan Alat'] as $index => $jenisPerizinan)
                                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700">
                                            <input type="radio" name="jenis_perizinan_pbp" value="{{ $jenisPerizinan }}" x-bind:required="adaPenerbitanIzinPbp === 'ya' && {{ $index === 0 ? 'true' : 'false' }}" class="text-forest-600 focus:ring-forest-600">
                                            <span>{{ $jenisPerizinan }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pemegang Izin Berusaha: <span class="text-rose-500">*</span></label>
                                <input type="text" name="pemegang_izin_berusaha_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan nama pemegang izin.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Berusaha (NIB): <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nib_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan Nomor Induk Berusaha (NIB).." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Sertifikat Standar: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nomor_sertifikat_standar_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan Nomor Sertifikat Standar.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label>
                                <select name="zona_blok_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                    <option value="">Pilih Zona/Blok</option>
                                    <option value="Inti">Inti</option>
                                    <option value="Rimba">Rimba</option>
                                    <option value="Pemanfaatan">Pemanfaatan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Tenaga Kerja: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <input type="number" min="0" name="jumlah_tenaga_kerja_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">org</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Perkiraan Omset Per Tahun (Rp): <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span>
                                        <input type="number" min="0" step="0.01" name="perkiraan_omset_per_tahun_pbp" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                <textarea name="keterangan_pbp" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.06: Pemanfaatan Jasa Lingkungan PBP Jasa Lainnya -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.06'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tahun: <span class="text-rose-500">*</span>
                        </label>
                        <select name="tahun_d06" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Kawasan Konservasi: <span class="text-rose-500">*</span>
                        </label>
                        <select name="kawasan_nama_d06" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Penerbitan Izin Pemanfaatan Jasa Lingkungan PBP pada periode tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih ya jika ada penerbitan izin pemanfaatan jasa lingkungan</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_penerbitan_izin_pbp_d06" value="ya" x-model="adaPenerbitanIzinPbp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_penerbitan_izin_pbp_d06" value="tidak" x-model="adaPenerbitanIzinPbp" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaPenerbitanIzinPbp === 'ya'" x-transition class="mt-5 space-y-5">
                            <div class="pt-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Ruang Usaha: <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_ruang_usaha_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Nama ruang usaha" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-4">INFORMASI PEMEGANG IZIN USAHA</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemegang Izin: <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nama_pemegang_izin_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Nama pemegang izin usaha" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Berusaha (NIB): <span class="text-rose-500">*</span></label>
                                            <input type="text" name="nib_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan nomor induk berusaha..." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Perizinan: <span class="text-rose-500">*</span></label>
                                            <input type="text" name="nomor_perizinan_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Nomor surat" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perizinan: <span class="text-rose-500">*</span></label>
                                            <input type="date" name="tanggal_perizinan_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Luas Area Perizinan: <span class="text-rose-500">*</span></label>
                                            <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                                <input type="number" min="0" step="0.01" name="luas_area_perizinan_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                                <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Ha</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label>
                                            <input type="text" name="zona_blok_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" placeholder="Masukan zona/blok" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Provinsi: <span class="text-rose-500">*</span></label>
                                            <select name="provinsi_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                                <option value="">Pilih provinsi</option>
                                                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Tenaga Kerja: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <input type="number" min="0" name="jumlah_tenaga_kerja_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">org</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Investasi (Rp): <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span>
                                        <input type="number" min="0" step="0.01" name="nilai_investasi_pbp_d06" x-bind:required="adaPenerbitanIzinPbp === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label>
                                <div class="w-full max-w-none rounded-xl border border-slate-300 bg-slate-50 p-0 overflow-hidden">
                                    <label class="flex items-center justify-between gap-3 px-3 py-2.5 cursor-pointer text-xs text-slate-600 hover:bg-slate-100">
                                        <span class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 font-bold text-slate-700">Pilih File</span>
                                        <span class="text-slate-500">Tidak ada file yang dipilih</span>
                                        <input type="file" name="dokumen_perizinan_pbp_d06" accept=".pdf" class="hidden">
                                    </label>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI GEOGRAFIS AREAL IZIN</h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Polygon Area (shapefile):</label>
                                    <p class="text-[11px] text-slate-500 mb-2">ESRI Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip</p>
                                    <div class="w-full max-w-none rounded-xl border border-slate-300 bg-slate-50 p-0 overflow-hidden">
                                        <label class="flex items-center justify-between gap-3 px-3 py-2.5 cursor-pointer text-xs text-slate-600 hover:bg-slate-100">
                                            <span class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 font-bold text-slate-700">Pilih File</span>
                                            <span class="text-slate-500">Tidak ada file yang dipilih</span>
                                            <input type="file" name="shapefile_pbp_d06" accept=".zip" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                <textarea name="keterangan_pbp_d06" rows="3" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.07: Sarana dan Prasarana Wisata Alam -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.07'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_d07" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                        <select name="kawasan_nama_d07" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Data Sarana dan Prasarana Wisata Alam yang belum terdata pada Periode tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada penambahan sarana dan prasarana wisata alam</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_sarana_prasarana_d07" value="ya" x-model="adaSaranaPrasaranaD07" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_sarana_prasarana_d07" value="tidak" x-model="adaSaranaPrasaranaD07" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaSaranaPrasaranaD07 === 'ya'" x-transition class="mt-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sarpras Wisata Alam: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nama_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" placeholder="Pintu loket masuk kawasan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Pembangunan: <span class="text-rose-500">*</span></label>
                                    <select name="tahun_pembangunan_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                        <option value="">Pilih tahun</option>
                                        @for ($year = 2026; $year >= 1945; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Luas (m<sup>2</sup>):</label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                        <input type="number" min="0" step="0.01" name="luas_sarana_prasarana_d07" placeholder="23.12" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                        <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">m<sup>2</sup></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Kondisi Sarpras: <span class="text-rose-500">*</span></label>
                                    <div class="flex flex-wrap items-center gap-5 pt-2">
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700"><input type="radio" name="kondisi_sarana_prasarana_d07" value="Baik" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>Baik</span></label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700"><input type="radio" name="kondisi_sarana_prasarana_d07" value="Rusak" class="text-forest-600 focus:ring-forest-600"><span>Rusak</span></label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700"><input type="radio" name="kondisi_sarana_prasarana_d07" value="Rusak Berat" class="text-forest-600 focus:ring-forest-600"><span>Rusak Berat</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Investasi: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_investasi_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span></div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Sumber Dana: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="sumber_dana_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI/TITIK GEOGRAFIS SARANA &amp; PRASARANA (XY): <span class="text-rose-500">*</span></h4>
                                <div class="rounded-2xl overflow-hidden border border-slate-300 bg-cover bg-center h-64 relative" style="background-image: linear-gradient(180deg, rgba(7,21,39,0.35), rgba(7,21,39,0.55)), url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1200&q=80');"><div class="absolute top-3 right-3 w-3 h-3 rounded-full bg-white shadow-md border border-slate-400"></div><div class="absolute bottom-5 right-5 bg-white/80 border border-slate-300 text-[10px] font-bold text-slate-700 px-3 py-1 rounded-md shadow-sm">3.000 km</div></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">X</span><input type="text" name="longitude_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" placeholder="101.23234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div>
                                    <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Y</span><input type="text" name="latitude_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" placeholder="1.234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div>
                                </div>
                                <div class="mt-4"><label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label><select name="zona_blok_sarana_prasarana_d07" x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih zona/blok</option><option value="Inti">Inti</option><option value="Rimba">Rimba</option><option value="Pemanfaatan">Pemanfaatan</option><option value="Lainnya">Lainnya</option></select></div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Sarana &amp; Prasarana (JPG/PNG): <span class="text-rose-500">*</span></label>
<<<<<<< HEAD
                                <p class="text-[11px] text-slate-500 mb-2">Maksimal 3 foto, masing-masing berukuran maksimal 10 MB.</p>
=======
                                <p class="text-[11px] text-slate-500 mb-2">Maksimal 3 foto dengan ukuran maksimal 1 Mb</p>
>>>>>>> temp-fix
                                <div class="flex flex-col md:flex-row gap-4 items-start"><input type="file" name="foto_sarana_prasarana_d07[]" accept="image/png,image/jpeg" multiple x-bind:required="adaSaranaPrasaranaD07 === 'ya'" class="w-full md:w-1/2 h-12 rounded-xl border border-slate-300 bg-slate-50 text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"><div class="grid grid-cols-3 gap-3 w-full md:w-1/2">@foreach (range(1, 3) as $photoNumber)<div class="h-28 rounded-xl border border-slate-300 bg-white flex items-start p-2 text-xs font-bold text-slate-800">{{ $photoNumber }}</div>@endforeach</div></div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                <textarea name="keterangan_sarana_prasarana_d07" rows="3" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.08: Dampak Aktivitas Wisata Alam -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.08'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_d08" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="periode_bulan_d08" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                <option value="">Pilih periode</option>
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                        <select name="kawasan_nama_d08" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Data Tersedia pada Periode tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak ada jika data belum tersedia pada periode tersebut</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="data_tersedia_d08" value="ya" x-model="dataTersediaD08" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, data tersedia</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="data_tersedia_d08" value="tidak" x-model="dataTersediaD08" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="dataTersediaD08 === 'ya'" x-transition class="mt-5 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">ODTWA: <span class="text-rose-500">*</span></label>
                                <select name="odtwa_d08" x-bind:required="dataTersediaD08 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                    <option value="">Pilih ODTWA</option>
                                    <option value="ODTWA 1">ODTWA 1</option>
                                    <option value="ODTWA 2">ODTWA 2</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">DIPA (Komponen Kegiatan Wisata Alam): <span class="text-rose-500">*</span></label>
                                <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden max-w-md">
                                    <span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span>
                                    <input type="number" min="0" step="0.01" name="dipa_d08" x-bind:required="dataTersediaD08 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                    <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span>
                                </div>
                            </div>

                            @foreach ([
                                'pemandu' => 'PELAKU USAHA PEMANDU WISATA',
                                'ojek' => 'PELAKU USAHA OJEK WISATA',
                                'porter' => 'PELAKU USAHA PORTER WISATA',
                                'makanan_minuman' => 'PELAKU USAHA PENYEDIA MAKANAN & MINUMAN',
                                'homestay' => 'PELAKU USAHA HOMESTAY'
                            ] as $jenisPelaku => $judulPelaku)
                                <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 px-5 pb-5 pt-2">
                                    <legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">{{ $judulPelaku }}</legend>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Pelaku Usaha: <span class="text-rose-500">*</span></label>
                                            <div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden">
                                                <input type="number" min="0" name="jumlah_pelaku_{{ $jenisPelaku }}_d08" x-bind:required="dataTersediaD08 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                                <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Orang</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Perkiraan Omset (Rp): <span class="text-rose-500">*</span></label>
                                            <div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden">
                                                <span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span>
                                                <input type="number" min="0" step="0.01" name="omset_{{ $jenisPelaku }}_d08" x-bind:required="dataTersediaD08 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                                <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endforeach

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Perkiraan Nilai Ekonomi yang terdampak disekitar ODTWA: <span class="text-rose-500">*</span></label>
                                <div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden">
                                    <span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Rp</span>
                                    <input type="number" min="0" step="0.01" name="nilai_ekonomi_terdampak_d08" x-bind:required="dataTersediaD08 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none">
                                    <span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">,00</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label>
                                <textarea name="keterangan_d08" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS SUB-BIDANG D.09: Potensi Pemanfaatan Air dan Energi Air -->
                <div x-cloak x-show="selectedSubBidangKode === 'D.09'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_d09" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                        <select name="kawasan_nama_d09" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                            <option value="">Pilih kawasan konservasi</option>
                            @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700">Ada Potensi Air dan Energi Air yang belum terdata di kawasan tersebut?</label>
                                <p class="text-[11px] text-slate-500">Pilih tidak jika tidak ada/nihil</p>
                            </div>
                            <div class="flex items-center gap-6 shrink-0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_potensi_air_d09" value="ya" x-model="adaPotensiAirD09" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ada_potensi_air_d09" value="tidak" x-model="adaPotensiAirD09" class="text-forest-600 focus:ring-forest-600">
                                    <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="adaPotensiAirD09 === 'ya'" x-transition class="mt-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Sumber Air: <span class="text-rose-500">*</span></label><input type="text" name="sumber_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Jenis Sumber Air: <span class="text-rose-500">*</span></label><select name="jenis_sumber_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih jenis sumber air</option><option value="Sungai">Sungai</option><option value="Mata Air">Mata Air</option><option value="Danau">Danau</option><option value="Lainnya">Lainnya</option></select></div>
                            </div>
                            <div><h4 class="font-bold text-sm text-slate-800 mb-3">Potensi Sumber Air</h4><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Debit: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="debit_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Liter/Detik</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Potensi energi air: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="potensi_energi_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Kw</span></div></div></div></div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4"><h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI TITIK GEOGRAFIS (XY): <span class="text-rose-500">*</span></h4><div class="rounded-2xl overflow-hidden border border-slate-300 bg-cover bg-center h-64" style="background-image: linear-gradient(180deg, rgba(7,21,39,0.35), rgba(7,21,39,0.55)), url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1200&q=80');"></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Longitude: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">X</span><input type="text" name="longitude_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" placeholder="101.23234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Latitude: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs font-semibold text-slate-500 border-r border-slate-300">Y</span><input type="text" name="latitude_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" placeholder="1.234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div></div></div><div class="mt-4"><label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label><select name="zona_blok_air_d09" x-bind:required="adaPotensiAirD09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih zona/blok</option><option value="Inti">Inti</option><option value="Rimba">Rimba</option><option value="Pemanfaatan">Pemanfaatan</option><option value="Lainnya">Lainnya</option></select></div><div class="mt-4"><label class="block text-xs font-bold text-slate-700 mb-1">Wilayah Daerah Aliran Sungai:</label><input type="text" name="wilayah_das_d09" placeholder="Sebutkan wilayah aliran DAS.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div><div class="mt-4"><label class="block text-xs font-bold text-slate-700 mb-2">Sudah Ditetapkan sbg Areal Pemanfaatan?: <span class="text-rose-500">*</span></label><div class="flex items-center gap-6"><label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700"><input type="radio" name="status_areal_pemanfaatan_d09" value="sudah" x-bind:required="adaPotensiAirD09 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>Sudah Ditetapkan</span></label><label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700"><input type="radio" name="status_areal_pemanfaatan_d09" value="belum" class="text-forest-600 focus:ring-forest-600"><span>Belum Ditetapkan</span></label></div></div></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_air_d09" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                        </div>
                    </div>
                </div>

                @foreach ([
                    'D.10' => 'Areal Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.11' => 'Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.12' => 'Potensi Pemanfaatan Karbon di Kawasan Konservasi',
                    'D.13' => 'Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi',
                    'D.14' => 'Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi',
                    'D.15' => 'Kejadian Kecelakaan di dalam Kawasan Konservasi',
                    'D.16' => 'Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi'
                ] as $kodeSubBidang => $namaSubBidang)
                    @php($kodeForm = str_replace('.', '_', strtolower($kodeSubBidang)))
                    <div x-cloak x-show="selectedSubBidangKode === '{{ $kodeSubBidang }}'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_{{ $kodeForm }}" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        @if ($kodeSubBidang === 'D.16')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Periode Semester: <span class="text-rose-500">*</span></label>
                                    <select name="periode_semester_d16" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                        <option value="">Pilih periode</option>
                                        <option value="Semester I">Semester I</option>
                                        <option value="Semester II">Semester II</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                                    <select name="satuan_kerja_d16" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                        <option value="">Pilih satuan kerja</option>
                                        <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kawasan Konservasi: <span class="text-rose-500">*</span></label>
                                <select name="kawasan_nama_{{ $kodeForm }}" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none" required>
                                    <option value="">Pilih kawasan konservasi</option>
                                    @foreach ($kawasanKonservasi ?? [] as $kawasan)
                                        <option value="{{ $kawasan['nama'] ?? $kawasan }}">{{ $kawasan['nama'] ?? $kawasan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="pt-2">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div>
                                    @if ($kodeSubBidang === 'D.10')
                                        <label class="block text-xs font-bold text-slate-700">Ada Pembaruan Data Areal Pemanfaatan Air &amp; Energi Air yang belum terdata pada kawasan tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada</p>
                                    @elseif ($kodeSubBidang === 'D.11')
                                        <label class="block text-xs font-bold text-slate-700">Ada Data Pemanfaatan Air dan Energi Air yang belum terdata di kawasan tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak jika tidak ada/nihil</p>
                                    @elseif ($kodeSubBidang === 'D.12')
                                        <label class="block text-xs font-bold text-slate-700">Tersedia data potensi pemanfaatan karbon untuk kawasan di periode tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika data belum tersedia atau tidak ada perubahan dari data sebelumnya</p>
                                    @elseif ($kodeSubBidang === 'D.13')
                                        <label class="block text-xs font-bold text-slate-700">Ada Data Potensi Panas Bumi yang belum terdaftar?</label>
                                        <p class="text-[11px] text-slate-500">Pilih ya jika terdapat potensi panas bumi baru yang belum terdaftar</p>
                                    @elseif ($kodeSubBidang === 'D.14')
                                        <label class="block text-xs font-bold text-slate-700">Ada data Pemanfaatan Panas Bumi yang belum masuk pada periode tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada penerbitan izin pemanfaatan panas bumi pada periode tersebut</p>
                                    @elseif ($kodeSubBidang === 'D.15')
                                        <label class="block text-xs font-bold text-slate-700">Ada Kejadian Kecelakaan Wisata Alam pada Periode tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ditemukan gangguan kawasan</p>
                                    @elseif ($kodeSubBidang === 'D.16')
                                        <label class="block text-xs font-bold text-slate-700">Ada kegiatan promosi/publikasi jasa lingkungan pada periode tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada, jika tidak ada kegiatan publikasi/promosi</p>
                                    @else
                                        <label class="block text-xs font-bold text-slate-700">Data Tersedia pada Periode tersebut?</label>
                                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika data belum tersedia pada periode tersebut</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-6 shrink-0">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="data_tersedia_{{ $kodeForm }}" value="ya" x-model="dataTersediaD10D16" class="text-forest-600 focus:ring-forest-600">
                                        <span class="text-xs font-medium text-slate-700">{{ $kodeSubBidang === 'D.15' ? 'Ya, ada kejadian' : ($kodeSubBidang === 'D.13' ? 'Ya, tambah data' : ($kodeSubBidang === 'D.16' ? 'Ya, ada' : 'Ya, data tersedia')) }}</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="data_tersedia_{{ $kodeForm }}" value="tidak" x-model="dataTersediaD10D16" class="text-forest-600 focus:ring-forest-600">
                                        <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if ($kodeSubBidang === 'D.10')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Zonasi/Blok: <span class="text-rose-500">*</span></label>
                                    <div x-data="{ open: false, search: '', selected: '', options: ['Zona Inti', 'Blok Perlindungan', 'Zona Rimba', 'Blok Koleksi', 'Zona Pemanfaatan', 'Blok Pemanfaatan', 'Zona Perlindungan Bahari', 'Blok Perlindungan Bahari', 'Zona Tradisional', 'Blok Tradisional', 'Zona Rehabilitasi', 'Blok Rehabilitasi', 'Zona Khusus', 'Blok Khusus', 'Zona Religi, Budaya dan Sejarah', 'Blok Religi, Budaya dan Sejarah', 'Zona Lainnya', 'Blok Lainnya'] }" @click.outside="open = false; search = ''" class="relative">
                                        <input type="text" name="zonasi_blok_d10" x-model="selected" x-bind:required="dataTersediaD10D16 === 'ya'" x-bind:aria-expanded="open" aria-haspopup="listbox" readonly placeholder="Pilih Zona/Blok" @click="open = !open; if (open) $nextTick(() => $refs.zoneSearch.focus())" @keydown.escape="open = false" class="w-full p-3 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white cursor-pointer">
                                        <span class="pointer-events-none absolute right-4 top-1/2 h-2 w-2 -translate-y-1/2 rotate-45 border-b-2 border-r-2 border-slate-400"></span>
                                        <div x-cloak x-show="open" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-slate-300 bg-white shadow-lg">
                                            <input type="search" x-ref="zoneSearch" x-model="search" placeholder="Cari zona/blok..." class="w-full border-0 border-b border-slate-200 p-3 text-xs text-slate-800 focus:outline-none focus:ring-0">
                                            <div role="listbox" class="max-h-64 overflow-y-auto">
                                                <template x-for="option in options.filter((item) => item.toLowerCase().includes(search.toLowerCase()))" :key="option">
                                                    <button type="button" role="option" @click="selected = option; open = false; search = ''" class="block w-full border-b border-slate-100 px-3 py-2.5 text-left text-xs text-slate-800 hover:bg-blue-600 hover:text-white" x-text="option"></button>
                                                </template>
                                                <p x-show="options.filter((item) => item.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-3 py-3 text-xs text-slate-500">Zona/Blok tidak ditemukan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang/Seksi Pengelolaan Wilayah: <span class="text-rose-500">*</span></label>
                                    <input type="text" name="bidang_seksi_pengelolaan_wilayah_d10" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Lokasi Bidang/Seksi Pengelolaan Wilayah" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Sumber Air: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" name="jumlah_sumber_air_d10" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Titik</span></div></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Total Debit Air: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="total_debit_air_d10" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Liter/detik</span></div></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Areal Pemanfaatan: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="luas_areal_pemanfaatan_d10" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs font-semibold text-slate-500 border-l border-slate-300">Ha</span></div></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4"><h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI GEOGRAFIS AREAL PEMANFAATAN AIR &amp; ENERGI AIR</h4><label class="block text-xs font-bold text-slate-700 mb-1">Unggah Polygon Area (shapefile):</label><p class="text-[11px] text-slate-500 mb-2">ESRI Shapefile terdiri dari file dengan ekstensi .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip</p><input type="file" name="shapefile_d10" accept=".zip" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 bg-white rounded-xl"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d10" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.11')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Sumber Air: <span class="text-rose-500">*</span></label><select name="sumber_air_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full md:w-2/3 p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih Sumber Air</option><option value="Sungai">Sungai</option><option value="Mata Air">Mata Air</option><option value="Danau">Danau</option><option value="Lainnya">Lainnya</option></select></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-2">Jenis Perizinan: <span class="text-rose-500">*</span></label><div class="flex flex-wrap gap-6"><label class="inline-flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700"><input type="checkbox" name="jenis_perizinan_d11[]" value="Pemanfaatan Air" x-bind:required="dataTersediaD10D16 === 'ya'" class="rounded text-forest-600 focus:ring-forest-600"><span>Pemanfaatan Air</span></label><label class="inline-flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700"><input type="checkbox" name="jenis_perizinan_d11[]" value="Pemanfaatan Energi Air" class="rounded text-forest-600 focus:ring-forest-600"><span>Pemanfaatan Energi Air</span></label></div></div>
                                <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-4"><legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">INFORMASI PEMEGANG IZIN</legend><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemegang Izin: <span class="text-rose-500">*</span></label><input type="text" name="nama_pemegang_izin_d11" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Nama pemegang izin" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-2">Komersil?: <span class="text-rose-500">*</span></label><div class="flex items-center gap-6 pt-2"><label class="inline-flex items-center gap-2 cursor-pointer text-xs"><input type="radio" name="komersil_d11" value="komersil" x-bind:required="dataTersediaD10D16 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>Komersil</span></label><label class="inline-flex items-center gap-2 cursor-pointer text-xs"><input type="radio" name="komersil_d11" value="non_komersil" class="text-forest-600 focus:ring-forest-600"><span>Non Komersil</span></label></div></div></div><div class="grid grid-cols-1 md:grid-cols-3 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">No Surat Keputusan: <span class="text-rose-500">*</span></label><input type="text" name="no_surat_keputusan_d11" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="No surat keputusan" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal SK: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_sk_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Areal: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="luas_areal_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Ha</span></div></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen SK (pdf):</label><input type="file" name="dokumen_sk_d11" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 border border-slate-300 bg-white rounded-xl"></div></fieldset>
                                <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-4"><legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">INFORMASI PENERIMA MANFAAT</legend><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten/Kota: <span class="text-rose-500">*</span></label><input type="text" name="kabupaten_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan: <span class="text-rose-500">*</span></label><input type="text" name="kecamatan_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Kelurahan/Desa: <span class="text-rose-500">*</span></label><input type="text" name="kelurahan_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Jumlah KK yang dilayani: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" name="jumlah_kk_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">KK</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Tenaga Kerja: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" name="jumlah_tenaga_kerja_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Orang</span></div></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Nilai Investasi: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden max-w-md"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_investasi_d11" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">,00</span></div></div></fieldset>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"><h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI GEOGRAFIS AREAL IZIN PEMANFAATAN</h4><label class="block text-xs font-bold text-slate-700 mb-1">Unggah Polygon Area (shapefile):</label><p class="text-[11px] text-slate-500 mb-2">ESRI Shapefile terdiri dari file .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi .Zip</p><input type="file" name="shapefile_d11" accept=".zip" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 border border-slate-300 bg-white rounded-xl"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d11" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.12')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Potensi Karbon Per Ha: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="potensi_karbon_per_ha_d12" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Ton C/Ha</span></div></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Total Stok Karbon: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="total_stok_karbon_d12" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Ton C</span></div></div>
                                </div>
                                @foreach ([
                                    'flora' => 'Nilai Flora',
                                    'satwa' => 'Nilai Satwa',
                                    'jasa_air' => 'Nilai Jasa Air',
                                    'jasa_wisata_alam' => 'Nilai Jasa Wisata Alam',
                                    'jasa_lingkungan_total' => 'Nilai Jasa Lingkungan Total'
                                ] as $nilaiKode => $nilaiLabel)
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">{{ $nilaiLabel }}: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden max-w-md"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_{{ $nilaiKode }}_d12" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">,00</span></div><div class="mt-2 rounded-lg border border-blue-500 bg-blue-100 px-4 py-5 text-sm italic text-slate-700"><span class="block -mt-8 mb-3 w-fit rounded border border-blue-500 bg-white px-2 py-1 not-italic font-bold text-slate-800">Terbilang:</span>Nol Rupiah</div></div>
                                @endforeach
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Nilai Kualitas Karbon: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_kualitas_karbon_d12" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">/ Ton C</span></div><div class="mt-2 rounded-lg border border-blue-500 bg-blue-100 px-4 py-5 text-sm italic text-slate-700"><span class="block -mt-8 mb-3 w-fit rounded border border-blue-500 bg-white px-2 py-1 not-italic font-bold text-slate-800">Terbilang:</span>Nol Rupiah</div></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Nilai Ekosistem: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_ekosistem_d12" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">/ ha.thn</span></div><div class="mt-2 rounded-lg border border-blue-500 bg-blue-100 px-4 py-5 text-sm italic text-slate-700"><span class="block -mt-8 mb-3 w-fit rounded border border-blue-500 bg-white px-2 py-1 not-italic font-bold text-slate-800">Terbilang:</span>Nol Rupiah</div></div>
                                </div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d12" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.13')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Manifestasi: <span class="text-rose-500">*</span></label><input type="text" name="nama_manifestasi_d13" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Manifestasi: <span class="text-rose-500">*</span></label>
                                        <div x-data="{ open: false, search: '', selected: '', options: ['Mata Air Panas', 'Geiser', 'Fumarol', 'Solfatara', 'Kawah Geotermal', 'Panas Tanah', 'Panas Bumi Tersembunyi'] }" @click.outside="open = false; search = ''" class="relative">
                                            <input type="text" name="jenis_manifestasi_d13" x-model="selected" x-bind:required="dataTersediaD10D16 === 'ya'" x-bind:aria-expanded="open" aria-haspopup="listbox" readonly placeholder="Pilih jenis manifestasi" @click="open = !open; if (open) $nextTick(() => $refs.manifestationSearch.focus())" @keydown.escape="open = false" class="w-full p-3 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white cursor-pointer">
                                            <span class="pointer-events-none absolute right-4 top-1/2 h-2 w-2 -translate-y-1/2 rotate-45 border-b-2 border-r-2 border-slate-400"></span>
                                            <div x-cloak x-show="open" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-slate-300 bg-white shadow-lg">
                                                <input type="search" x-ref="manifestationSearch" x-model="search" placeholder="Cari jenis manifestasi..." class="w-full border-0 border-b border-slate-200 p-3 text-xs text-slate-800 focus:outline-none focus:ring-0">
                                                <div role="listbox" class="max-h-64 overflow-y-auto">
                                                    <template x-for="option in options.filter((item) => item.toLowerCase().includes(search.toLowerCase()))" :key="option">
                                                        <button type="button" role="option" @click="selected = option; open = false; search = ''" class="block w-full border-b border-slate-100 px-3 py-2.5 text-left text-xs text-slate-800 hover:bg-blue-600 hover:text-white" x-text="option"></button>
                                                    </template>
                                                    <p x-show="options.filter((item) => item.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-3 py-3 text-xs text-slate-500">Jenis manifestasi tidak ditemukan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4"><h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI TITIK GEOGRAFIS (XY): <span class="text-rose-500">*</span></h4><div class="rounded-2xl overflow-hidden border border-slate-300 bg-cover bg-center h-64" style="background-image: linear-gradient(180deg, rgba(7,21,39,0.35), rgba(7,21,39,0.55)), url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1200&q=80');"></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Longitude: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">X</span><input type="text" name="longitude_manifestasi_d13" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="101.23234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Latitude: <span class="text-rose-500">*</span></label><div class="flex items-center bg-slate-50 border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Y</span><input type="text" name="latitude_manifestasi_d13" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="1.234525" class="w-full p-3 bg-transparent text-xs text-slate-800 focus:outline-none"></div></div></div><div class="mt-4"><label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label><input type="text" name="zona_blok_manifestasi_d13" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d13" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.14')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div><label class="block text-xs font-bold text-slate-700 mb-2">Jenis Perizinan Berusaha: <span class="text-rose-500">*</span></label><div class="flex flex-wrap gap-6"><label class="inline-flex items-center gap-2 cursor-pointer text-xs"><input type="radio" name="jenis_perizinan_d14" value="eksplorasi" x-bind:required="dataTersediaD10D16 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>PB-PJLPB Tahap Eksplorasi</span></label><label class="inline-flex items-center gap-2 cursor-pointer text-xs"><input type="radio" name="jenis_perizinan_d14" value="eksploitasi" class="text-forest-600 focus:ring-forest-600"><span>PB-PJLPB Tahap Eksploitasi dan Pemanfaatan</span></label></div></div>
                                <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-4"><legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">INFORMASI PEMEGANG IZIN USAHA</legend><div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemegang Izin: <span class="text-rose-500">*</span></label><input type="text" name="nama_pemegang_izin_d14" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Nama pemegang izin usaha" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Berusaha (NIB): <span class="text-rose-500">*</span></label><input type="text" name="nib_d14" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Masukan nomor induk berusaha.." class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Nomor Perizinan: <span class="text-rose-500">*</span></label><input type="text" name="nomor_perizinan_d14" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Nomor surat" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perizinan: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_perizinan_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Area Perizinan: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="luas_area_perizinan_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Ha</span></div></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><input type="file" name="dokumen_perizinan_d14" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 border border-slate-300 bg-white rounded-xl"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Zona/Blok: <span class="text-rose-500">*</span></label><input type="text" name="zona_blok_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">SPTN/SKW: <span class="text-rose-500">*</span></label><input type="text" name="sptn_skw_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Wilayah Kerja: <span class="text-rose-500">*</span></label><input type="text" name="nama_wilayah_kerja_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs"></div></div><div><label class="block text-xs font-bold text-slate-700 mb-2">Status Wilayah Kerja: <span class="text-rose-500">*</span></label><div class="space-y-2"><label class="block text-xs"><input type="radio" name="status_wilayah_kerja_d14" value="panas_bumi" x-bind:required="dataTersediaD10D16 === 'ya'" class="mr-2 text-forest-600">Wilayah Kerja Panas Bumi</label><label class="block text-xs"><input type="radio" name="status_wilayah_kerja_d14" value="penugasan" class="mr-2 text-forest-600">Wilayah Penugasan Survei Pendahuluan dan Eksplorasi</label></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Wilayah Kerja: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="luas_wilayah_kerja_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Ha</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Terbangkit: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" step="0.01" name="kapasitas_terbangkit_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">MW</span></div></div></div><div class="grid grid-cols-1 md:grid-cols-3 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Jml KK Terlayani: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" name="jumlah_kk_terlayani_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">KK</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Jml Tenaga Kerja: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><input type="number" min="0" name="jumlah_tenaga_kerja_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">org</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Nilai Investasi (Rp): <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Rp</span><input type="number" min="0" step="0.01" name="nilai_investasi_d14" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">,00</span></div></div></div></fieldset>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"><h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider mb-3">LOKASI GEOGRAFIS AREAL IZIN</h4><label class="block text-xs font-bold text-slate-700 mb-1">Unggah Polygon Area (shapefile):</label><p class="text-[11px] text-slate-500 mb-2">ESRI Shapefile terdiri dari file .shp, .dbf, .prj, .shx, dan .cpg yang dikompres menjadi ekstensi .Zip</p><input type="file" name="shapefile_d14" accept=".zip" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 border border-slate-300 bg-white rounded-xl"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d14" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.15')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kecelakaan: <span class="text-rose-500">*</span></label>
                                    <div x-data="{ open: false, search: '', selected: '', options: ['Jatuh', 'Terpeleset', 'Hipotermia', 'Edema', 'Tertimpa Batu/Longsoran', 'Tertimpa Bangunan', 'Tertimpa Pohon', 'Lainnya'] }" @click.outside="open = false; search = ''" class="relative w-full md:w-1/2">
                                        <input type="text" name="jenis_kecelakaan_d15" x-model="selected" x-bind:required="dataTersediaD10D16 === 'ya'" x-bind:aria-expanded="open" aria-haspopup="listbox" readonly placeholder="Pilih jenis kecelakaan" @click="open = !open; if (open) $nextTick(() => $refs.accidentSearch.focus())" @keydown.escape="open = false" class="w-full p-3 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white cursor-pointer">
                                        <span class="pointer-events-none absolute right-4 top-1/2 h-2 w-2 -translate-y-1/2 rotate-45 border-b-2 border-r-2 border-slate-400"></span>
                                        <div x-cloak x-show="open" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-slate-300 bg-white shadow-lg">
                                            <input type="search" x-ref="accidentSearch" x-model="search" placeholder="Cari jenis kecelakaan..." class="w-full border-0 border-b border-slate-200 p-3 text-xs text-slate-800 focus:outline-none focus:ring-0">
                                            <div role="listbox" class="max-h-64 overflow-y-auto">
                                                <template x-for="option in options.filter((item) => item.toLowerCase().includes(search.toLowerCase()))" :key="option">
                                                    <button type="button" role="option" @click="selected = option; open = false; search = ''" class="block w-full border-b border-slate-100 px-3 py-2.5 text-left text-xs text-slate-800 hover:bg-blue-600 hover:text-white" x-text="option"></button>
                                                </template>
                                                <p x-show="options.filter((item) => item.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-3 py-3 text-xs text-slate-500">Jenis kecelakaan tidak ditemukan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Kejadian: <span class="text-rose-500">*</span></label><textarea name="lokasi_kejadian_d15" rows="3" x-bind:required="dataTersediaD10D16 === 'ya'" placeholder="Masukan lokasi kejadian kecelakaan wisata alam.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                                <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"><legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">JUMLAH KORBAN</legend><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-slate-700 mb-1">Dalam Negeri: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Jumlah</span><input type="number" min="0" name="korban_dalam_negeri_d15" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Orang</span></div></div><div><label class="block text-xs font-bold text-slate-700 mb-1">Luar Negeri: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Jumlah</span><input type="number" min="0" name="korban_luar_negeri_d15" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Orang</span></div></div></div></fieldset>
                                @foreach ([['kategori', 'KATEGORI KECELAKAAN', ['Ringan', 'Sedang', 'Berat']], ['luka_ringan', 'KORBAN LUKA RINGAN', ['Dalam Negeri', 'Luar Negeri']], ['luka_berat', 'KORBAN LUKA BERAT', ['Dalam Negeri', 'Luar Negeri']], ['meninggal', 'KORBAN MENINGGAL', ['Dalam Negeri', 'Luar Negeri']]] as $kelompokKorban)
                                    <fieldset class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"><legend class="px-2 font-extrabold text-xs text-slate-800 uppercase">{{ $kelompokKorban[1] }}</legend><div class="grid grid-cols-1 {{ count($kelompokKorban[2]) === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2' }} gap-4">@foreach ($kelompokKorban[2] as $jenisKorban)@php($fieldKorban = strtolower(str_replace(' ', '_', $jenisKorban)))<div><label class="block text-xs font-bold text-slate-700 mb-1">{{ $jenisKorban }}: <span class="text-rose-500">*</span></label><div class="flex items-center bg-white border border-slate-300 rounded-xl overflow-hidden"><span class="px-3 text-xs text-slate-500 border-r border-slate-300">Jumlah</span><input type="number" min="0" name="{{ $kelompokKorban[0] }}_{{ $fieldKorban }}_d15" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-transparent text-xs"><span class="px-3 text-xs text-slate-500 border-l border-slate-300">Orang</span></div></div>@endforeach</div></fieldset>
                                @endforeach
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d15" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif

                        @if ($kodeSubBidang === 'D.16')
                            <div x-show="dataTersediaD10D16 === 'ya'" x-transition class="mt-5 space-y-5">
                                <div><label class="block text-xs font-bold text-slate-700 mb-2">Jenis Publikasi:</label><div class="space-y-3">@foreach (['Publikasi Elektronik/Digital', 'Publikasi Cetak', 'Pameran', 'Kampanye', 'Lain-lain'] as $jenisPublikasi)<label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700"><input type="radio" name="jenis_publikasi_d16" value="{{ $jenisPublikasi }}" x-bind:required="dataTersediaD10D16 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>{{ $jenisPublikasi }}</span></label>@endforeach</div></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Judul Publikasi: <span class="text-rose-500">*</span></label><input type="text" name="judul_publikasi_d16" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Penyelenggara Kegiatan: <span class="text-rose-500">*</span></label><input type="text" name="penyelenggara_kegiatan_d16" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Sumber Pembiayaan: <span class="text-rose-500">*</span></label><input type="text" name="sumber_pembiayaan_d16" x-bind:required="dataTersediaD10D16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></div>
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_d16" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:bg-white"></textarea></div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div x-cloak x-show="selectedSubBidangKode === 'C.02'" x-transition class="space-y-6 pt-2">
                    <div class="md:w-1/2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_c02" x-bind:required="selectedSubBidangKode === 'C.02'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c02" x-bind:required="selectedSubBidangKode === 'C.02'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada data Lembaga Konservasi yang belum terdata?</label>
                        <p class="text-[11px] text-slate-500">Pilih ya, ada jika belum tersedia data lembaga konservasi</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_lembaga_c02" value="ya" x-model="adaDataLembagaC02" x-bind:required="selectedSubBidangKode === 'C.02'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_lembaga_c02" value="tidak" x-model="adaDataLembagaC02" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataLembagaC02 === 'ya'" x-transition x-init="$watch('adaDataLembagaC02', value => { if (value === 'ya') $nextTick(() => window.initC02Map()) })" class="space-y-6 pt-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-700 mb-1">Nama Lembaga Konservasi: <span class="text-rose-500">*</span></label><input type="text" name="nama_lembaga_c02" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bentuk Lembaga: <span class="text-rose-500">*</span></label>
                                <select name="bentuk_lembaga_c02" x-model="bentukLembagaC02" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                    <option value="">Pilih bentuk lembaga</option>
                                    <option value="Pusat Penyelamatan Satwa">Pusat Penyelamatan Satwa</option>
                                    <option value="Pusat Latihan Satwa Khusus">Pusat Latihan Satwa Khusus</option>
                                    <option value="Pusat Rehabilitasi Satwa">Pusat Rehabilitasi Satwa</option>
                                    <option value="Kebun Binatang">Kebun Binatang</option>
                                    <option value="Taman Safari">Taman Safari</option>
                                    <option value="Taman Satwa">Taman Satwa</option>
                                    <option value="Taman Satwa Khusus">Taman Satwa Khusus</option>
                                    <option value="Museum Zoologi">Museum Zoologi</option>
                                    <option value="Kebun Botani">Kebun Botani</option>
                                    <option value="Taman Tumbuhan Khusus">Taman Tumbuhan Khusus</option>
                                    <option value="Herbarium">Herbarium</option>
                                </select>
                            </div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK/LOKASI GEOGRAFIS LEMBAGA KONSERVASI (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c02" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">X</span><input id="latitude-c02" type="number" step="any" name="latitude_c02" placeholder="-0.897123" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">Y</span><input id="longitude-c02" type="number" step="any" name="longitude_c02" placeholder="119.87123" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lembaga Konservasi: <span class="text-rose-500">*</span></label><textarea name="alamat_lembaga_c02" rows="4" x-bind:required="adaDataLembagaC02 === 'ya'" placeholder="Alamat lembaga konservasi.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-700 mb-1">Nomor Dokumen Perizinan: <span class="text-rose-500">*</span></label><input type="text" name="nomor_dokumen_perizinan_c02" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Areal: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" step="any" name="luas_areal_c02" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">Ha</span></div></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perizinan: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_perizinan_c02" x-bind:required="adaDataLembagaC02 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Berakhir Izin:</label><input type="date" name="tanggal_berakhir_izin_c02" x-bind:required="adaDataLembagaC02 === 'ya' && bentukLembagaUmumC02.includes(bentukLembagaC02)" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><p class="mt-2 text-[11px] text-slate-500">Tanggal berakhir izin wajib diisi jika bentuk lembaga konservasi termasuk kategori lembaga konservasi umum</p></div>
                        </div>
<<<<<<< HEAD
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format PDF, maksimal 10 MB per file.</p><input type="file" name="dokumen_perizinan_c02" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
=======
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format file pdf dan maksimal 1 file berukuran 2 Mb</p><input type="file" name="dokumen_perizinan_c02" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
>>>>>>> temp-fix
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c02" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.04'" x-transition class="space-y-6 pt-2">
                    <div class="md:w-1/2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_c04" x-bind:required="selectedSubBidangKode === 'C.04'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c04" x-bind:required="selectedSubBidangKode === 'C.04'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada data Penangkaran yang belum terdata?</label>
                        <p class="text-[11px] text-slate-500">Pilih ya, ada jika belum tersedia data penangkaran</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_penangkaran_c04" value="ya" x-model="adaDataPenangkaranC04" x-bind:required="selectedSubBidangKode === 'C.04'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_penangkaran_c04" value="tidak" x-model="adaDataPenangkaranC04" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataPenangkaranC04 === 'ya'" x-transition x-init="$watch('adaDataPenangkaranC04', value => { if (value === 'ya') $nextTick(() => window.initC04Map()) })" class="space-y-6 pt-2">
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Penangkar: <span class="text-rose-500">*</span></label><input type="text" name="nama_penangkar_c04" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK/LOKASI GEOGRAFIS PENANGKARAN (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c04" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">X</span><input id="latitude-c04" type="number" step="any" name="latitude_c04" placeholder="-0.897123" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">Y</span><input id="longitude-c04" type="number" step="any" name="longitude_c04" placeholder="119.87123" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Alamat Penangkaran: <span class="text-rose-500">*</span></label><textarea name="alamat_penangkaran_c04" rows="4" x-bind:required="adaDataPenangkaranC04 === 'ya'" placeholder="Alamat penangkaran.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Unit Penangkaran:</label><textarea name="lokasi_unit_penangkaran_c04" rows="4" placeholder="Lokasi Unit penangkaran.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-700 mb-1">Nomor Dokumen Perizinan: <span class="text-rose-500">*</span></label><input type="text" name="nomor_dokumen_perizinan_c04" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Areal: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" step="any" name="luas_areal_c04" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">Ha</span></div></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perizinan: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_perizinan_c04" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Berakhir Izin: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_berakhir_izin_c04" x-bind:required="adaDataPenangkaranC04 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        </div>
<<<<<<< HEAD
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format PDF, maksimal 10 MB per file.</p><input type="file" name="dokumen_perizinan_c04" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
=======
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format file pdf dan maksimal 1 file berukuran 2 Mb</p><input type="file" name="dokumen_perizinan_c04" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
>>>>>>> temp-fix
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c04" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.03'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c03" x-bind:required="selectedSubBidangKode === 'C.03'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Semester: <span class="text-rose-500">*</span></label>
                            <select name="periode_semester_c03" x-bind:required="selectedSubBidangKode === 'C.03'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                <option value="Semester I">Semester I</option>
                                <option value="Semester II">Semester II</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c03" x-bind:required="selectedSubBidangKode === 'C.03'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Data Koleksi TSL di Lembaga Konservasi yang belum terdata pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada data koleksi TSL di lembaga konservasi</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_koleksi_c03" value="ya" x-model="adaDataKoleksiC03" x-bind:required="selectedSubBidangKode === 'C.03'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_koleksi_c03" value="tidak" x-model="adaDataKoleksiC03" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataKoleksiC03 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lembaga Konservasi: <span class="text-rose-500">*</span></label>
                            <select name="lembaga_konservasi_c03" x-bind:required="adaDataKoleksiC03 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih lembaga konservasi</option>
                                @foreach ($lembagaKonservasiList as $namaLembaga)
                                    <option value="{{ $namaLembaga }}">{{ $namaLembaga }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies Koleksi: <span class="text-rose-500">*</span></label>
                            <select name="spesies_koleksi_c03" x-model="spesiesKoleksiC03" x-bind:required="adaDataKoleksiC03 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesKoleksiC03 === 'Lainnya'" type="text" name="nama_spesies_lainnya_c03" x-bind:required="adaDataKoleksiC03 === 'ya' && spesiesKoleksiC03 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ([['jantan_c03', 'Jantan'], ['betina_c03', 'Betina'], ['belum_diketahui_c03', 'Belum Diketahui']] as [$fieldJumlahC03, $labelJumlahC03])
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelJumlahC03 }}: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="{{ $fieldJumlahC03 }}" value="0" x-bind:required="adaDataKoleksiC03 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">indv.</span></div>
                                </div>
                            @endforeach
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c03" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.05'" x-transition class="space-y-6 pt-2">
                    <div class="md:w-1/2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_c05" x-bind:required="selectedSubBidangKode === 'C.05'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c05" x-bind:required="selectedSubBidangKode === 'C.05'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Jenis TSL yang Ditangkarkan namun belum terdata pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih ya, ada jika ingin menambahkan data jenis TSL pada penangkar</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_jenis_tsl_c05" value="ya" x-model="adaDataJenisTslC05" x-bind:required="selectedSubBidangKode === 'C.05'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_jenis_tsl_c05" value="tidak" x-model="adaDataJenisTslC05" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataJenisTslC05 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penangkar: <span class="text-rose-500">*</span></label>
                            <select name="penangkar_c05" x-bind:required="adaDataJenisTslC05 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih penangkar</option>
                                @foreach ($penangkarList as $namaPenangkar)
                                    <option value="{{ $namaPenangkar }}">{{ $namaPenangkar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies yang ditangkarkan: <span class="text-rose-500">*</span></label>
                            <select name="spesies_ditangkar_c05" x-model="spesiesDitangkarkanC05" x-bind:required="adaDataJenisTslC05 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesDitangkarkanC05 === 'Lainnya'" type="text" name="nama_spesies_lainnya_c05" x-bind:required="adaDataJenisTslC05 === 'ya' && spesiesDitangkarkanC05 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ([['jumlah_indukan_c05', 'Jumlah Indukan'], ['hasil_penangkaran_c05', 'Hasil Penangkaran'], ['hasil_pemanfaatan_tahun_ini_c05', 'Hasil Pemanfaatan Tahun ini'], ['sisa_stok_hasil_pemanfaatan_c05', 'Sisa Stok Hasil Pemanfaatan']] as [$fieldJumlahC05, $labelJumlahC05])
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelJumlahC05 }}: <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="{{ $fieldJumlahC05 }}" value="0" x-bind:required="adaDataJenisTslC05 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">indv.</span></div>
                                </div>
                            @endforeach
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c05" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.06'" x-transition class="space-y-6 pt-2">
                    <div class="md:w-1/2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                        <select name="tahun_c06" x-bind:required="selectedSubBidangKode === 'C.06'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih tahun</option>
                            @for ($year = 2026; $year >= 1945; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c06" x-bind:required="selectedSubBidangKode === 'C.06'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada data Pengedar TSL yang belum terdata?</label>
                        <p class="text-[11px] text-slate-500">Pilih ya, ada jika belum tersedia data pengedar</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_pengedar_c06" value="ya" x-model="adaDataPengedarC06" x-bind:required="selectedSubBidangKode === 'C.06'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_pengedar_c06" value="tidak" x-model="adaDataPengedarC06" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataPengedarC06 === 'ya'" x-transition x-init="$watch('adaDataPengedarC06', value => { if (value === 'ya') $nextTick(() => window.initC06Map()) })" class="space-y-6 pt-2">
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Nama Pengedar TSL: <span class="text-rose-500">*</span></label><input type="text" name="nama_pengedar_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Kategori: <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" name="kategori_pengedar_c06" value="Dalam Negeri" x-bind:required="adaDataPengedarC06 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span class="text-xs font-medium text-slate-700">Dalam Negeri</span></label>
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" name="kategori_pengedar_c06" value="Luar Negeri" class="text-forest-600 focus:ring-forest-600"><span class="text-xs font-medium text-slate-700">Luar Negeri</span></label>
                            </div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK/LOKASI GEOGRAFIS PENGEDAR TSL (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c06" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">X</span><input id="latitude-c06" type="number" step="any" name="latitude_c06" placeholder="-0.897123" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">Y</span><input id="longitude-c06" type="number" step="any" name="longitude_c06" placeholder="119.87123" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Unit Pengedar TSL:</label><textarea name="lokasi_unit_pengedar_c06" rows="4" placeholder="Lokasi Unit Pengedar TSL.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-700 mb-1">Nomor Dokumen Perizinan: <span class="text-rose-500">*</span></label><input type="text" name="nomor_dokumen_perizinan_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Luas Areal: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" step="any" name="luas_areal_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">Ha</span></div></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perizinan: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_perizinan_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Berakhir Izin: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_berakhir_izin_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        </div>
<<<<<<< HEAD
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format PDF, maksimal 10 MB per file.</p><input type="file" name="dokumen_perizinan_c06" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
=======
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Dokumen Perizinan (pdf):</label><p class="mb-2 text-[11px] text-slate-500">Format file pdf dan maksimal 1 file berukuran 2 Mb</p><input type="file" name="dokumen_perizinan_c06" accept=".pdf,application/pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 bg-white rounded-xl"></div>
>>>>>>> temp-fix
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Spesies yang Diedarkan: <span class="text-rose-500">*</span></label><input type="text" name="spesies_diedarkan_c06" x-bind:required="adaDataPengedarC06 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c06" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.08'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c08" x-bind:required="selectedSubBidangKode === 'C.08'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Triwulan: <span class="text-rose-500">*</span></label>
                            <select name="periode_triwulan_c08" x-bind:required="selectedSubBidangKode === 'C.08'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                <option value="Triwulan I">Triwulan I</option>
                                <option value="Triwulan II">Triwulan II</option>
                                <option value="Triwulan III">Triwulan III</option>
                                <option value="Triwulan IV">Triwulan IV</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c08" x-bind:required="selectedSubBidangKode === 'C.08'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Realisasi Pengambilan/Penangkapan TSL di Satuan Kerja anda?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada realisasi pengambilan/penangkapan tumbuhan dan satwa liar</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_pengambilan_c08" value="ya" x-model="adaRealisasiPengambilanC08" x-bind:required="selectedSubBidangKode === 'C.08'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_pengambilan_c08" value="tidak" x-model="adaRealisasiPengambilanC08" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaRealisasiPengambilanC08 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Species: <span class="text-rose-500">*</span></label>
                            <select name="nama_spesies_c08" x-bind:required="adaRealisasiPengambilanC08 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Jenis: <span class="text-rose-500">*</span></label><input type="text" name="jenis_tsl_c08" x-bind:required="adaRealisasiPengambilanC08 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Provinsi: <span class="text-rose-500">*</span></label><select id="provinsi_c08" name="provinsi_c08" x-bind:required="adaRealisasiPengambilanC08 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih provinsi</option></select></div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-3"><h4 class="text-xs font-extrabold text-slate-800">Kuota Tangkap</h4><button type="button" @click="tampilkanKuotaTangkapC08 = !tampilkanKuotaTangkapC08" class="text-xs font-medium text-blue-600 hover:text-blue-800" x-text="tampilkanKuotaTangkapC08 ? 'Sembunyikan kuota tangkap' : 'Tampilkan kuota tangkap'"></button></div>
                                <div x-cloak x-show="tampilkanKuotaTangkapC08" x-transition class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume:</label><input type="text" readonly placeholder="Belum tersedia" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan:</label><input type="text" readonly placeholder="-" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="text-xs font-extrabold text-slate-800">Realisasi Tangkap</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume: <span class="text-rose-500">*</span></label><input type="number" min="0" step="any" name="realisasi_volume_c08" x-bind:required="adaRealisasiPengambilanC08 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan: <span class="text-rose-500">*</span></label><input type="text" name="realisasi_satuan_c08" x-bind:required="adaRealisasiPengambilanC08 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                </div>
                            </div>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c08" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.09'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c09" x-bind:required="selectedSubBidangKode === 'C.09'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Triwulan: <span class="text-rose-500">*</span></label>
                            <select name="periode_triwulan_c09" x-bind:required="selectedSubBidangKode === 'C.09'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                <option value="Triwulan I">Triwulan I</option>
                                <option value="Triwulan II">Triwulan II</option>
                                <option value="Triwulan III">Triwulan III</option>
                                <option value="Triwulan IV">Triwulan IV</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c09" x-bind:required="selectedSubBidangKode === 'C.09'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Realisasi Ekspor TSL Penangkaran di Satuan Kerja Anda?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada realisasi ekspor tumbuhan dan satwa liar di penangkaran</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_ekspor_c09" value="ya" x-model="adaRealisasiEksporC09" x-bind:required="selectedSubBidangKode === 'C.09'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_ekspor_c09" value="tidak" x-model="adaRealisasiEksporC09" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaRealisasiEksporC09 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penangkar: <span class="text-rose-500">*</span></label>
                            <select name="penangkar_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih nama penangkar</option>
                                @foreach ($penangkarList as $namaPenangkar)
                                    <option value="{{ $namaPenangkar }}">{{ $namaPenangkar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Spesies: <span class="text-rose-500">*</span></label>
                            <select name="nama_spesies_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Jenis: <span class="text-rose-500">*</span></label><input type="text" name="jenis_tsl_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Negara Tujuan: <span class="text-rose-500">*</span></label><select name="negara_tujuan_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih negara tujuan</option>@foreach ($negaraTujuanC09 as $negara)<option value="{{ $negara }}">{{ $negara }}</option>@endforeach</select></div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="text-xs font-extrabold text-slate-800">Kuota Ekspor</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume:</label><input type="text" readonly placeholder="Belum tersedia" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan:</label><input type="text" readonly placeholder="-" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="text-xs font-extrabold text-slate-800">Realisasi Ekspor</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume: <span class="text-rose-500">*</span></label><input type="number" min="0" step="any" name="realisasi_ekspor_volume_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan: <span class="text-rose-500">*</span></label><input type="text" name="realisasi_ekspor_satuan_c09" x-bind:required="adaRealisasiEksporC09 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                </div>
                            </div>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c09" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.10'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c10" x-bind:required="selectedSubBidangKode === 'C.10'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Triwulan: <span class="text-rose-500">*</span></label>
                            <select name="periode_triwulan_c10" x-bind:required="selectedSubBidangKode === 'C.10'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                <option value="Triwulan I">Triwulan I</option>
                                <option value="Triwulan II">Triwulan II</option>
                                <option value="Triwulan III">Triwulan III</option>
                                <option value="Triwulan IV">Triwulan IV</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c10" x-bind:required="selectedSubBidangKode === 'C.10'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Realisasi Ekspor TSL Pengambilan dari Alam di Satuan Kerja Anda?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada realisasi ekspor tumbuhan dan satwa liar hasil pengambilan dari alam</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_ekspor_c10" value="ya" x-model="adaRealisasiEksporC10" x-bind:required="selectedSubBidangKode === 'C.10'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_realisasi_ekspor_c10" value="tidak" x-model="adaRealisasiEksporC10" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaRealisasiEksporC10 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pengedar TSL: <span class="text-rose-500">*</span></label>
                            <select name="pengedar_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih nama pengedar</option>
                                @foreach ($pengedarList as $namaPengedar)
                                    <option value="{{ $namaPengedar }}">{{ $namaPengedar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Spesies: <span class="text-rose-500">*</span></label>
                            <select name="nama_spesies_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Jenis: <span class="text-rose-500">*</span></label><input type="text" name="jenis_tsl_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Negara Tujuan: <span class="text-rose-500">*</span></label><select name="negara_tujuan_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"><option value="">Pilih negara tujuan</option>@foreach ($negaraTujuanC09 as $negara)<option value="{{ $negara }}">{{ $negara }}</option>@endforeach</select></div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="text-xs font-extrabold text-slate-800">Kuota Ekspor</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume:</label><input type="text" readonly placeholder="Belum tersedia" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan:</label><input type="text" readonly placeholder="-" class="w-full rounded-xl border border-slate-300 bg-slate-100 p-3 text-xs text-slate-500"></div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="text-xs font-extrabold text-slate-800">Realisasi Ekspor</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Volume: <span class="text-rose-500">*</span></label><input type="number" min="0" step="any" name="realisasi_ekspor_volume_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Satuan: <span class="text-rose-500">*</span></label><input type="text" name="realisasi_ekspor_satuan_c10" x-bind:required="adaRealisasiEksporC10 === 'ya'" class="w-full p-3 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:outline-none"></div>
                                </div>
                            </div>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c10" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.11'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c11" x-bind:required="selectedSubBidangKode === 'C.11'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c11" x-bind:required="selectedSubBidangKode === 'C.11'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c11" x-bind:required="selectedSubBidangKode === 'C.11'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Sitaan/Penyerahan/Penyelamatan Satwa pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada sitaan/penyerahan/penyelamatan satwa</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_sitaan_c11" value="ya" x-model="adaDataSitaanC11" x-bind:required="selectedSubBidangKode === 'C.11'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_data_sitaan_c11" value="tidak" x-model="adaDataSitaanC11" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaDataSitaanC11 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c11" x-bind:required="adaDataSitaanC11 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Asal-usul: <span class="text-rose-500">*</span></label>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                                @foreach (['Hasil Operasi', 'Hasil Penyerahan', 'Hasil Penyelamatan', 'Hasil Tegahan'] as $asalUsulC11)
                                    <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" name="asal_usul_c11" value="{{ $asalUsulC11 }}" x-bind:required="adaDataSitaanC11 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span class="text-xs font-medium text-slate-700">{{ $asalUsulC11 }}</span></label>
                                @endforeach
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ([['jantan_c11', 'Jantan'], ['betina_c11', 'Betina'], ['belum_diketahui_c11', 'Belum Diketahui']] as [$fieldJumlahC11, $labelJumlahC11])
                                <div><label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelJumlahC11 }}: <span class="text-rose-500">*</span></label><input type="number" min="0" name="{{ $fieldJumlahC11 }}" x-bind:required="adaDataSitaanC11 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            @endforeach
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c11" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.12'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c12" x-bind:required="selectedSubBidangKode === 'C.12'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c12" x-bind:required="selectedSubBidangKode === 'C.12'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c12" x-bind:required="selectedSubBidangKode === 'C.12'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada PNB dari kegiatan pemanfaatan TSL pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada penerimaan PNBP pemanfaatan TSL</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_pnb_tsl_c12" value="ya" x-model="adaPnbTslC12" x-bind:required="selectedSubBidangKode === 'C.12'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_pnb_tsl_c12" value="tidak" x-model="adaPnbTslC12" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaPnbTslC12 === 'ya'" x-transition class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Akun PNB: <span class="text-rose-500">*</span></label>
                            <select name="akun_pnb_c12" x-bind:required="adaPnbTslC12 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih Akun PNB</option>
                                <option value="PNBP Pemanfaatan TSL">PNBP Pemanfaatan TSL</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="max-w-md">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi: <span class="text-rose-500">*</span></label>
                            <div class="flex items-center overflow-hidden rounded-xl border border-slate-300 bg-slate-50">
                                <span class="border-r border-slate-300 px-3 py-3 text-xs text-slate-500">Rp</span>
                                <input type="number" min="0" step="1" name="realisasi_pnb_c12" x-bind:required="adaPnbTslC12 === 'ya'" class="w-full bg-transparent p-3 text-xs text-slate-800 focus:outline-none">
                                <span class="border-l border-slate-300 px-3 py-3 text-xs text-slate-500">,00</span>
                            </div>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c12" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.14'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c14" x-bind:required="selectedSubBidangKode === 'C.14'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c14" x-bind:required="selectedSubBidangKode === 'C.14'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c14" x-bind:required="selectedSubBidangKode === 'C.14'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada kejadian Interaksi Negatif Satwa Liar dan Manusia pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada kejadian Interaksi Negatif Satwa Liar dan Manusia</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_interaksi_negatif_c14" value="ya" x-model="adaInteraksiNegatifC14" x-bind:required="selectedSubBidangKode === 'C.14'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_interaksi_negatif_c14" value="tidak" x-model="adaInteraksiNegatifC14" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaInteraksiNegatifC14 === 'ya'" x-transition x-init="$watch('adaInteraksiNegatifC14', value => { if (value === 'ya') $nextTick(() => window.initC14Map()) })" class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies yang terlibat Interaksi Negatif: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c14" x-model="spesiesInteraksiC14" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesInteraksiC14 === 'Lainnya'" type="text" name="spesies_lainnya_c14" x-bind:required="adaInteraksiNegatifC14 === 'ya' && spesiesInteraksiC14 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kejadian: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_kejadian_c14" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Jml Individu: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="1" name="jumlah_individu_c14" value="1" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">indv.</span></div></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Apakah Satwa Mati?</label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="satwa_mati_c14" value="0" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">indv.</span></div></div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK/LOKASI GEOGRAFIS KEJADIAN (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c14" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">X</span><input id="latitude-c14" type="number" step="any" name="latitude_c14" placeholder="-0.897123" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">Y</span><input id="longitude-c14" type="number" step="any" name="longitude_c14" placeholder="119.87123" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <fieldset class="rounded-xl border border-slate-200 p-4">
                                <legend class="px-2 text-xs font-extrabold text-slate-500">Korban Manusia</legend>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ([['meninggal_c14', 'Meninggal', 'org'], ['cedera_c14', 'Cedera', 'org']] as [$fieldKorbanC14, $labelKorbanC14, $unitKorbanC14])
                                        <div><label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelKorbanC14 }}: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="{{ $fieldKorbanC14 }}" value="0" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">{{ $unitKorbanC14 }}</span></div></div>
                                    @endforeach
                                </div>
                            </fieldset>
                            <fieldset class="rounded-xl border border-slate-200 p-4">
                                <legend class="px-2 text-xs font-extrabold text-slate-500">Kerusakan Kebun/Lahan &amp; Bangunan</legend>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Kebun/Lahan: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" step="any" name="kerusakan_kebun_c14" value="0" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">m²</span></div></div>
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">Bangunan: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="kerusakan_bangunan_c14" value="0" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">unit</span></div></div>
                                </div>
                            </fieldset>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-500">Korban Hewan Ternak</legend>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach ([['kambing_c14', 'Kambing'], ['sapi_c14', 'Sapi'], ['kerbau_c14', 'Kerbau'], ['anjing_c14', 'Anjing'], ['babi_c14', 'Babi'], ['unggas_c14', 'Unggas']] as [$fieldTernakC14, $labelTernakC14])
                                    <div><label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelTernakC14 }}: <span class="text-rose-500">*</span></label><div class="flex items-center rounded-xl border border-slate-300 bg-slate-50"><input type="number" min="0" name="{{ $fieldTernakC14 }}" value="0" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 text-xs text-slate-500">indv.</span></div></div>
                                @endforeach
                            </div>
                        </fieldset>
                        <div class="max-w-md"><label class="block text-xs font-bold text-slate-700 mb-1">Taksiran Kerugian: <span class="text-rose-500">*</span></label><div class="flex items-center overflow-hidden rounded-xl border border-slate-300 bg-slate-50"><span class="border-r border-slate-300 px-3 py-3 text-xs text-slate-500">Rp</span><input type="number" min="0" step="1" name="taksiran_kerugian_c14" x-bind:required="adaInteraksiNegatifC14 === 'ya'" class="w-full bg-transparent p-3 text-xs text-slate-800 focus:outline-none"><span class="border-l border-slate-300 px-3 py-3 text-xs text-slate-500">,00</span></div></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Upaya Penanggulangan:</label><textarea name="upaya_penanggulangan_c14" rows="4" placeholder="Upaya penanggulangan yang telah dan akan dilakukan.." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c14" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.15'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c15" x-bind:required="selectedSubBidangKode === 'C.15'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c15" x-bind:required="selectedSubBidangKode === 'C.15'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c15" x-bind:required="selectedSubBidangKode === 'C.15'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Kegiatan Pelepasliaran Satwa pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada kegiatan pelepasliaran satwa</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_pelepasan_satwa_c15" value="ya" x-model="adaPelepasliaranC15" x-bind:required="selectedSubBidangKode === 'C.15'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_pelepasan_satwa_c15" value="tidak" x-model="adaPelepasliaranC15" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaPelepasliaranC15 === 'ya'" x-transition x-init="$watch('selectedSubBidangKode', code => { if (code === 'C.15') $nextTick(() => window.initC15Map()) }); $watch('adaPelepasliaranC15', value => { if (value === 'ya') $nextTick(() => window.initC15Map()) })" class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies yang dilepasliarkan: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c15" x-model="spesiesPelepasliaranC15" x-bind:required="adaPelepasliaranC15 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesPelepasliaranC15 === 'Lainnya'" type="text" name="spesies_lainnya_c15" x-bind:required="adaPelepasliaranC15 === 'ya' && spesiesPelepasliaranC15 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="max-w-md">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Individu Satwa: <span class="text-rose-500">*</span></label>
                            <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50">
                                <input type="number" min="1" name="jumlah_individu_c15" value="1" x-bind:required="adaPelepasliaranC15 === 'ya'" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none">
                                <span class="border-l border-slate-300 px-3 text-xs text-slate-500">Indv.</span>
                            </div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">Asal-usul: <span class="text-rose-500">*</span></legend>
                            <div class="space-y-2">
                                @foreach (['Hasil Penyerahan Masyarakat', 'Hasil Sitaan', 'Hasil Penyelamatan Satwa', 'Hasil Operasi atau Razia', 'Hasil Rehabilitasi', 'Hasil Penangkaran (Reintroduksi)', 'Hasil Interaksi Negatif Satwa Liar dan Manusia', 'Hasil Translokasi Habitat', 'Hasil Pengembalian dari Kebun Binatang atau Sirkus', 'Lainnya'] as $asalUsulPelepasliaran)
                                    <label class="flex items-center gap-2 text-xs font-medium text-slate-700">
                                        <input type="radio" name="asal_usul_c15" value="{{ $asalUsulPelepasliaran }}" x-bind:required="adaPelepasliaranC15 === 'ya'" class="text-forest-600 focus:ring-forest-600">
                                        <span>{{ $asalUsulPelepasliaran }}</span>
                                        <i class="fa-solid fa-circle-info text-slate-400" title="Pilih sumber asal satwa yang dilepasliarkan"></i>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK GEOGRAFIS PELEPASLIARAN SATWA (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c15" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">X</span><input id="longitude-c15" type="number" step="any" name="longitude_c15" placeholder="119.87123" x-bind:required="adaPelepasliaranC15 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">Y</span><input id="latitude-c15" type="number" step="any" name="latitude_c15" placeholder="-0.897123" x-bind:required="adaPelepasliaranC15 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c15" rows="4" placeholder="Masukkan informasi lainnya" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-600"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.16'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c16" x-bind:required="selectedSubBidangKode === 'C.16'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c16" x-bind:required="selectedSubBidangKode === 'C.16'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c16" x-bind:required="selectedSubBidangKode === 'C.16'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Kelahiran Satwa pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada kelahiran satwa</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_kelahiran_satwa_c16" value="ya" x-model="adaKelahiranC16" x-bind:required="selectedSubBidangKode === 'C.16'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_kelahiran_satwa_c16" value="tidak" x-model="adaKelahiranC16" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaKelahiranC16 === 'ya'" x-transition x-init="$watch('selectedSubBidangKode', code => { if (code === 'C.16') $nextTick(() => window.initC16Map()) }); $watch('adaKelahiranC16', value => { if (value === 'ya') $nextTick(() => window.initC16Map()) })" class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies Satwa: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c16" x-model="spesiesKelahiranC16" x-bind:required="adaKelahiranC16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesKelahiranC16 === 'Lainnya'" type="text" name="spesies_lainnya_c16" x-bind:required="adaKelahiranC16 === 'ya' && spesiesKelahiranC16 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kelahiran: <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_kelahiran_c16" x-bind:required="adaKelahiranC16 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Usulan Nama Satwa:</label>
                                <input type="text" name="usulan_nama_satwa_c16" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ([['jantan_c16', 'Jantan'], ['betina_c16', 'Betina'], ['belum_diketahui_c16', 'Belum Diketahui']] as [$fieldJumlahC16, $labelJumlahC16])
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelJumlahC16 }}:</label>
                                    <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50">
                                        <input type="number" min="0" name="{{ $fieldJumlahC16 }}" value="0" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none">
                                        <span class="border-l border-slate-300 px-3 text-xs text-slate-500">Indv.</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK GEOGRAFIS KELAHIRAN SATWA (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c16" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">X</span><input id="longitude-c16" type="number" step="any" name="longitude_c16" placeholder="119.87123" x-bind:required="adaKelahiranC16 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">Y</span><input id="latitude-c16" type="number" step="any" name="latitude_c16" placeholder="-0.897123" x-bind:required="adaKelahiranC16 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c16" rows="4" placeholder="Masukkan informasi lainnya" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-600"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.17'" x-transition class="space-y-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c17" x-bind:required="selectedSubBidangKode === 'C.17'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c17" x-bind:required="selectedSubBidangKode === 'C.17'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c17" x-bind:required="selectedSubBidangKode === 'C.17'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Kematian Satwa Liar pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada kematian satwa liar</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_kematian_satwa_c17" value="ya" x-model="adaKematianC17" x-bind:required="selectedSubBidangKode === 'C.17'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="ada_kematian_satwa_c17" value="tidak" x-model="adaKematianC17" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="adaKematianC17 === 'ya'" x-transition x-init="$watch('selectedSubBidangKode', code => { if (code === 'C.17') $nextTick(() => window.initC17Map()) }); $watch('adaKematianC17', value => { if (value === 'ya') $nextTick(() => window.initC17Map()) })" class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies Satwa: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c17" x-model="spesiesKematianC17" x-bind:required="adaKematianC17 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                @foreach ($spesiesKoleksiC03 as $namaSpesies)
                                    <option value="{{ $namaSpesies }}">{{ $namaSpesies }}</option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesKematianC17 === 'Lainnya'" type="text" name="spesies_lainnya_c17" x-bind:required="adaKematianC17 === 'ya' && spesiesKematianC17 === 'Lainnya'" placeholder="Masukkan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kematian: <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_kematian_c17" x-bind:required="adaKematianC17 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Perkiraan Usia: <span class="text-rose-500">*</span></label>
                                <select name="perkiraan_usia_c17" x-bind:required="adaKematianC17 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                    <option value="">Pilih perkiraan usia</option>
                                    <option value="Anakan">Anakan</option>
                                    <option value="Remaja">Remaja</option>
                                    <option value="Dewasa">Dewasa</option>
                                    <option value="Tidak diketahui">Tidak diketahui</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Penyebab Kematian: <span class="text-rose-500">*</span></label>
                                <select name="penyebab_kematian_c17" x-model="penyebabKematianC17" x-bind:required="adaKematianC17 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                    <option value="">Pilih penyebab kematian</option>
                                    <option value="Sakit/Penyakit">Sakit/Penyakit</option>
                                    <option value="Kecelakaan">Kecelakaan</option>
                                    <option value="Konflik dengan manusia">Konflik dengan manusia</option>
                                    <option value="Perburuan">Perburuan</option>
                                    <option value="Keracunan">Keracunan</option>
                                    <option value="Penyebab alami">Penyebab alami</option>
                                    <option value="Tidak diketahui">Tidak diketahui</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <input x-cloak x-show="penyebabKematianC17 === 'Lainnya'" type="text" name="penyebab_kematian_lainnya_c17" x-bind:required="adaKematianC17 === 'ya' && penyebabKematianC17 === 'Lainnya'" placeholder="Masukkan penyebab kematian" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            </div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">Jumlah Individu Satwa Liar yang Mati Berdasarkan Jenis Kelamin: <span class="text-rose-500">*</span></legend>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                @foreach ([['jantan_c17', 'Jantan'], ['betina_c17', 'Betina'], ['belum_diketahui_c17', 'Belum Diketahui']] as [$fieldJumlahC17, $labelJumlahC17])
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ $labelJumlahC17 }}:</label>
                                        <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50">
                                            <input type="number" min="0" name="{{ $fieldJumlahC17 }}" value="0" class="w-full rounded-l-xl bg-transparent p-3 text-xs text-slate-800 focus:outline-none">
                                            <span class="border-l border-slate-300 px-3 text-xs text-slate-500">Indv.</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Upaya Penanganan:</label>
                            <textarea name="upaya_penanganan_c17" rows="4" placeholder="Upaya penanganan yang telah dan akan dilakukan" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK GEOGRAFIS KEMATIAN SATWA LIAR (XY): <span class="text-rose-500">*</span></legend>
                            <div id="map-c17" class="h-80 w-full rounded-lg border border-slate-200"></div>
                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">X</span><input id="longitude-c17" type="number" step="any" name="longitude_c17" placeholder="119.87123" x-bind:required="adaKematianC17 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="border-r border-slate-200 px-3 py-3 text-xs text-slate-500">Y</span><input id="latitude-c17" type="number" step="any" name="latitude_c17" placeholder="-0.897123" x-bind:required="adaKematianC17 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c17" rows="4" placeholder="Masukkan informasi lainnya" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-600"></textarea></div>
                    </div>
                </div>

                <div x-cloak x-show="selectedSubBidangKode === 'C.01'" x-transition class="space-y-6 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kerja: <span class="text-rose-500">*</span></label>
                        <select name="satuan_kerja_c01" x-bind:required="selectedSubBidangKode === 'C.01'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                            <option value="">Pilih satuan kerja</option>
                            <option value="Balai KSDA Sulawesi Tengah">Balai KSDA Sulawesi Tengah</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700">Ada Temuan Perjumpaan Spesies pada Periode tersebut?</label>
                        <p class="text-[11px] text-slate-500">Pilih tidak ada jika tidak ada kegiatan/temuan perjumpaan spesies</p>
                        <div class="mt-4 flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="temuan_perjumpaan_spesies_c01" value="ya" x-model="temuanPerjumpaanSpesiesC01" x-bind:required="selectedSubBidangKode === 'C.01'" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Ya, ada</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="temuan_perjumpaan_spesies_c01" value="tidak" x-model="temuanPerjumpaanSpesiesC01" class="text-forest-600 focus:ring-forest-600">
                                <span class="text-xs font-medium text-slate-700">Tidak ada (Nihil)</span>
                            </label>
                        </div>
                    </div>
                    <div x-cloak x-show="temuanPerjumpaanSpesiesC01 === 'ya'" x-transition x-init="$watch('temuanPerjumpaanSpesiesC01', value => { if (value === 'ya') $nextTick(() => window.initC01Map()) })" class="space-y-6 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Spesies yang dijumpai: <span class="text-rose-500">*</span></label>
                            <select name="spesies_c01" x-model="spesiesC01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih spesies</option>
                                <option>Babirusa</option><option>Maleo</option><option>Anoa</option>
                                <option>Yaki (Monyet Sulawesi)</option><option>Tarsius</option><option>Rangkong Sulawesi</option>
                                <option>Kuskus</option><option>Buaya muara</option><option>Anggrek alam</option><option>Lainnya</option>
                            </select>
                            <input x-cloak x-show="spesiesC01 === 'Lainnya'" type="text" name="spesies_lainnya_c01" x-model="spesiesLainnyaC01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya' && spesiesC01 === 'Lainnya'" placeholder="Tuliskan nama spesies" class="mt-3 w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Jml Ind. yang dijumpai: <span class="text-rose-500">*</span></label><input type="number" min="1" name="jumlah_individu_c01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                            <div><label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Perjumpaan: <span class="text-rose-500">*</span></label><input type="date" name="tanggal_perjumpaan_c01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></div>
                        </div>
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-xs font-extrabold text-slate-800">TITIK GEOGRAFIS PERJUMPAAN SPESIES (XY): <span class="text-rose-500">*</span></legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">X</span><input id="latitude-c01" type="number" step="any" name="latitude_c01" placeholder="-0.897123" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                                <label class="flex items-center rounded-lg border border-slate-300 bg-white"><span class="px-3 py-3 border-r border-slate-200 text-xs text-slate-500">Y</span><input id="longitude-c01" type="number" step="any" name="longitude_c01" placeholder="119.87123" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="w-full rounded-r-lg border-0 p-3 text-xs focus:ring-2 focus:ring-forest-600"></label>
                            </div>
                            <div id="map-c01" class="h-80 w-full rounded-lg border border-slate-200"></div>
                        </fieldset>
                        <fieldset class="rounded-xl border border-slate-200 p-4 md:p-6">
                            <legend class="px-2 text-xs font-extrabold uppercase text-slate-800">Kategori Perjumpaan Spesies (XY): <span class="text-rose-500">*</span></legend>
                            @foreach ($kategoriPerjumpaanC01 as $kelompokKategori => $opsiKategori)
                                <div class="mb-5 last:mb-0">
                                    <h4 class="mb-2 text-xs font-semibold text-slate-500">{{ $kelompokKategori }}</h4>
                                    <div class="space-y-2">
                                        @foreach ($opsiKategori as [$namaKategori, $jumlahKategori])
                                            <label class="flex items-center justify-between gap-4 rounded-md px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">
                                                <span class="flex items-center gap-3"><input type="radio" name="kategori_perjumpaan_c01" value="{{ $namaKategori }}" x-model="kategoriPerjumpaanC01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'ya'" class="text-forest-600 focus:ring-forest-600"><span>{{ $namaKategori }}</span></span>
                                                <span class="shrink-0 text-slate-600">{{ $jumlahKategori }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>
                        <div><label class="block text-xs font-bold text-slate-700 mb-1">Keterangan:</label><textarea name="keterangan_c01" rows="4" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none"></textarea></div>
                    </div>
                    <div x-cloak x-show="temuanPerjumpaanSpesiesC01 === 'tidak'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun: <span class="text-rose-500">*</span></label>
                            <select name="tahun_c01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'tidak'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih tahun</option>
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Bulan: <span class="text-rose-500">*</span></label>
                            <select name="bulan_c01" x-bind:required="temuanPerjumpaanSpesiesC01 === 'tidak'" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                <option value="">Pilih periode</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- FORM DINAMIS SUB-BIDANG B.01: Kelompok Binaan                             -->
                <!-- ========================================================================= -->
                <div x-cloak x-show="selectedSubBidangKode === 'B.01'" x-transition class="space-y-6 pt-2">
                    
                    <!-- 1. TAHUN & PERIODE SEMESTER -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Tahun: <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_b01" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-forest-600 focus:bg-white focus:outline-none">
                                @for ($year = 2026; $year >= 1945; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
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

<<<<<<< HEAD
                <div x-show="selectedSubBidangKode !== 'C.01' && selectedSubBidangKode !== 'C.02' && selectedSubBidangKode !== 'C.03' && selectedSubBidangKode !== 'C.04' && selectedSubBidangKode !== 'C.05' && selectedSubBidangKode !== 'C.06' && selectedSubBidangKode !== 'C.08' && selectedSubBidangKode !== 'C.09' && selectedSubBidangKode !== 'C.10' && selectedSubBidangKode !== 'C.11' && selectedSubBidangKode !== 'C.12' && selectedSubBidangKode !== 'C.14' && selectedSubBidangKode !== 'C.15' && selectedSubBidangKode !== 'C.16' && selectedSubBidangKode !== 'C.17'">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Keterangan:</label>
                    <textarea name="keterangan" rows="3" placeholder="Masukan disini untuk informasi lainnya" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-forest-600 outline-none text-xs font-medium"></textarea>
=======
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
                                    <p class="text-[11px] text-slate-500 mb-2">Format PDF, maksimal 10 MB per file.</p>
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
>>>>>>> 23d71526497aa8bc1353a93efea120528a1f5a59
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
        document.addEventListener('DOMContentLoaded', () => {
            window.setTimeout(() => {
        window.initC01Map = function() {
            const mapElement = document.getElementById('map-c01');
            if (!mapElement || !window.L) return;

            if (window.c01Map) {
                window.c01Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c01');
            const longitudeInput = document.getElementById('longitude-c01');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c01Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c01Map);

            let marker = null;
            window.c01Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c01Map);
            });

            window.setTimeout(() => window.c01Map.invalidateSize(), 100);
        };

        window.initC02Map = function() {
            const mapElement = document.getElementById('map-c02');
            if (!mapElement || !window.L) return;

            if (window.c02Map) {
                window.c02Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c02');
            const longitudeInput = document.getElementById('longitude-c02');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c02Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c02Map);

            let marker = null;
            window.c02Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c02Map);
            });

            window.setTimeout(() => window.c02Map.invalidateSize(), 100);
        };

        window.initC04Map = function() {
            const mapElement = document.getElementById('map-c04');
            if (!mapElement || !window.L) return;

            if (window.c04Map) {
                window.c04Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c04');
            const longitudeInput = document.getElementById('longitude-c04');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c04Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c04Map);

            let marker = null;
            window.c04Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c04Map);
            });

            window.setTimeout(() => window.c04Map.invalidateSize(), 100);
        };

        window.initC06Map = function() {
            const mapElement = document.getElementById('map-c06');
            if (!mapElement || !window.L) return;

            if (window.c06Map) {
                window.c06Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c06');
            const longitudeInput = document.getElementById('longitude-c06');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c06Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c06Map);

            let marker = null;
            window.c06Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c06Map);
            });

            window.setTimeout(() => window.c06Map.invalidateSize(), 100);
        };

        window.initC14Map = function() {
            const mapElement = document.getElementById('map-c14');
            if (!mapElement || !window.L) return;

            if (window.c14Map) {
                window.c14Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c14');
            const longitudeInput = document.getElementById('longitude-c14');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c14Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c14Map);

            let marker = null;
            window.c14Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c14Map);
            });

            window.setTimeout(() => window.c14Map.invalidateSize(), 100);
        };

        window.initC15Map = function() {
            const mapElement = document.getElementById('map-c15');
            if (!mapElement || !window.L) return;

            if (window.c15Map) {
                window.c15Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c15');
            const longitudeInput = document.getElementById('longitude-c15');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c15Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c15Map);

            let marker = null;
            window.c15Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c15Map);
            });

            window.setTimeout(() => window.c15Map.invalidateSize(), 100);
        };

        window.initC16Map = function() {
            const mapElement = document.getElementById('map-c16');
            if (!mapElement || !window.L) return;

            if (window.c16Map) {
                window.c16Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c16');
            const longitudeInput = document.getElementById('longitude-c16');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c16Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c16Map);

            let marker = null;
            window.c16Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c16Map);
            });

            window.setTimeout(() => window.c16Map.invalidateSize(), 100);
        };

        window.initC17Map = function() {
            const mapElement = document.getElementById('map-c17');
            if (!mapElement || !window.L) return;

            if (window.c17Map) {
                window.c17Map.invalidateSize();
                return;
            }

            const latitudeInput = document.getElementById('latitude-c17');
            const longitudeInput = document.getElementById('longitude-c17');
            const parsedLatitude = Number(latitudeInput.value);
            const parsedLongitude = Number(longitudeInput.value);
            const initialLatitude = Number.isFinite(parsedLatitude) && latitudeInput.value !== '' ? parsedLatitude : -0.9;
            const initialLongitude = Number.isFinite(parsedLongitude) && longitudeInput.value !== '' ? parsedLongitude : 119.87;
            window.c17Map = L.map(mapElement).setView([initialLatitude, initialLongitude], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(window.c17Map);

            let marker = null;
            window.c17Map.on('click', event => {
                const { lat, lng } = event.latlng;
                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);
                latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                if (marker) marker.setLatLng(event.latlng);
                else marker = L.marker(event.latlng).addTo(window.c17Map);
            });

            window.setTimeout(() => window.c17Map.invalidateSize(), 100);
        };

        const conservationForm = document.querySelector('form');
        const disabledBeforePanelHidden = new WeakMap();

        function syncHiddenPanelControls() {
            conservationForm.querySelectorAll('input, select, textarea, button').forEach(control => {
                let ancestor = control.parentElement;
                let panelHidden = false;

                while (ancestor && ancestor !== conservationForm) {
                    if (ancestor.hasAttribute('x-cloak') || (ancestor.hasAttribute('x-show') && getComputedStyle(ancestor).display === 'none')) {
                        panelHidden = true;
                        break;
                    }
                    ancestor = ancestor.parentElement;
                }

                if (panelHidden) {
                    if (!disabledBeforePanelHidden.has(control)) {
                        disabledBeforePanelHidden.set(control, control.disabled);
                    }
                    control.disabled = true;
                } else if (disabledBeforePanelHidden.has(control)) {
                    control.disabled = disabledBeforePanelHidden.get(control);
                    disabledBeforePanelHidden.delete(control);
                }
            });
        }

        const hiddenPanelObserver = new MutationObserver(syncHiddenPanelControls);
        conservationForm.querySelectorAll('[x-show], [x-cloak]').forEach(panel => {
            hiddenPanelObserver.observe(panel, { attributes: true, attributeFilter: ['style', 'x-cloak'] });
        });
        syncHiddenPanelControls();

        // --- 1. SCRIPT FOR SUB-BIDANG ---
        const subBidangUrlTemplate = @json(route('konservasi.sub-bidang', ['bidang_id' => '__BIDANG_ID__']), JSON_UNESCAPED_SLASHES);

        document.getElementById('bidang_select').addEventListener('change', function() {
            let bidangId = this.value;
            let subSelect = document.getElementById('sub_bidang_select');
            
            let alpineComponent = window.Alpine?.$data(document.querySelector('[x-data]'));
            if (alpineComponent) {
                alpineComponent.selectedSubBidangKode = '';
            }
            subSelect.innerHTML = '<option value="">Memuat sub-bidang...</option>';
            subSelect.disabled = true;

            if (bidangId) {
<<<<<<< HEAD
                fetch('{{ url('/get-sub-bidang') }}/' + encodeURIComponent(bidangId))
=======
                const subBidangUrl = subBidangUrlTemplate.replace('__BIDANG_ID__', encodeURIComponent(bidangId));
                fetch(subBidangUrl)
>>>>>>> 23d71526497aa8bc1353a93efea120528a1f5a59
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
            const normalizedKodeSub = kodeSub.trim().toUpperCase();

            let alpineComponent = window.Alpine?.$data(document.querySelector('[x-data]'));
            if (alpineComponent) {
                alpineComponent.selectedSubBidangKode = normalizedKodeSub;
            }
        });

        // Sinkronkan pilihan sub-bidang yang sudah terpilih saat halaman dibuka.
        const selectedSubBidang = document.getElementById('sub_bidang_select');
        const selectedSubOption = selectedSubBidang.options[selectedSubBidang.selectedIndex];
        const initialSubText = selectedSubOption?.textContent?.trim().toUpperCase() || '';
        const initialSubKode = selectedSubOption?.getAttribute('data-kode')?.trim().toUpperCase()
            || (initialSubText.startsWith('D.03.') ? 'D.03' : '');
        const initialAlpineComponent = window.Alpine?.$data(document.querySelector('[x-data]'));
        if (initialAlpineComponent && initialSubKode) {
            initialAlpineComponent.selectedSubBidangKode = initialSubKode;
        }

        // --- 2. SCRIPT FOR DROPDOWN WILAYAH INDONESIA (BERTINGKAT) ---
        const provSelect = document.getElementById('provinsi_select');
        const provSelectC08 = document.getElementById('provinsi_c08');
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
                    if (provSelectC08) {
                        provSelectC08.innerHTML = '<option value="">Pilih provinsi</option>';
                        provinces.forEach(p => {
                            provSelectC08.innerHTML += `<option value="${p.name}">${p.name}</option>`;
                        });
                    }
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
            }, 0);
        });
    </script>
</body>
</html>