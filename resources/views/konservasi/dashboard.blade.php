<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics - SIDAK BKSDA Sulawesi Tengah</title>
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
    <!-- Chart.js untuk Grafik Dinamis -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative">

    <!-- BACKGROUND GLOBAL KAWASAN KONSERVASI DENGAN OPASITAS TIPIS -->
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
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=SIDAK';">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem input data konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>

            <!-- Status & User Profile (DINAMIS SESUAI USER LOGIN) -->
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

        <!-- HERO BANNER -->
        <div class="relative rounded-2xl overflow-hidden shadow-xl bg-slate-900 text-white min-h-[240px] flex flex-col justify-between p-8 border border-slate-800">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/satwa-endemik-sulteng.jpg') }}" alt="Satwa Endemik & Kawasan Konservasi Sulawesi Tengah" class="w-full h-full object-cover opacity-50 transform hover:scale-105 transition duration-700" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1600&auto=format&fit=crop'">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-slate-950/40"></div>
            </div>

            <!-- Konten Banner -->
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-medium mb-3 text-emerald-300 shadow-sm">
                    <i class="fa-solid fa-tree"></i> <span>Kawasan Konservasi & Satwa Endemik Sulawesi Tengah</span>
                </div>
                
                <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    Selamat Datang, {{ Auth::user()->name ?? 'Petugas Operator' }}!
                </h2>

                <p class="mt-2 text-xs sm:text-sm text-slate-200 leading-relaxed">
                    Pusat pemantauan data kawasan hutan, perlindungan satwa endemik, serta pemetaan koordinat GIS wilayah kerja BKSDA Sulawesi Tengah.
                </p>
            </div>

            <!-- Tombol Tambah Data & Badge Hak Akses -->
            <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 mt-6 pt-4 border-t border-white/15">
                <div class="flex items-center space-x-2 text-xs text-slate-200">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i>
                    <span>Hak Akses: <strong class="text-white font-semibold">Petugas {{ Auth::user()->role ?? 'Operator' }} Terverifikasi</strong></span>
                </div>
                <div>
                    <a href="{{ route('konservasi.create') }}" class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 shadow-md transition">
                        <i class="fa-solid fa-plus mr-1.5"></i> Tambah Data Konservasi
                    </a>
                </div>
            </div>
        </div>

        <!-- GRID STATISTIK DINAMIS DARI CONTROLLER -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Total Entri -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Entri Data</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <i class="fa-solid fa-database text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalData ?? 0) }}</h3>
                <p class="text-xs text-slate-500 mt-2 flex items-center">
                    Seluruh data konservasi yang tersimpan
                </p>
            </div>

            <!-- Card 2: Cakupan Sub-Bidang -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sub-Bidang Terisi</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <i class="fa-solid fa-tree text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $totalSubBidangTerisi ?? 0 }} <span class="text-lg text-slate-400">/ {{ $totalSubBidang ?? 0 }}</span></h3>
                <p class="text-xs text-slate-500 mt-2">Kategori yang memiliki entri dari enam bidang</p>
            </div>

            <!-- Card 3: Data Volume -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between text-slate-500 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Entri dengan Volume</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <i class="fa-solid fa-chart-column text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalEntriBervolume ?? 0) }}</h3>
                <p class="text-xs text-slate-500 mt-2">Input yang memiliki nilai kuantitatif</p>
            </div>

        </div>

        <!-- SECTION BAWAH: GRAFIK & AKSES PETA GIS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Grafik jumlah entri per enam bidang -->
            <div class="lg:col-span-2 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
                    <div>
                        <div class="flex items-center gap-2 text-emerald-700 mb-1">
                            <i class="fa-solid fa-chart-column text-sm"></i>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider">Perbandingan Entri</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900">Jumlah Entri per Enam Bidang</h3>
                        <p class="text-xs text-slate-500 mt-1">Setiap bidang tetap ditampilkan, termasuk yang belum memiliki data.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 self-start px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-[11px] font-semibold text-slate-600">
                        {{ number_format($totalData ?? 0) }} entri tersimpan
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.2fr)_minmax(260px,0.8fr)] gap-6 items-start">
                    <div class="relative h-[320px] min-w-0">
                        <canvas id="bidangChart" aria-label="Jumlah entri pada enam bidang konservasi" role="img"></canvas>
                    </div>

                    <div class="lg:border-l lg:border-slate-100 lg:pl-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">Cakupan subbidang</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Jumlah kategori yang sudah memiliki entri</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @foreach($bidangChart as $bidang)
                                @php
                                    $coverage = $bidang['total_subbidang'] > 0
                                        ? ($bidang['subbidang_terisi'] / $bidang['total_subbidang']) * 100
                                        : 0;
                                @endphp
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-1.5">
                                        <div class="flex items-start gap-2 min-w-0">
                                            <span class="w-2.5 h-2.5 mt-1 rounded-sm shrink-0" style="background-color: {{ $bidang['warna'] }}"></span>
                                            <span class="text-[11px] font-semibold text-slate-700 leading-snug">{{ $bidang['nama'] }}</span>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-800 shrink-0">{{ number_format($bidang['jumlah_entri']) }}</span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $coverage }}%; background-color: {{ $bidang['warna'] }}"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $bidang['subbidang_terisi'] }} dari {{ $bidang['total_subbidang'] }} subbidang terisi</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Akses Cepat Peta GIS -->
            <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-sm border border-slate-800 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=800&auto=format&fit=crop" alt="GIS Map" class="w-full h-full object-cover">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-white">Akses Cepat GIS</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">Live Map</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Eksplorasi spasial wilayah konservasi, titik koordinat patroli, dan batas kawasan hutan di Sulawesi Tengah secara terintegrasi.
                    </p>

                    <div class="mt-6 bg-slate-800/80 backdrop-blur rounded-xl p-4 border border-slate-700 h-36 flex items-center justify-center relative shadow-inner">
                        <div class="text-center">
                            <i class="fa-solid fa-map-location-dot text-3xl text-emerald-400 mb-2 animate-bounce"></i>
                            <p class="text-xs font-semibold text-slate-200">Peta Spasial Sulteng Aktif</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $totalLokasi ?? 0 }} Titik Koordinat Terpetakan</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-6">
                    <a href="{{ route('konservasi.peta') }}" class="w-full py-2.5 px-4 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold rounded-xl text-xs text-center block transition shadow-md">
                        Jelajahi Peta GIS Interaktif <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Volume ditampilkan per subbidang agar satuan tidak tercampur -->
            <div class="lg:col-span-3 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-5">
                    <div>
                        <div class="flex items-center gap-2 text-amber-700 mb-1">
                            <i class="fa-solid fa-chart-bar text-sm"></i>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider">Data Kuantitatif</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900">Volume Input per Sub-Bidang</h3>
                        <p class="text-xs text-slate-500 mt-1">Nilai dijumlahkan di dalam subbidang masing-masing, bukan antarjenis satuan.</p>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500">{{ count($volumeChart) }} subbidang memiliki nilai volume</span>
                </div>

                @if(count($volumeChart))
                    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.1fr)_minmax(280px,0.9fr)] gap-6 items-start">
                        <div class="max-h-[360px] overflow-y-auto pr-2">
                            <div style="height: {{ max(220, count($volumeChart) * 42) }}px">
                                <canvas id="volumeChart" aria-label="Nilai kuantitatif per sub-bidang" role="img"></canvas>
                            </div>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($volumeChart as $subBidang)
                                <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-slate-800">{{ $subBidang['kode'] }} · {{ $subBidang['nama'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $subBidang['nama_bidang'] }} · {{ $subBidang['entri_bervolume'] }} entri bervolume</p>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-900 shrink-0">{{ number_format($subBidang['jumlah_volume'], 0, ',', '.') }} {{ $subBidang['satuan'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="min-h-36 flex flex-col items-center justify-center text-center border border-dashed border-slate-200 rounded-xl px-5">
                        <i class="fa-solid fa-chart-bar text-xl text-slate-300 mb-2"></i>
                        <p class="text-sm font-semibold text-slate-600">Belum ada input dengan nilai volume</p>
                        <p class="text-xs text-slate-400 mt-1">Grafik terisi otomatis saat kategori menyimpan nilai kuantitatif.</p>
                    </div>
                @endif
            </div>

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bidangStats = @js($bidangChart ?? []);
            const volumeStats = @js($volumeChart ?? []);
            const numberFormat = new Intl.NumberFormat('id-ID');
            const sharedOptions = {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 650 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 11,
                        callbacks: {
                            label(context) {
                                return ` ${numberFormat.format(context.raw)} entri`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#64748b' },
                        grid: { color: '#e2e8f0' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#334155', font: { weight: '600' } }
                    }
                }
            };

            new Chart(document.getElementById('bidangChart'), {
                type: 'bar',
                data: {
                    labels: bidangStats.map((bidang) => bidang.kode),
                    datasets: [{
                        data: bidangStats.map((bidang) => bidang.jumlah_entri),
                        backgroundColor: bidangStats.map((bidang) => bidang.warna),
                        borderRadius: 5,
                        barThickness: 24,
                    }]
                },
                options: {
                    ...sharedOptions,
                    plugins: {
                        ...sharedOptions.plugins,
                        tooltip: {
                            ...sharedOptions.plugins.tooltip,
                            callbacks: {
                                title(context) {
                                    return bidangStats[context[0].dataIndex].nama;
                                },
                                label(context) {
                                    return ` ${numberFormat.format(context.raw)} entri`;
                                }
                            }
                        }
                    }
                },
            });

            const volumeCanvas = document.getElementById('volumeChart');
            if (volumeCanvas && volumeStats.length) {
                const volumeColors = volumeStats.map((item) => {
                    const bidang = bidangStats.find((field) => item.kode.startsWith(field.kode + '.'));
                    return bidang?.warna ?? '#64748b';
                });

                new Chart(volumeCanvas, {
                    type: 'bar',
                    data: {
                        labels: volumeStats.map((item) => item.kode),
                        datasets: [{
                            data: volumeStats.map((item) => item.jumlah_volume),
                            backgroundColor: volumeColors,
                            borderRadius: 5,
                            barThickness: 22,
                        }]
                    },
                    options: {
                        ...sharedOptions,
                        plugins: {
                            ...sharedOptions.plugins,
                            tooltip: {
                                ...sharedOptions.plugins.tooltip,
                                callbacks: {
                                    title(context) {
                                        const item = volumeStats[context[0].dataIndex];
                                        return `${item.kode} · ${item.nama}`;
                                    },
                                    label(context) {
                                        const item = volumeStats[context.dataIndex];
                                        return ` ${numberFormat.format(context.raw)} ${item.satuan}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            ...sharedOptions.scales,
                            x: {
                                ...sharedOptions.scales.x,
                                ticks: {
                                    ...sharedOptions.scales.x.ticks,
                                    callback(value) {
                                        return numberFormat.format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>