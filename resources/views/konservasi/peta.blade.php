<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta GIS Kawasan - SIDAK BKSDA Sulawesi Tengah</title>
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
    
    <!-- Leaflet GIS Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: 680px; width: 100%; border-radius: 1rem; }
    </style>
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative">

    <!-- BACKGROUND GLOBAL KAWASAN KONSERVASI DENGAN OPASITAS TIPIS -->
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1920&auto=format&fit=crop" alt="Background Konservasi" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-slate-100/75 backdrop-blur-[2px]"></div>
    </div>

    <!-- NAVBAR UTAMA (SERAGAM DENGAN DASHBOARD, REKAPITULASI, & INPUT DATA) -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo & Title Instansi -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl bg-forest-700/10 border border-forest-700/20 flex items-center justify-center p-1.5 shadow-xs overflow-hidden">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=SIDAK';">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Input data konservasi</span>
                </div>
            </div>

            <!-- Menu Navigasi (Menu Peta GIS Aktif) -->
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-forest-700 shadow-xs">Peta GIS</a>
            </nav>

            <!-- Status & User Profile (Dinamis) -->
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

        <!-- HEADER BANNER & RINGKASAN SEBARAN -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-forest-700 uppercase tracking-wider bg-emerald-100/70 px-2.5 py-0.5 rounded-md">GIS Spasial</span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs text-slate-500">Sulawesi Tengah</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Peta GIS Sebaran Kawasan Conservasi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Visualisasi batas interaktif kawasan hutan konservasi dan titik pemantauan Balai KSDA Sulawesi Tengah
                </p>
            </div>

            <!-- Legenda Ringkas -->
            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-700">
                    <span class="w-3.5 h-3.5 rounded-sm bg-emerald-500/60 border border-emerald-600"></span>
                    <span>Poligon Kawasan</span>
                </div>
                <div class="h-4 w-[1px] bg-slate-300"></div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-700">
                    <i class="fa-solid fa-location-dot text-rose-500"></i>
                    <span>Titik Koordinat</span>
                </div>
            </div>
        </div>

        <!-- CONTAINER PETA GIS LEAFLET -->
        <div class="bg-white/90 backdrop-blur-md p-4 md:p-5 rounded-2xl border border-slate-200/80 shadow-sm relative">
            <div id="map" class="shadow-inner rounded-xl z-10"></div>
        </div>

    </main>

    <!-- Leaflet Map Logic -->
    <script>
        // 1. Inisialisasi Peta Fokus Langsung ke Sulawesi Tengah (Zoom Level 7)
        var map = L.map('map', {
            minZoom: 6,
            maxZoom: 18
        }).setView([-1.2, 120.8], 7);

        // 2. Basemap Satelite Esri & OpenStreetMap
        var sateliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; BKSDA Sulawesi Tengah'
        });

        var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; BKSDA Sulawesi Tengah | OpenStreetMap'
        });

        // Set Default ke Peta Satelit
        sateliteMap.addTo(map);

        // Switcher Layer
        var baseMaps = {
            "Peta Satelit (Esri)": sateliteMap,
            "Peta Jalan (OSM)": streetMap
        };
        L.control.layers(baseMaps).addTo(map);

        // 3. MUAT GEOJSON KAWASAN KONSERVASI BKSDA SULTENG
        fetch("{{ asset('Kawasan_Hutan_Konservasi_SIDAK_2.json') }}")
            .then(response => response.json())
            .then(data => {
                var geojsonLayer = L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: "#22c55e",       // Batas Luar (Hijau Terang)
                            weight: 2,
                            opacity: 1,
                            fillColor: "#16a34a",   // Isian Area Kawasan
                            fillOpacity: 0.5
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties) {
                            var namaKawasan = feature.properties.NAMOBJ || "Kawasan Konservasi";
                            var fungsi = feature.properties.REMARK || "BKSDA Sulteng";
                            var kabupaten = feature.properties.WADMKK || "-";

                            layer.bindTooltip(namaKawasan, {
                                permanent: false,
                                direction: "center",
                                className: "bg-forest-800 text-white font-bold px-2.5 py-1 rounded-lg text-xs border border-forest-600 shadow-md"
                            });

                            layer.bindPopup(`
                                <div class="p-1 min-w-[180px]">
                                    <strong class="text-[10px] text-forest-700 block uppercase tracking-wider font-extrabold">BKSDA SULAWESI TENGAH</strong>
                                    <h4 class="font-bold text-sm text-slate-800 leading-snug mt-0.5">${namaKawasan}</h4>
                                    <hr class="my-2 border-slate-200">
                                    <p class="text-xs text-slate-600">Kabupaten/Kota: <strong>${kabupaten}</strong></p>
                                    <p class="text-xs text-slate-500 mt-1">Fungsi: <span>${fungsi}</span></p>
                                </div>
                            `);
                        }
                    }
                }).addTo(map);

                // Auto zoom memuat seluruh wilayah kawasan
                map.fitBounds(geojsonLayer.getBounds());
            })
            .catch(error => console.error("Error GeoJSON:", error));

        // 4. Marker Lokasi Database
        var locations = <?php echo json_encode($locations ?? []); ?>;
        if (Array.isArray(locations)) {
            locations.forEach(function(item) {
                if(item.latitude && item.longitude) {
                    var marker = L.marker([item.latitude, item.longitude]).addTo(map);
                    marker.bindPopup(`
                        <div class="p-1">
                            <strong class="text-xs text-forest-700 block">${item.sub_bidang && item.sub_bidang.bidang ? item.sub_bidang.bidang.nama_bidang : ''}</strong>
                            <h4 class="font-bold text-sm text-slate-800">${item.sub_bidang ? item.sub_bidang.nama_sub_bidang : ''}</h4>
                            <p class="text-xs text-slate-600 mt-1">Volume: <strong>${item.jumlah ?? '-'}</strong></p>
                            <p class="text-xs text-slate-500 mt-1">${item.keterangan ?? ''}</p>
                        </div>
                    `);
                }
            });
        }
    </script>
</body>
</html>