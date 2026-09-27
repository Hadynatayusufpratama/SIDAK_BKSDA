<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Konservasi - SIDAK BKSDA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-100/90 font-sans text-slate-800 antialiased min-h-screen relative">
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('konservasi.dashboard') }}" class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-700/10 border border-emerald-700/20 flex items-center justify-center p-1.5 shadow-xs overflow-hidden">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIDAK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-slate-900 leading-tight">SIDAK BKSDA SULTENG</span>
                    <span class="text-[11px] text-slate-500 font-medium">Sistem Input data konservasi</span>
                </div>
            </a>
            <nav class="hidden md:flex items-center space-x-1 bg-slate-100/80 p-1.5 rounded-xl border border-slate-200">
                <a href="{{ route('konservasi.dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Dashboard</a>
                <a href="{{ route('konservasi.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-white text-emerald-700 shadow-xs">Rekapitulasi</a>
                <a href="{{ route('konservasi.create') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Tambah Data</a>
                <a href="{{ route('konservasi.peta') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-white/50 transition">Peta GIS</a>
            </nav>
            <div class="flex items-center gap-2.5 border-l pl-4 border-slate-200">
                <div class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs shadow-sm uppercase">
                    {{ strtoupper(substr(Auth::user()->name ?? 'User', 0, 2)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                    <p class="text-[10px] text-slate-500">{{ Auth::user()->role ?? 'Operator' }}</p>
                </div>
            </div>
        </div>
    </header>

    <div class="min-h-screen">

        <!-- MAIN CONTENT -->
        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    
                    <!-- Form Header Banner -->
                    <div class="p-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-700 uppercase">Mode Perubahan Data</span>
                            <h2 class="text-2xl font-extrabold mt-1 text-slate-900">Edit Data Konservasi</h2>
                            <p class="text-xs text-slate-500 mt-1">Perbarui informasi data capaian kinerja dan inventarisasi kawasan.</p>
                        </div>
                        <a href="{{ route('konservasi.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-200/80 hover:bg-slate-300 transition shadow-xs">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Rekapitulasi
                        </a>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('konservasi.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8" x-data="{ selectedSubKode: @js($currentKode) }">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Klasifikasi -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Klasifikasi & Kategori Data
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bidang Utama -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bidang Utama *</label>
                                    <div class="relative">
                                        <select id="bidang_id" name="bidang_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition appearance-none font-medium text-slate-800 pr-10" required>
                                            <option value="">-- Pilih Bidang Utama --</option>
                                            @foreach($bidangs as $b)
                                                <option value="{{ $b->id }}" {{ (isset($currentBidangId) && $currentBidangId == $b->id) ? 'selected' : '' }}>
                                                    {{ $b->nama_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>

                                <!-- Sub Bidang -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sub-Bidang Kategori *</label>
                                    <div class="relative">
                                        <select id="sub_bidang_id" name="sub_bidang_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition appearance-none font-medium text-slate-800 pr-10" required>
                                            <option value="">-- Pilih Sub-Bidang --</option>
                                            @foreach($subBidangs as $sb)
                                                <option value="{{ $sb->id }}" data-kode="{{ $sb->kode_sub }}" {{ $item->sub_bidang_id == $sb->id ? 'selected' : '' }}>
                                                    {{ $sb->nama_sub_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        @php
                            $editRadioOptions = [
                                'ada_perubahan' => ['ya' => 'Ya, ada perubahan', 'tidak' => 'Tidak ada'],
                                'sk_provinsi_tersedia' => ['ya' => 'Ya, tersedia', 'tidak' => 'Tidak'],
                                'sk_penetapan_tersedia' => ['ya' => 'Ya, tersedia', 'tidak' => 'Tidak'],
                                'ketersediaan_rpjp' => ['ya' => 'Ya, tersedia', 'tidak' => 'Tidak'],
                                'ada_kegiatan_monitoring' => ['ya' => 'Ada kegiatan', 'tidak' => 'Tidak ada'],
                                'jenis_kegiatan' => ['Penataan' => 'Penataan', 'Rekonstruksi' => 'Rekonstruksi', 'Pemeliharaan' => 'Pemeliharaan'],
                                'ketersediaan_evaluasi' => ['ya' => 'Ya, tersedia', 'tidak' => 'Tidak'],
                                'ketersediaan_ekosistem' => ['ya' => 'Ya, tersedia', 'tidak' => 'Tidak'],
                                'ketersediaan_zonasi' => ['sudah' => 'Sudah', 'belum' => 'Belum'],
                                'ada_kegiatan_b01' => ['ya' => 'Ada kegiatan', 'tidak' => 'Tidak ada'],
                                'sumber_dana' => ['APBN KLHK Lainnya' => 'APBN KLHK Lainnya', 'APBN KSDAE' => 'APBN KSDAE', 'Pendanaan Pihak Lainnya' => 'Pendanaan Pihak Lainnya', 'Pendanaan Gabungan' => 'Pendanaan Gabungan'],
                            ];
                            $editDateFields = ['sk_parsial_tanggal', 'sk_provinsi_tanggal', 'sk_penetapan_tanggal', 'sk_rpjp_tanggal_pengesahan', 'sk_rpjp_periode_berakhir', 'tanggal_batb', 'tanggal_pelaksanaan_evaluasi', 'tanggal_sk_zonasi'];
                            $editNumberFields = ['sk_parsial_luas', 'sk_provinsi_luas', 'sk_penetapan_luas', 'pal_baik', 'pal_rusak', 'pal_hilang', 'pal_total', 'panjang_pal_km', 'jumlah_laki', 'jumlah_perempuan', 'jumlah_bantuan'];
                            $editTextareaFields = ['rekomendasi_evaluasi', 'tindak_lanjut_evaluasi'];
                            $editFileFields = ['sk_parsial_file', 'sk_provinsi_file', 'sk_penetapan_file', 'shapefile_zip', 'sk_rpjp_file', 'dokumen_batb', 'shapefile_monitoring_zip', 'file_dokumen_evaluasi', 'shapefile_ekosistem_zip', 'file_sk_zonasi', 'shapefile_zonasi_zip', 'dokumen_d03', 'shapefile_d03', 'foto_odtwa', 'dokumen_perizinan_pbp_d06', 'shapefile_pbp_d06', 'foto_sarana_prasarana_d07', 'shapefile_d10', 'dokumen_sk_d11', 'shapefile_d11', 'dokumen_perizinan_d14', 'shapefile_d14', 'dokumen_perizinan_c02', 'dokumen_perizinan_c04', 'dokumen_perizinan_c06'];
                            $editImageFields = ['foto_odtwa', 'foto_sarana_prasarana_d07'];
                            $editCheckboxFields = ['hhbk_nihil', 'pertanian_nihil', 'perkebunan_nihil', 'peternakan_nihil', 'perikanan_nihil', 'wisata_nihil', 'produk_nihil', 'pembibitan_nihil', 'lainnya_nihil'];
                        @endphp

                        <div class="space-y-6" x-cloak x-show="selectedSubKode && {{ Js::from(array_keys($editFieldsBySubBidang)) }}.includes(selectedSubKode)">
                            @foreach($editFieldsBySubBidang as $kode => $fields)
                                <section x-cloak x-show="selectedSubKode === @js($kode)" class="space-y-5">
                                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200/80">
                                        <span class="w-7 h-7 rounded-lg bg-emerald-700 text-amber-400 flex items-center justify-center text-xs font-black">2</span>
                                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Data Sub-Bidang {{ $kode }}</h3>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        @foreach($fields as $field)
                                            @php
                                                $name = $field['name'];
                                                $value = old($name, $detailValues[$name] ?? (str_starts_with($name, 'tahun') ? $item->tahun : ''));
                                            @endphp
                                            <div class="{{ in_array($name, $editTextareaFields, true) ? 'md:col-span-2' : '' }}">
                                                <label class="block text-xs font-bold text-slate-700 mb-2">{{ $field['label'] }}</label>
                                                @if(isset($editRadioOptions[$name]))
                                                    <div class="flex flex-wrap gap-x-5 gap-y-3 py-2">
                                                        @foreach($editRadioOptions[$name] as $optionValue => $optionLabel)
                                                            <label class="inline-flex items-center gap-2 text-xs text-slate-700">
                                                                <input type="radio" name="{{ $name }}" value="{{ $optionValue }}" {{ (string) $value === (string) $optionValue ? 'checked' : '' }} class="text-emerald-700 focus:ring-emerald-600">
                                                                {{ $optionLabel }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif(in_array($name, $editCheckboxFields, true))
                                                    <label class="inline-flex items-center gap-2 py-2 text-xs text-slate-700">
                                                        <input type="checkbox" name="{{ $name }}" value="on" {{ in_array(strtolower((string) $value), ['ya', 'on', '1', 'true'], true) ? 'checked' : '' }} class="rounded text-emerald-700 focus:ring-emerald-600">
                                                        Nihil
                                                    </label>
                                                @elseif(in_array($name, $editFileFields, true))
                                                    <input type="file" name="{{ $name }}{{ $name === 'foto_sarana_prasarana_d07' ? '[]' : '' }}" accept="{{ in_array($name, $editImageFields, true) ? 'image/png,image/jpeg' : (str_contains($name, 'zip') ? '.zip' : '.pdf') }}" {{ $name === 'foto_sarana_prasarana_d07' ? 'multiple' : '' }} class="w-full text-xs text-slate-500 border border-slate-200 bg-slate-50 rounded-xl p-2.5">
                                                    @if(!empty($value))
                                                        <p class="mt-1 text-[11px] text-slate-500">Lampiran tersimpan: {{ basename($value) }}. Kosongkan jika tidak ingin mengganti.</p>
                                                    @endif
                                                @elseif(in_array($name, $editTextareaFields, true))
                                                    <textarea name="{{ $name }}" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition text-slate-800">{{ $value }}</textarea>
                                                @else
                                                    <input type="{{ in_array($name, $editDateFields, true) ? 'date' : (in_array($name, $editNumberFields, true) || str_starts_with($name, 'tahun') ? 'number' : 'text') }}" name="{{ $name }}" value="{{ $value }}" {{ in_array($name, ['sk_parsial_luas', 'sk_provinsi_luas', 'sk_penetapan_luas', 'panjang_pal_km'], true) ? 'step=0.01' : '' }} class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition font-medium text-slate-800">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200/80">
                                <span class="w-7 h-7 rounded-lg bg-emerald-700 text-amber-400 flex items-center justify-center text-xs font-black">3</span>
                                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Waktu & Koordinat Spasial</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Bulan</label>
                                    <input type="text" name="bulan" value="{{ old('bulan', $item->bulan) }}" placeholder="Opsional" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Latitude (LS/LU)</label>
                                    <input type="text" name="latitude" value="{{ old('latitude', $item->latitude) }}" placeholder="-0.897123" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Longitude (BT)</label>
                                    <input type="text" name="longitude" value="{{ old('longitude', $item->longitude) }}" placeholder="119.87123" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                                </div>
                                <div class="sm:col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Keterangan Tambahan</label>
                                    <input type="text" name="keterangan" value="{{ old('keterangan', $detailValues['keterangan'] ?? '') }}" placeholder="Opsional" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                            <a href="{{ route('konservasi.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                <i class="fas fa-xmark"></i> Batal
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-teal-800 hover:from-emerald-900 hover:to-teal-900 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-900/20 transition flex items-center gap-2">
                                <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        const form = document.querySelector('form');
        const bidangSelect = document.getElementById('bidang_id');
        const subBidangSelect = document.getElementById('sub_bidang_id');
        const updateSubBidangFields = () => {
            const selectedOption = subBidangSelect.options[subBidangSelect.selectedIndex];
            Alpine.$data(form).selectedSubKode = selectedOption?.dataset.kode || '';
        };

        bidangSelect.addEventListener('change', function () {
            const bidangId = this.value;
            subBidangSelect.innerHTML = '<option value="">-- Memuat Sub-Bidang... --</option>';
            Alpine.$data(form).selectedSubKode = '';

            if (!bidangId) {
                subBidangSelect.innerHTML = '<option value="">-- Pilih Bidang Terlebih Dahulu --</option>';
                return;
            }

            fetch(`/get-sub-bidang/${bidangId}`)
                .then(response => response.json())
                .then(data => {
                    subBidangSelect.innerHTML = '<option value="">-- Pilih Sub-Bidang --</option>';
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.dataset.kode = item.kode_sub || item.kode || '';
                        option.textContent = item.nama_sub_bidang;
                        subBidangSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    subBidangSelect.innerHTML = '<option value="">-- Gagal memuat sub-bidang --</option>';
                });
        });

        subBidangSelect.addEventListener('change', updateSubBidangFields);
    </script>
</body>
</html>