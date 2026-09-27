<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\DataKonservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class KonservasiController extends Controller
{
    // Helper privat untuk memfilter query pencarian secara konsisten (pencarian multi-kolom + mapping bulan)
    private function applySearchFilter($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = trim($request->search);

            // Mapping nama bulan Indonesia & Inggris ke format angka
            $monthsMap = [
                'januari' => '01', 'january' => '01', 'jan' => '01',
                'februari' => '02', 'february' => '02', 'feb' => '02',
                'maret' => '03', 'march' => '03', 'mar' => '03',
                'april' => '04', 'apr' => '04',
                'mei' => '05', 'may' => '05',
                'juni' => '06', 'june' => '06', 'jun' => '06',
                'juli' => '07', 'july' => '07', 'jul' => '07',
                'agustus' => '08', 'august' => '08', 'agu' => '08', 'aug' => '08',
                'september' => '09', 'sep' => '09',
                'oktober' => '10', 'october' => '10', 'okt' => '10', 'oct' => '10',
                'november' => '11', 'nov' => '11',
                'desember' => '12', 'december' => '12', 'des' => '12', 'dec' => '12',
            ];

            $searchLower = strtolower($search);
            $monthNumber = $monthsMap[$searchLower] ?? null;

            $query->where(function ($q) use ($search, $monthNumber) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('jumlah', 'like', "%{$search}%")
                  ->orWhere('bulan', 'like', "%{$search}%");

                if ($monthNumber) {
                    $q->orWhere('bulan', $monthNumber)
                      ->orWhere('bulan', (int)$monthNumber);
                }

                $q->orWhereHas('subBidang', function ($subQ) use ($search) {
                    $subQ->where('nama_sub_bidang', 'like', "%{$search}%")
                         ->orWhere('kode_sub', 'like', "%{$search}%")
                         ->orWhereHas('bidang', function ($bidangQ) use ($search) {
                             $bidangQ->where('nama_bidang', 'like', "%{$search}%");
                         });
                });
            });
        }

        return $query;
    }

    // Halaman Rekapitulasi Data
    public function index(Request $request)
    {
        // 1. Array Master Bidang A-F Lengkap untuk Form Dropdown Filter Bertingkat
        $masterBidang = [
            'perencanaan_konservasi' => [
                'nama' => 'Perencanaan Konservasi',
                'subs' => [
                    'A.01' => 'A.01. Kawasan Konservasi',
                    'A.02' => 'A.02. Perencanaan Pengelolaan Kawasan Konservasi',
                    'A.03' => 'A.03. Monitoring Batas Kawasan Konservasi',
                    'A.04' => 'A.04. Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi',
                    'A.05' => 'A.05. Ekosistem Kawasan Konservasi',
                    'A.06' => 'A.06. Penataan Kawasan Konservasi',
                    'A.07' => 'A.07. Kerja Sama Penyelenggaraan KSA dan KPA',
                ]
            ],
            'konservasi_kawasan' => [
                'nama' => 'Konservasi Kawasan',
                'subs' => [
                    'B.01' => 'B.01. Kelompok Binaan UPT dalam rangka Pemberdayaan Masyarakat',
                    'B.02' => 'B.02. Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi',
                    'B.03' => 'B.03. Permasalahan Strategis Kawasan Konservasi',
                    'B.04' => 'B.04. Gangguan Kawasan Konservasi',
                    'B.06' => 'B.06. Penanganan Perkara Tindak Pidana',
                    'B.07' => 'B.07. Tenaga Pengamanan Hutan Per Satuan Kerja',
                    'B.08' => 'B.08. Tenaga Pengamanan Hutan Per Resor',
                    'B.09' => 'B.09. Sarana Pengamanan Hutan',
                    'B.10' => 'B.10. Kebakaran Hutan dan Lahan di Kawasan Konservasi',
                    'B.11' => 'B.11. Tenaga Pengendalian Kebakaran Hutan',
                    'B.12' => 'B.12. Peralatan Tangan Pengendalian Kebakaran Hutan',
                    'B.13' => 'B.13. Peralatan Lainnya untuk Kebutuhan Pengendalian Kebakaran Hutan',
                    'B.14' => 'B.14. Rekapitulasi Kader Bina Cinta Alam',
                ]
            ],
            'konservasi_spesies' => [
                'nama' => 'Konservasi Spesies dan Genetik',
                'subs' => [
                    'C.01' => 'C.01. Perjumpaan Spesies di dalam dan luar Kawasan Konservasi',
                    'C.02' => 'C.02. Lembaga Konservasi Umum dan Khusus',
                    'C.03' => 'C.03. Koleksi TSL di Lembaga Konservasi',
                    'C.04' => 'C.04. Penangkaran Tumbuhan dan Satwa Liar',
                    'C.05' => 'C.05. Jenis TSL yang ditangkarkan di Penangkaran',
                    'C.06' => 'C.06. Pengedar Tumbuhan dan Satwa Liar (Dalam dan Luar Negeri)',
                    'C.07' => 'C.07. Kuota Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.08' => 'C.08. Realisasi Penangkapan/ Pengambilan Tumbuhan dan Satwa Liar',
                    'C.09' => 'C.09. Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Penangkaran',
                    'C.10' => 'C.10. Realisasi Ekspor Tumbuhan dan Satwa Liar Pengambilan dari Alam',
                    'C.11' => 'C.11. Rekapitulasi Sitaan/ Penyerahan/ Penyelamatkan Satwa',
                    'C.12' => 'C.12. PNBP dari kegiatan Pemanfaatan Tumbuhan dan Satwa Liar',
                    'C.14' => 'C.14. Interaksi Negatif Satwa Liar dan Manusia',
                    'C.15' => 'C.15. Rekapitulasi Pelepasliaran Kembali Satwa',
                    'C.16' => 'C.16. Rekapitulasi Kelahiran Satwa',
                    'C.17' => 'C.17. Rekapitulasi Kematian Satwa Liar',
                ]
            ],
            'pemanfaatan_jasling' => [
                'nama' => 'Pemanfaatan Jasa Lingkungan',
                'subs' => [
                    'D.01' => 'D.01. Pengunjung Kawasan Konservasi',
                    'D.02' => 'D.02. PNBP Wisata Alam di Kawasan Konservasi',
                    'D.03' => 'D.03. Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam',
                    'D.04' => 'D.04. Potensi Wisata Alam di Kawasan Konservasi',
                    'D.05' => 'D.05. Pemanfaatan Jasa Lingkungan Penyediaan Jasa Wisata Alam (PBPJWA)',
                    'D.06' => 'D.06. Pengusahaan Sarana Jasa Lingkungan Wisata Alam (PBPSWA)',
                    'D.07' => 'D.07. Sarana dan Prasarana Wisata Alam di Kawasan Konservasi',
                    'D.08' => 'D.08. Dampak Aktivitas Wisata Alam',
                    'D.09' => 'D.09. Potensi Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.10' => 'D.10. Areal Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.11' => 'D.11. Pemanfaatan Air dan Energi Air di Kawasan Konservasi',
                    'D.12' => 'D.12. Potensi Pemanfaatan Karbon di Kawasan Konservasi',
                    'D.13' => 'D.13. Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi',
                    'D.14' => 'D.14. Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi',
                    'D.15' => 'D.15. Kejadian Kecelakaan di dalam Kawasan Konservasi',
                    'D.16' => 'D.16. Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi',
                ]
            ],
            'pemulihan_ekosistem' => [
                'nama' => 'Pemulihan Ekosistem dan Bina Area Preservasi',
                'subs' => [
                    'E.01' => 'E.01. Perencanaan Pemulihan Ekosistem',
                    'E.02' => 'E.02. Realisasi Pemulihan Ekosistem',
                    'E.03' => 'E.03. Hasil Inventarisasi Area dengan Potensi Kehati Tinggi di Luar Kawasan Konservasi',
                    'E.04' => 'E.04. Kawasan Ekosistem Esensial',
                    'E.05' => 'E.05. Perencanaan Kawasan Ekosistem Esensial',
                    'E.06' => 'E.06. Penilaian Efektivitas Pengelolaan KEE',
                    'E.07' => 'E.07. Situs Ramsar',
                ]
            ],
            'kesekretariatan' => [
                'nama' => 'Kesekretariatan',
                'subs' => [
                    'F.01' => 'F.01. Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin',
                    'F.02' => 'F.02. Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.03' => 'F.03. Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin',
                    'F.04' => 'F.04. Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin',
                    'F.05' => 'F.05. Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin',
                    'F.06' => 'F.06. Sebaran ASN P3K menurut Tingkat Pendidikan dan Jenis Kelamin',
                    'F.07' => 'F.07. Kerja Sama Teknik Bidang KSDAE',
                    'F.08' => 'F.08. Perijinan Masuk Kawasan Konservasi',
                ]
            ]
        ];

        // 2. Ambil parameter filter input
        $selectedBidang = $request->get('bidang');
        $selectedSub = $request->get('sub_bidang');

        if ($selectedSub && !$selectedBidang) {
            foreach ($masterBidang as $bidangKey => $bidang) {
                if (array_key_exists($selectedSub, $bidang['subs'])) {
                    $selectedBidang = $bidangKey;
                    break;
                }
            }
        }

        // 3. Query utama data konservasi
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        // Filter berdasarkan Bidang Utama jika dipilih
        if ($selectedBidang && isset($masterBidang[$selectedBidang])) {
            $namaBidangSearch = $masterBidang[$selectedBidang]['nama'];
            $query->whereHas('subBidang.bidang', function ($q) use ($namaBidangSearch) {
                $q->where('nama_bidang', 'like', "%{$namaBidangSearch}%");
            });
        }

        // Filter jika user telah memilih sub_bidang tertentu
        if ($selectedSub) {
            $query->whereHas('subBidang', function ($q) use ($selectedSub) {
                $q->where('kode_sub', $selectedSub)
                  ->orWhere('id', $selectedSub);
            });
        }

        $query = $this->applySearchFilter($query, $request);
        $data = !$selectedSub
            ? collect()
            : ($request->boolean('export_all') ? $query->get() : $query->paginate(10)->withQueryString());
        
        // 4. Return view dengan menyertakan data pendukung
        return view('konservasi.index', compact('data', 'masterBidang', 'selectedBidang', 'selectedSub'));
    }

    // Fitur Unduh PDF
    public function exportPdf(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $query = $this->applySearchFilter($query, $request);
        $datas = $query->get();

        $pdf = Pdf::loadView('konservasi.pdf', compact('datas'));
        return $pdf->download('Data_Konservasi_SIDAK_BKSDA_' . date('Y-m-d') . '.pdf');
    }

    // Fitur Unduh Excel (CSV)
    public function exportExcel(Request $request)
    {
        $query = DataKonservasi::with('subBidang.bidang')->latest();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $query = $this->applySearchFilter($query, $request);
        $datas = $query->get();

        $filename = 'Data_Konservasi_SIDAK_BKSDA_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($datas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['No', 'Bidang Utama', 'Sub-Bidang / Kategori', 'Bulan', 'Tahun', 'Jumlah / Vol', 'Latitude', 'Longitude', 'Keterangan']);

            $no = 1;
            foreach ($datas as $item) {
                fputcsv($file, [
                    $no++,
                    $item->subBidang->bidang->nama_bidang ?? '-',
                    $item->subBidang->nama_sub_bidang ?? '-',
                    $item->bulan ?? '-',
                    $item->tahun ?? '-',
                    $item->jumlah ?? 0,
                    $item->latitude ?? '-',
                    $item->longitude ?? '-',
                    $item->keterangan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Halaman Form Input Data (Sudah terhubung dengan model Bidang)
    public function create()
    {
        $bidang = Bidang::all();
        $fieldsC02 = $this->fieldsBySubBidang()['C.02'];
        $lembagaQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.02'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $lembagaQuery->where('user_id', auth()->id());
        }

        $lembagaKonservasiList = $lembagaQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC02)['nama_lembaga_c02'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $fieldsC04 = $this->fieldsBySubBidang()['C.04'];
        $penangkarQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.04'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $penangkarQuery->where('user_id', auth()->id());
        }

        $penangkarList = $penangkarQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC04)['nama_penangkar_c04'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $fieldsC06 = $this->fieldsBySubBidang()['C.06'];
        $pengedarQuery = DataKonservasi::with('subBidang')
            ->whereHas('subBidang', fn ($query) => $query->where('kode_sub', 'C.06'));

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $pengedarQuery->where('user_id', auth()->id());
        }

        $pengedarList = $pengedarQuery->latest()->get()
            ->map(fn ($record) => $this->parseDetailValues($record->keterangan, $fieldsC06)['nama_pengedar_c06'] ?? null)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $spesiesKoleksiC03 = [
            'Anoa dataran rendah', 'Anoa pegunungan', 'Babirusa', 'Maleo',
            'Yaki (Monyet hitam Sulawesi)', 'Tarsius', 'Kuskus beruang',
            'Kuskus kerdil', 'Rangkong Sulawesi', 'Kakatua kecil jambul kuning',
        ];
        $negaraTujuanC09 = [
            'Australia', 'Belanda', 'Hong Kong', 'Jepang', 'Jerman', 'Korea Selatan',
            'Malaysia', 'Singapura', 'Thailand', 'Tiongkok', 'Amerika Serikat', 'Lainnya',
        ];
        $kawasanKonservasi = collect([
            'TWA Wera',
            'SM Tanjung Santigi',
            'CA Tanjung Api',
            'TWA Pulau Tokobae',
            'TWA Pulau Pasoso',
            'SM Pulau Dolangan',
            'SM Pinjan Tanjung Matop',
            'SM Pati-Pati',
            'CA Pangi Binangga',
            'CA Pamona',
            'CA Morowali',
            'SM Lombuyan',
            'TB Landusa Tomata',
            'CA Gunung Tinombala',
            'CA Gunung Sojol',
            'CA Gunung Dako',
            'TWA Bancea',
            'SM Bakiriang',
        ])->map(fn ($nama) => [
            'nama' => $nama . ' (Satker: Balai KSDA Sulawesi Tengah)',
        ])->all();

        return view('konservasi.create', compact('bidang', 'kawasanKonservasi', 'lembagaKonservasiList', 'spesiesKoleksiC03', 'penangkarList', 'pengedarList', 'negaraTujuanC09'));
    }

    // Mengambil Sub-Bidang Berdasarkan Bidang ID untuk Dropdown Dinamis
    public function getSubBidang($bidang_id)
    {
        $subBidang = SubBidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subBidang);
    }

    private function fieldsBySubBidang(): array
    {
        return [
            'A.01' => ['tahun', 'kawasan_nama', 'ada_perubahan', 'sk_parsial_nomor', 'sk_parsial_tanggal', 'sk_parsial_luas', 'sk_parsial_file', 'sk_provinsi_tersedia', 'sk_provinsi_nomor', 'sk_provinsi_tanggal', 'sk_provinsi_luas', 'sk_provinsi_file', 'sk_penetapan_tersedia', 'sk_penetapan_nomor', 'sk_penetapan_tanggal', 'sk_penetapan_luas', 'sk_penetapan_file', 'shapefile_zip'],
            'A.02' => ['tahun_rpjp', 'kawasan_nama_rpjp', 'ketersediaan_rpjp', 'sk_rpjp_nomor', 'sk_rpjp_tanggal_pengesahan', 'sk_rpjp_periode_berakhir', 'sk_rpjp_file'],
            'A.03' => ['tahun_monitoring', 'kawasan_nama_monitoring', 'ada_kegiatan_monitoring', 'jenis_kegiatan', 'nomor_batb', 'tanggal_batb', 'pal_baik', 'pal_rusak', 'pal_hilang', 'pal_total', 'panjang_pal_km', 'dokumen_batb', 'shapefile_monitoring_zip'],
            'A.04' => ['tahun_evaluasi', 'kawasan_nama_evaluasi', 'ketersediaan_evaluasi', 'tanggal_pelaksanaan_evaluasi', 'rekomendasi_evaluasi', 'tindak_lanjut_evaluasi', 'file_dokumen_evaluasi'],
            'A.05' => ['tahun_ekosistem', 'kawasan_nama_ekosistem', 'ketersediaan_ekosistem', 'shapefile_ekosistem_zip'],
            'A.06' => ['tahun_zonasi', 'kawasan_nama_zonasi', 'ketersediaan_zonasi', 'nomor_sk_zonasi', 'tanggal_sk_zonasi', 'file_sk_zonasi', 'shapefile_zonasi_zip'],
            'B.01' => ['tahun_b01', 'periode_semester', 'kawasan_nama_b01', 'ada_kegiatan_b01', 'nama_kelompok', 'jumlah_laki', 'jumlah_perempuan', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'hhbk_nihil', 'jenis_hhbk', 'pertanian_nihil', 'jenis_pertanian', 'perkebunan_nihil', 'jenis_perkebunan', 'peternakan_nihil', 'jenis_peternakan', 'perikanan_nihil', 'jenis_perikanan', 'wisata_nihil', 'jenis_wisata', 'produk_nihil', 'jenis_produk', 'pembibitan_nihil', 'jenis_pembibitan', 'lainnya_nihil', 'jenis_lainnya', 'jenis_bantuan', 'jumlah_bantuan', 'sumber_dana'],
<<<<<<< HEAD
            'B.02' => ['tahun_b02', 'kawasan_nama_b02', 'ada_akses_b02', 'jenis_pengelolaan_b02', 'jenis_akses_b02', 'jenis_dimanfaatkan_b02', 'nama_kelompok_b02', 'masyarakat_hukum_adat_b02', 'jumlah_laki_b02', 'jumlah_perempuan_b02', 'kabupaten_b02', 'kecamatan_b02', 'desa_b02', 'nomor_surat_dirjen_b02', 'nomor_pks_b02', 'tanggal_mulai_ks_b02', 'tanggal_akhir_pks_b02', 'luas_area_b02', 'zona_blok_b02', 'dokumen_kerjasama_b02', 'shapefile_kerjasama_b02'],
=======
>>>>>>> temp-fix
            'C.01' => ['satuan_kerja_c01', 'temuan_perjumpaan_spesies_c01', 'tahun_c01', 'bulan_c01', 'spesies_c01', 'spesies_lainnya_c01', 'jumlah_individu_c01', 'tanggal_perjumpaan_c01', 'latitude_c01', 'longitude_c01', 'kategori_perjumpaan_c01', 'keterangan_c01'],
            'C.02' => ['tahun_c02', 'satuan_kerja_c02', 'ada_data_lembaga_c02', 'nama_lembaga_c02', 'bentuk_lembaga_c02', 'latitude_c02', 'longitude_c02', 'alamat_lembaga_c02', 'nomor_dokumen_perizinan_c02', 'luas_areal_c02', 'tanggal_perizinan_c02', 'tanggal_berakhir_izin_c02', 'dokumen_perizinan_c02', 'keterangan_c02'],
            'C.03' => ['tahun_c03', 'periode_semester_c03', 'satuan_kerja_c03', 'ada_data_koleksi_c03', 'lembaga_konservasi_c03', 'spesies_koleksi_c03', 'nama_spesies_lainnya_c03', 'jantan_c03', 'betina_c03', 'belum_diketahui_c03', 'keterangan_c03'],
            'C.04' => ['tahun_c04', 'satuan_kerja_c04', 'ada_data_penangkaran_c04', 'nama_penangkar_c04', 'latitude_c04', 'longitude_c04', 'alamat_penangkaran_c04', 'lokasi_unit_penangkaran_c04', 'nomor_dokumen_perizinan_c04', 'luas_areal_c04', 'tanggal_perizinan_c04', 'tanggal_berakhir_izin_c04', 'dokumen_perizinan_c04', 'keterangan_c04'],
            'C.05' => ['tahun_c05', 'satuan_kerja_c05', 'ada_data_jenis_tsl_c05', 'penangkar_c05', 'spesies_ditangkar_c05', 'nama_spesies_lainnya_c05', 'jumlah_indukan_c05', 'hasil_penangkaran_c05', 'hasil_pemanfaatan_tahun_ini_c05', 'sisa_stok_hasil_pemanfaatan_c05', 'keterangan_c05'],
            'C.06' => ['tahun_c06', 'satuan_kerja_c06', 'ada_data_pengedar_c06', 'nama_pengedar_c06', 'kategori_pengedar_c06', 'latitude_c06', 'longitude_c06', 'lokasi_unit_pengedar_c06', 'nomor_dokumen_perizinan_c06', 'luas_areal_c06', 'tanggal_perizinan_c06', 'tanggal_berakhir_izin_c06', 'dokumen_perizinan_c06', 'spesies_diedarkan_c06', 'keterangan_c06'],
            'C.08' => ['tahun_c08', 'periode_triwulan_c08', 'satuan_kerja_c08', 'ada_realisasi_pengambilan_c08', 'nama_spesies_c08', 'jenis_tsl_c08', 'provinsi_c08', 'realisasi_volume_c08', 'realisasi_satuan_c08', 'keterangan_c08'],
            'C.09' => ['tahun_c09', 'periode_triwulan_c09', 'satuan_kerja_c09', 'ada_realisasi_ekspor_c09', 'penangkar_c09', 'nama_spesies_c09', 'jenis_tsl_c09', 'negara_tujuan_c09', 'realisasi_ekspor_volume_c09', 'realisasi_ekspor_satuan_c09', 'keterangan_c09'],
            'C.10' => ['tahun_c10', 'periode_triwulan_c10', 'satuan_kerja_c10', 'ada_realisasi_ekspor_c10', 'pengedar_c10', 'nama_spesies_c10', 'jenis_tsl_c10', 'negara_tujuan_c10', 'realisasi_ekspor_volume_c10', 'realisasi_ekspor_satuan_c10', 'keterangan_c10'],
            'C.11' => ['tahun_c11', 'bulan_c11', 'satuan_kerja_c11', 'ada_data_sitaan_c11', 'spesies_c11', 'asal_usul_c11', 'jantan_c11', 'betina_c11', 'belum_diketahui_c11', 'keterangan_c11'],
            'C.12' => ['tahun_c12', 'bulan_c12', 'satuan_kerja_c12', 'ada_pnb_tsl_c12', 'akun_pnb_c12', 'realisasi_pnb_c12', 'keterangan_c12'],
            'C.14' => ['tahun_c14', 'bulan_c14', 'satuan_kerja_c14', 'ada_interaksi_negatif_c14', 'spesies_c14', 'spesies_lainnya_c14', 'tanggal_kejadian_c14', 'jumlah_individu_c14', 'satwa_mati_c14', 'latitude_c14', 'longitude_c14', 'meninggal_c14', 'cedera_c14', 'kerusakan_kebun_c14', 'kerusakan_bangunan_c14', 'kambing_c14', 'sapi_c14', 'kerbau_c14', 'anjing_c14', 'babi_c14', 'unggas_c14', 'taksiran_kerugian_c14', 'upaya_penanggulangan_c14', 'keterangan_c14'],
<<<<<<< HEAD
        ];
    }
=======
            'B.02' => ['tahun_b02', 'kawasan_nama_b02', 'ada_akses_b02', 'jenis_pengelolaan_b02', 'jenis_akses_b02', 'jenis_dimanfaatkan_b02', 'nama_kelompok_b02', 'masyarakat_hukum_adat_b02', 'jumlah_laki_b02', 'jumlah_perempuan_b02', 'kabupaten_b02', 'kecamatan_b02', 'desa_b02', 'nomor_surat_dirjen_b02', 'nomor_pks_b02', 'tanggal_mulai_ks_b02', 'tanggal_akhir_pks_b02', 'luas_area_b02', 'zona_blok_b02', 'dokumen_kerjasama_b02', 'shapefile_kerjasama_b02'],
        ];}
>>>>>>> temp-fix

    private function fileUploadConfig(): array
    {
        return [
            'sk_parsial_file' => ['sub' => 'A.01', 'folder' => 'dokumen_sk', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'File SK parsial'],
            'sk_provinsi_file' => ['sub' => 'A.01', 'folder' => 'dokumen_sk', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'File SK provinsi'],
            'sk_penetapan_file' => ['sub' => 'A.01', 'folder' => 'dokumen_sk', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'File SK penetapan'],
            'shapefile_zip' => ['sub' => 'A.01', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile kawasan'],
            'sk_rpjp_file' => ['sub' => 'A.02', 'folder' => 'dokumen_rpjp', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'File SK RPJP'],
            'dokumen_batb' => ['sub' => 'A.03', 'folder' => 'dokumen_batb', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen BATB'],
            'shapefile_monitoring_zip' => ['sub' => 'A.03', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile monitoring'],
            'file_dokumen_evaluasi' => ['sub' => 'A.04', 'folder' => 'dokumen_evaluasi', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen evaluasi'],
            'shapefile_ekosistem_zip' => ['sub' => 'A.05', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile ekosistem'],
            'file_sk_zonasi' => ['sub' => 'A.06', 'folder' => 'dokumen_zonasi', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'File SK zonasi'],
            'shapefile_zonasi_zip' => ['sub' => 'A.06', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile zonasi'],
            'dokumen_kerjasama_b02' => ['sub' => 'B.02', 'folder' => 'dokumen_kerjasama', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen kerjasama B.02'],
            'shapefile_kerjasama_b02' => ['sub' => 'B.02', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile kerjasama B.02'],
            'dokumen_perizinan_c02' => ['sub' => 'C.02', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen perizinan C.02'],
            'dokumen_perizinan_c04' => ['sub' => 'C.04', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen perizinan C.04'],
            'dokumen_perizinan_c06' => ['sub' => 'C.06', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen perizinan C.06'],
            'dokumen_d03' => ['sub' => 'D.03', 'folder' => 'dokumen_desain_tapak', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen desain tapak D.03'],
            'shapefile_d03' => ['sub' => 'D.03', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile D.03'],
            'foto_odtwa' => ['sub' => 'D.04', 'folder' => 'foto_odtwa', 'mimes' => 'jpg,jpeg,png', 'max' => 10240, 'label' => 'Foto ODTWA'],
            'dokumen_perizinan_pbp_d06' => ['sub' => 'D.06', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen perizinan D.06'],
            'shapefile_pbp_d06' => ['sub' => 'D.06', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile D.06'],
            'foto_sarana_prasarana_d07' => ['sub' => 'D.07', 'folder' => 'foto_sarana_prasarana', 'mimes' => 'jpg,jpeg,png', 'max' => 10240, 'label' => 'Foto sarana prasarana D.07', 'multiple' => true, 'max_files' => 3, 'required_if' => ['ada_sarana_prasarana_d07', 'ya']],
            'shapefile_d10' => ['sub' => 'D.10', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile D.10'],
            'dokumen_sk_d11' => ['sub' => 'D.11', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen SK D.11'],
            'shapefile_d11' => ['sub' => 'D.11', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile D.11'],
            'dokumen_perizinan_d14' => ['sub' => 'D.14', 'folder' => 'dokumen_perizinan', 'mimes' => 'pdf', 'max' => 10240, 'label' => 'Dokumen perizinan D.14'],
            'shapefile_d14' => ['sub' => 'D.14', 'folder' => 'shapefiles', 'mimes' => 'zip', 'max' => 10240, 'label' => 'Shapefile D.14'],
        ];
    }

    private function fileUploadRules(): array
    {
        $rules = [];
        foreach ($this->fileUploadConfig() as $field => $config) {
            if (!empty($config['multiple'])) {
                $rules[$field] = (!empty($config['required_if'])
                    ? 'required_if:' . implode(',', $config['required_if']) . '|'
                    : 'nullable|') . 'array|max:' . $config['max_files'];
                $rules[$field . '.*'] = 'file|mimes:' . $config['mimes'] . '|max:' . $config['max'];
                continue;
            }

            $rules[$field] = 'nullable|file|mimes:' . $config['mimes'] . '|max:' . $config['max'];
        }

        return $rules;
    }

    private function fileUploadMessages(): array
    {
        $messages = [];
        foreach ($this->fileUploadConfig() as $field => $config) {
            $maxMb = number_format($config['max'] / 1024, $config['max'] % 1024 === 0 ? 0 : 1);
            $fieldName = !empty($config['multiple']) ? $field . '.*' : $field;
            $messages[$fieldName . '.uploaded'] = "{$config['label']} gagal diterima server. Maksimal {$maxMb} MB per file; coba unggah ulang. Jika berulang, periksa batas upload PHP atau folder temporary server.";
            $messages[$fieldName . '.mimes'] = "{$config['label']} harus berformat " . strtoupper(str_replace(',', '/', $config['mimes'])) . '.';
            $messages[$fieldName . '.max'] = "Ukuran {$config['label']} maksimal {$maxMb} MB per file.";
            if (!empty($config['multiple'])) {
                $messages[$field . '.max'] = "{$config['label']} maksimal {$config['max_files']} file.";
            }
        }

        return $messages;
    }

    private function storeUploadedFiles(Request $request, string $kodeSubBidang, array $existingValues = []): array
    {
        $details = [];
        foreach ($this->fileUploadConfig() as $field => $config) {
            if ($config['sub'] !== $kodeSubBidang) {
                continue;
            }

            if ($request->hasFile($field)) {
                $files = $request->file($field);
                foreach (is_array($files) ? $files : [$files] as $file) {
                    if (!$file || !$file->isValid()) {
                        continue;
                    }

                    $storedPath = $file->store($config['folder'], 'public');
                    $details[] = ucwords(str_replace('_', ' ', $field)) . ': ' . $storedPath;
                }
            } elseif (filled($existingValues[$field] ?? null)) {
                $details[] = ucwords(str_replace('_', ' ', $field)) . ': ' . $existingValues[$field];
            }
        }

        return $details;
    }

    private function formFieldLabel(string $field): string
    {
        $labels = [
            'tahun' => 'Tahun', 'tahun_rpjp' => 'Tahun', 'tahun_monitoring' => 'Tahun',
            'tahun_evaluasi' => 'Tahun', 'tahun_ekosistem' => 'Tahun', 'tahun_zonasi' => 'Tahun', 'tahun_b01' => 'Tahun', 'tahun_b02' => 'Tahun',
            'kawasan_nama' => 'Kawasan Konservasi', 'kawasan_nama_rpjp' => 'Kawasan Konservasi',
            'kawasan_nama_monitoring' => 'Kawasan Konservasi', 'kawasan_nama_evaluasi' => 'Kawasan Konservasi',
            'kawasan_nama_ekosistem' => 'Kawasan Konservasi', 'kawasan_nama_zonasi' => 'Kawasan Konservasi',
            'kawasan_nama_b01' => 'Kawasan Konservasi', 'jumlah_laki' => 'Jumlah Laki-laki',
            'kawasan_nama_b02' => 'Kawasan Konservasi', 'ada_akses_b02' => 'Ada Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi',
            'jenis_pengelolaan_b02' => 'Jenis Pengelolaan Bersama Masyarakat', 'jenis_akses_b02' => 'Jenis Akses',
            'jenis_dimanfaatkan_b02' => 'Jenis yang Dimanfaatkan', 'nama_kelompok_b02' => 'Nama Kelompok',
            'masyarakat_hukum_adat_b02' => 'Masyarakat Hukum Adat', 'jumlah_laki_b02' => 'Laki-laki',
            'jumlah_perempuan_b02' => 'Perempuan', 'kabupaten_b02' => 'Kabupaten/Kota',
            'kecamatan_b02' => 'Kecamatan', 'desa_b02' => 'Kelurahan/Desa',
            'nomor_surat_dirjen_b02' => 'Nomor Surat Dirjen KSDAE', 'nomor_pks_b02' => 'Nomor Perjanjian Kerjasama',
            'tanggal_mulai_ks_b02' => 'Tanggal Mulai KS', 'tanggal_akhir_pks_b02' => 'Tanggal Berakhir PKS',
            'luas_area_b02' => 'Luas Area Pemanfaatan', 'zona_blok_b02' => 'Zona/Blok',
            'jumlah_perempuan' => 'Jumlah Perempuan', 'jenis_hhbk' => 'Pemungutan HHBK',
            'jenis_pertanian' => 'Pertanian', 'jenis_perkebunan' => 'Perkebunan',
            'jenis_peternakan' => 'Peternakan', 'jenis_perikanan' => 'Perikanan',
            'jenis_wisata' => 'Jasa Wisata', 'jenis_produk' => 'Usaha Penghasil Produk',
            'jenis_pembibitan' => 'Pembibitan', 'jenis_lainnya' => 'Jenis Usaha Lainnya',
            'satuan_kerja_c01' => 'Satuan Kerja', 'temuan_perjumpaan_spesies_c01' => 'Temuan Perjumpaan Spesies',
            'tahun_c01' => 'Tahun', 'bulan_c01' => 'Periode Bulan',
            'tahun_c02' => 'Tahun', 'satuan_kerja_c02' => 'Satuan Kerja', 'ada_data_lembaga_c02' => 'Ada Data Lembaga Konservasi',
            'tahun_c03' => 'Tahun', 'periode_semester_c03' => 'Periode Semester',
            'satuan_kerja_c03' => 'Satuan Kerja', 'ada_data_koleksi_c03' => 'Ada Data Koleksi TSL',
            'tahun_c04' => 'Tahun', 'satuan_kerja_c04' => 'Satuan Kerja', 'ada_data_penangkaran_c04' => 'Ada Data Penangkaran',
            'nama_penangkar_c04' => 'Nama Penangkar', 'latitude_c04' => 'Koordinat X', 'longitude_c04' => 'Koordinat Y',
            'alamat_penangkaran_c04' => 'Alamat Penangkaran', 'lokasi_unit_penangkaran_c04' => 'Lokasi Unit Penangkaran',
            'nomor_dokumen_perizinan_c04' => 'Nomor Dokumen Perizinan', 'luas_areal_c04' => 'Luas Areal',
            'tanggal_perizinan_c04' => 'Tanggal Perizinan', 'tanggal_berakhir_izin_c04' => 'Tanggal Berakhir Izin',
            'keterangan_c04' => 'Keterangan',
            'tahun_c05' => 'Tahun', 'satuan_kerja_c05' => 'Satuan Kerja', 'ada_data_jenis_tsl_c05' => 'Ada Jenis TSL yang Ditangkarkan',
            'penangkar_c05' => 'Nama Penangkar', 'spesies_ditangkar_c05' => 'Spesies yang Ditangkarkan',
            'nama_spesies_lainnya_c05' => 'Nama Spesies Lainnya', 'jumlah_indukan_c05' => 'Jumlah Indukan',
            'hasil_penangkaran_c05' => 'Hasil Penangkaran', 'hasil_pemanfaatan_tahun_ini_c05' => 'Hasil Pemanfaatan Tahun Ini',
            'sisa_stok_hasil_pemanfaatan_c05' => 'Sisa Stok Hasil Pemanfaatan', 'keterangan_c05' => 'Keterangan',
            'tahun_c06' => 'Tahun', 'satuan_kerja_c06' => 'Satuan Kerja', 'ada_data_pengedar_c06' => 'Ada Data Pengedar TSL',
            'nama_pengedar_c06' => 'Nama Pengedar TSL', 'kategori_pengedar_c06' => 'Kategori Pengedar',
            'latitude_c06' => 'Koordinat X', 'longitude_c06' => 'Koordinat Y', 'lokasi_unit_pengedar_c06' => 'Lokasi Unit Pengedar TSL',
            'nomor_dokumen_perizinan_c06' => 'Nomor Dokumen Perizinan', 'luas_areal_c06' => 'Luas Areal',
            'tanggal_perizinan_c06' => 'Tanggal Perizinan', 'tanggal_berakhir_izin_c06' => 'Tanggal Berakhir Izin',
            'spesies_diedarkan_c06' => 'Spesies yang Diedarkan', 'keterangan_c06' => 'Keterangan',
            'tahun_c08' => 'Tahun', 'periode_triwulan_c08' => 'Periode Triwulan',
            'satuan_kerja_c08' => 'Satuan Kerja', 'ada_realisasi_pengambilan_c08' => 'Ada Realisasi Pengambilan/Penangkapan TSL',
            'nama_spesies_c08' => 'Nama Species', 'jenis_tsl_c08' => 'Jenis', 'provinsi_c08' => 'Provinsi',
            'realisasi_volume_c08' => 'Realisasi Tangkap Volume', 'realisasi_satuan_c08' => 'Realisasi Tangkap Satuan',
            'keterangan_c08' => 'Keterangan',
            'tahun_c09' => 'Tahun', 'periode_triwulan_c09' => 'Periode Triwulan',
            'satuan_kerja_c09' => 'Satuan Kerja', 'ada_realisasi_ekspor_c09' => 'Ada Realisasi Ekspor TSL Penangkaran',
            'penangkar_c09' => 'Nama Penangkar', 'nama_spesies_c09' => 'Nama Spesies',
            'jenis_tsl_c09' => 'Jenis', 'negara_tujuan_c09' => 'Negara Tujuan',
            'realisasi_ekspor_volume_c09' => 'Realisasi Ekspor Volume', 'realisasi_ekspor_satuan_c09' => 'Realisasi Ekspor Satuan',
            'keterangan_c09' => 'Keterangan',
            'tahun_c10' => 'Tahun', 'periode_triwulan_c10' => 'Periode Triwulan',
            'satuan_kerja_c10' => 'Satuan Kerja', 'ada_realisasi_ekspor_c10' => 'Ada Realisasi Ekspor TSL Pengambilan dari Alam',
            'pengedar_c10' => 'Nama Pengedar TSL', 'nama_spesies_c10' => 'Nama Spesies',
            'jenis_tsl_c10' => 'Jenis', 'negara_tujuan_c10' => 'Negara Tujuan',
            'realisasi_ekspor_volume_c10' => 'Realisasi Ekspor Volume', 'realisasi_ekspor_satuan_c10' => 'Realisasi Ekspor Satuan',
            'keterangan_c10' => 'Keterangan',
            'tahun_c11' => 'Tahun', 'bulan_c11' => 'Periode Bulan',
            'satuan_kerja_c11' => 'Satuan Kerja', 'ada_data_sitaan_c11' => 'Ada Data Sitaan/Penyerahan/Penyelamatan Satwa',
            'spesies_c11' => 'Spesies', 'asal_usul_c11' => 'Asal-usul',
            'jantan_c11' => 'Jantan', 'betina_c11' => 'Betina',
            'belum_diketahui_c11' => 'Belum Diketahui', 'keterangan_c11' => 'Keterangan',
            'tahun_c12' => 'Tahun', 'bulan_c12' => 'Periode Bulan',
            'satuan_kerja_c12' => 'Satuan Kerja', 'ada_pnb_tsl_c12' => 'Ada PNB Pemanfaatan TSL',
            'akun_pnb_c12' => 'Akun PNB', 'realisasi_pnb_c12' => 'Realisasi PNB', 'keterangan_c12' => 'Keterangan',
            'tahun_c14' => 'Tahun', 'bulan_c14' => 'Periode Bulan', 'satuan_kerja_c14' => 'Satuan Kerja',
            'ada_interaksi_negatif_c14' => 'Ada Interaksi Negatif Satwa Liar dan Manusia',
            'spesies_c14' => 'Spesies yang Terlibat Interaksi Negatif', 'spesies_lainnya_c14' => 'Spesies Lainnya',
            'tanggal_kejadian_c14' => 'Tanggal Kejadian', 'jumlah_individu_c14' => 'Jumlah Individu',
            'satwa_mati_c14' => 'Satwa Mati', 'latitude_c14' => 'Koordinat X', 'longitude_c14' => 'Koordinat Y',
            'meninggal_c14' => 'Meninggal', 'cedera_c14' => 'Cedera',
            'kerusakan_kebun_c14' => 'Kerusakan Kebun/Lahan', 'kerusakan_bangunan_c14' => 'Kerusakan Bangunan',
            'kambing_c14' => 'Kambing', 'sapi_c14' => 'Sapi', 'kerbau_c14' => 'Kerbau',
            'anjing_c14' => 'Anjing', 'babi_c14' => 'Babi', 'unggas_c14' => 'Unggas',
            'taksiran_kerugian_c14' => 'Taksiran Kerugian', 'upaya_penanggulangan_c14' => 'Upaya Penanggulangan',
            'keterangan_c14' => 'Keterangan',
            'lembaga_konservasi_c03' => 'Nama Lembaga Konservasi', 'spesies_koleksi_c03' => 'Spesies Koleksi',
            'nama_spesies_lainnya_c03' => 'Nama Spesies Lainnya', 'jantan_c03' => 'Jantan',
            'betina_c03' => 'Betina', 'belum_diketahui_c03' => 'Belum Diketahui', 'keterangan_c03' => 'Keterangan',
            'nama_lembaga_c02' => 'Nama Lembaga Konservasi', 'bentuk_lembaga_c02' => 'Bentuk Lembaga',
            'latitude_c02' => 'Koordinat X', 'longitude_c02' => 'Koordinat Y',
            'alamat_lembaga_c02' => 'Alamat Lembaga Konservasi', 'nomor_dokumen_perizinan_c02' => 'Nomor Dokumen Perizinan',
            'luas_areal_c02' => 'Luas Areal', 'tanggal_perizinan_c02' => 'Tanggal Perizinan',
            'tanggal_berakhir_izin_c02' => 'Tanggal Berakhir Izin', 'keterangan_c02' => 'Keterangan',
            'spesies_c01' => 'Spesies', 'spesies_lainnya_c01' => 'Nama Spesies Lainnya',
            'jumlah_individu_c01' => 'Jumlah Individu', 'tanggal_perjumpaan_c01' => 'Tanggal Perjumpaan',
            'latitude_c01' => 'Koordinat X', 'longitude_c01' => 'Koordinat Y',
            'kategori_perjumpaan_c01' => 'Kategori Perjumpaan', 'keterangan_c01' => 'Keterangan',
        ];
        $label = $labels[$field] ?? ucwords(str_replace('_', ' ', $field));

        return str_replace(
            ['Sk ', 'Rpjp', 'Batb', 'Hhbk', 'Zip'],
            ['SK ', 'RPJP', 'BATB', 'HHBK', 'ZIP'],
            $label
        );
    }

    private function parseDetailValues(?string $keterangan, array $fields): array
    {
        $values = [];

        foreach (explode(' | ', $keterangan ?? '') as $detail) {
            $separator = strpos($detail, ': ');
            if ($separator === false) {
                continue;
            }

            $label = trim(substr($detail, 0, $separator));
            $value = trim(substr($detail, $separator + 2));

            foreach ($fields as $field) {
                if (strcasecmp($label, $this->formFieldLabel($field)) === 0) {
                    $values[$field] = $value;
                    break;
                }
            }
        }

        if ($values === [] && filled($keterangan) && !str_contains($keterangan, ': ')) {
            $values['keterangan'] = $keterangan;
        }

        return $values;
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        $request->validate(array_merge([
            'sub_bidang_id'             => 'required|exists:ref_sub_bidang,id',
<<<<<<< HEAD
=======
            'sk_parsial_file'           => 'nullable|mimes:pdf|max:2048',
            'sk_provinsi_file'          => 'nullable|mimes:pdf|max:2048',
            'sk_penetapan_file'         => 'nullable|mimes:pdf|max:2048',
            'shapefile_zip'             => 'nullable|mimes:zip|max:10240',
            'sk_rpjp_file'              => 'nullable|mimes:pdf|max:20480',
            'dokumen_batb'              => 'nullable|mimes:pdf|max:20480',
            'shapefile_monitoring_zip'  => 'nullable|mimes:zip|max:10240',
            'file_dokumen_evaluasi'     => 'nullable|mimes:pdf|max:20480',
            'shapefile_ekosistem_zip'   => 'nullable|mimes:zip|max:10240',
            'file_sk_zonasi'            => 'nullable|mimes:pdf|max:2048',
            'shapefile_zonasi_zip'      => 'nullable|mimes:zip|max:10240',
            'dokumen_d03'               => 'nullable|mimes:pdf|max:10240',
            'shapefile_d03'             => 'nullable|mimes:zip|max:10240',
            'dokumen_perizinan_c02'     => 'nullable|mimes:pdf|max:2048',
            'dokumen_perizinan_c04'     => 'nullable|mimes:pdf|max:2048',
            'dokumen_perizinan_c06'     => 'nullable|mimes:pdf|max:2048',
>>>>>>> temp-fix
            'tahun_b02' => 'nullable|integer|min:2000|max:2100',
            'ada_akses_b02' => 'nullable|in:ya,tidak',
            'jenis_pengelolaan_b02' => 'required_if:ada_akses_b02,ya|nullable|in:akses_tradisional,kemitraan_konservasi',
            'jenis_akses_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'jenis_dimanfaatkan_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:2000',
            'nama_kelompok_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'masyarakat_hukum_adat_b02' => 'required_if:ada_akses_b02,ya|nullable|in:ya,tidak',
            'jumlah_laki_b02' => 'required_if:ada_akses_b02,ya|nullable|integer|min:0',
            'jumlah_perempuan_b02' => 'required_if:ada_akses_b02,ya|nullable|integer|min:0',
            'kabupaten_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'kecamatan_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'desa_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'nomor_surat_dirjen_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'nomor_pks_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'tanggal_mulai_ks_b02' => 'required_if:ada_akses_b02,ya|nullable|date',
            'tanggal_akhir_pks_b02' => 'required_if:ada_akses_b02,ya|nullable|date|after_or_equal:tanggal_mulai_ks_b02',
            'luas_area_b02' => 'required_if:ada_akses_b02,ya|nullable|numeric|min:0',
            'zona_blok_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
<<<<<<< HEAD
        ], $this->fileUploadRules()), $this->fileUploadMessages());
=======
            'dokumen_kerjasama_b02' => 'nullable|mimes:pdf|max:2048',
            'shapefile_kerjasama_b02' => 'nullable|mimes:zip|max:10240',
        ], [
            'sk_parsial_file.uploaded' => 'File SK parsial gagal diunggah. Pastikan file PDF berukuran maksimal 2 MB, atau kosongkan lampiran untuk menyimpan data tanpa file.',
            'sk_parsial_file.mimes' => 'File SK parsial harus berformat PDF.',
            'sk_parsial_file.max' => 'Ukuran file SK parsial maksimal 2 MB.',
        ]);
>>>>>>> temp-fix

        $subBidang = SubBidang::findOrFail($request->sub_bidang_id);
        $yearFields = [
            'A.01' => 'tahun',
            'A.02' => 'tahun_rpjp',
            'A.03' => 'tahun_monitoring',
            'A.04' => 'tahun_evaluasi',
            'A.05' => 'tahun_ekosistem',
            'A.06' => 'tahun_zonasi',
            'B.01' => 'tahun_b01',
<<<<<<< HEAD
            'B.02' => 'tahun_b02',
=======
>>>>>>> temp-fix
            'C.01' => 'tahun_c01',
            'C.02' => 'tahun_c02',
            'C.03' => 'tahun_c03',
            'C.04' => 'tahun_c04',
            'C.05' => 'tahun_c05',
            'C.06' => 'tahun_c06',
            'C.08' => 'tahun_c08',
            'C.09' => 'tahun_c09',
            'C.10' => 'tahun_c10',
            'C.11' => 'tahun_c11',
            'C.12' => 'tahun_c12',
            'C.14' => 'tahun_c14',
<<<<<<< HEAD
=======
            'B.02' => 'tahun_b02',
>>>>>>> temp-fix
        ];
        $tahun = $request->input($yearFields[$subBidang->kode_sub] ?? '', date('Y'));

        $jumlah = match ($subBidang->kode_sub) {
            'A.03' => $request->filled('pal_baik') || $request->filled('pal_rusak') || $request->filled('pal_hilang')
                ? (int) $request->input('pal_baik', 0) + (int) $request->input('pal_rusak', 0) + (int) $request->input('pal_hilang', 0)
                : null,
            'B.01' => $request->filled('jumlah_laki') || $request->filled('jumlah_perempuan')
                ? (int) $request->input('jumlah_laki', 0) + (int) $request->input('jumlah_perempuan', 0)
                : null,
<<<<<<< HEAD
            'B.02' => $request->filled('jumlah_laki_b02') || $request->filled('jumlah_perempuan_b02')
                ? (int) $request->input('jumlah_laki_b02', 0) + (int) $request->input('jumlah_perempuan_b02', 0)
                : null,
=======
>>>>>>> temp-fix
            'C.03' => $request->input('ada_data_koleksi_c03') === 'ya'
                ? (int) $request->input('jantan_c03', 0) + (int) $request->input('betina_c03', 0) + (int) $request->input('belum_diketahui_c03', 0)
                : null,
            'C.05' => $request->input('ada_data_jenis_tsl_c05') === 'ya'
                ? (int) $request->input('jumlah_indukan_c05', 0) + (int) $request->input('hasil_penangkaran_c05', 0) + (int) $request->input('hasil_pemanfaatan_tahun_ini_c05', 0) + (int) $request->input('sisa_stok_hasil_pemanfaatan_c05', 0)
                : null,
            'C.11' => $request->input('ada_data_sitaan_c11') === 'ya'
                ? (int) $request->input('jantan_c11', 0) + (int) $request->input('betina_c11', 0) + (int) $request->input('belum_diketahui_c11', 0)
                : null,
            'C.12' => $request->input('ada_pnb_tsl_c12') === 'ya'
                ? (int) $request->input('realisasi_pnb_c12', 0)
                : null,
            'C.14' => $request->input('ada_interaksi_negatif_c14') === 'ya'
                ? (int) $request->input('jumlah_individu_c14', 0)
                : null,
<<<<<<< HEAD
=======
            'B.02' => $request->filled('jumlah_laki_b02') || $request->filled('jumlah_perempuan_b02')
                ? (int) $request->input('jumlah_laki_b02', 0) + (int) $request->input('jumlah_perempuan_b02', 0)
                : null,
>>>>>>> temp-fix
            default => null,
        };

        $fieldsBySubBidang = $this->fieldsBySubBidang();

        $details = [];
        $activeFields = array_merge($fieldsBySubBidang[$subBidang->kode_sub] ?? [], ['keterangan']);
        foreach ($request->only($activeFields) as $field => $value) {
            if (!is_scalar($value) || $value === '') {
                continue;
            }

            $label = $this->formFieldLabel($field);
            $details[] = $label . ': ' . ($value === 'on' ? 'Ya' : $value);
        }

<<<<<<< HEAD
        $details = array_merge($details, $this->storeUploadedFiles($request, $subBidang->kode_sub));
=======
        $fileInputs = [
            'sk_parsial_file'           => 'dokumen_sk',
            'sk_provinsi_file'          => 'dokumen_sk',
            'sk_penetapan_file'         => 'dokumen_sk',
            'shapefile_zip'             => 'shapefiles',
            'sk_rpjp_file'              => 'dokumen_rpjp',
            'dokumen_batb'              => 'dokumen_batb',
            'shapefile_monitoring_zip'  => 'shapefiles',
            'file_dokumen_evaluasi'     => 'dokumen_evaluasi',
            'shapefile_ekosistem_zip'   => 'shapefiles',
            'file_sk_zonasi'            => 'dokumen_zonasi',
            'shapefile_zonasi_zip'      => 'shapefiles',
            'dokumen_d03'               => 'dokumen_desain_tapak',
            'shapefile_d03'             => 'shapefiles',
            'dokumen_perizinan_c02'     => 'dokumen_perizinan',
            'dokumen_perizinan_c04'     => 'dokumen_perizinan',
            'dokumen_perizinan_c06'     => 'dokumen_perizinan',
            'dokumen_kerjasama_b02'     => 'dokumen_kerjasama',
            'shapefile_kerjasama_b02'   => 'shapefiles',
        ];

        foreach ($fileInputs as $inputName => $folderPath) {
            if (in_array($inputName, $fieldsBySubBidang[$subBidang->kode_sub] ?? [], true) && $request->hasFile($inputName)) {
                $storedPath = $request->file($inputName)->store($folderPath, 'public');
                $details[] = ucwords(str_replace('_', ' ', $inputName)) . ': ' . $storedPath;
            }
        }
>>>>>>> temp-fix

        $keteranganFinal = count($details) > 0 ? implode(' | ', $details) : '-';

        $payload = [
            'sub_bidang_id' => $request->sub_bidang_id,
            'tahun'         => $tahun,
            'bulan'         => $request->input('bulan_c14', $request->input('bulan_c12', $request->input('bulan_c11', $request->input('bulan_c01', $request->bulan)))),
            'latitude'      => $request->input('latitude_c14', $request->input('latitude_c06', $request->input('latitude_c04', $request->input('latitude_c02', $request->input('latitude_c01', $request->latitude))))),
            'longitude'     => $request->input('longitude_c14', $request->input('longitude_c06', $request->input('longitude_c04', $request->input('longitude_c02', $request->input('longitude_c01', $request->longitude))))),
            'jumlah'        => $jumlah,
            'keterangan'    => $keteranganFinal,
        ];

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $payload['user_id'] = auth()->id();
        }

        DataKonservasi::create($payload);

        return redirect()->route('konservasi.index')->with('success', 'Data Konservasi Berhasil Disimpan ke Sistem SIDAK!');
    }

    // Proses Hapus Data
    public function destroy($id)
    {
        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $data = $query->findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data Konservasi berhasil dihapus!');
    }

    // Halaman Peta GIS Kawasan
    public function peta()
    {
        $query = DataKonservasi::with('subBidang.bidang')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $locations = $query->get();

        return view('konservasi.peta', compact('locations'));
    }

    // Halaman Dashboard Analytics
    public function dashboard()
    {
        $userQuery = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $userQuery->where('user_id', auth()->id());
        }

        $totalData = (clone $userQuery)->count();
        $totalLokasi = (clone $userQuery)->whereNotNull('latitude')
                                         ->whereNotNull('longitude')
                                         ->where('latitude', '!=', '')
                                         ->where('longitude', '!=', '')
                                         ->count();

        $bidangDefinitions = [
            ['nama' => 'Perencanaan Konservasi', 'kode' => 'A', 'total_subbidang' => 7, 'warna' => '#0f766e'],
            ['nama' => 'Konservasi Kawasan', 'kode' => 'B', 'total_subbidang' => 13, 'warna' => '#ca8a04'],
            ['nama' => 'Konservasi Spesies dan Genetik', 'kode' => 'C', 'total_subbidang' => 16, 'warna' => '#e11d48'],
            ['nama' => 'Pemanfaatan Jasa Lingkungan', 'kode' => 'D', 'total_subbidang' => 16, 'warna' => '#0284c7'],
            ['nama' => 'Pemulihan Ekosistem dan Bina Area Preservasi', 'kode' => 'E', 'total_subbidang' => 7, 'warna' => '#65a30d'],
            ['nama' => 'Kesekretariatan', 'kode' => 'F', 'total_subbidang' => 8, 'warna' => '#ea580c'],
        ];

        $bidangStats = [];
        foreach ($bidangDefinitions as $definition) {
            $bidangStats[$definition['nama']] = $definition + [
                'jumlah_entri' => 0,
                'subbidang_terisi' => 0,
            ];
        }

        $subBidangStats = [];
        foreach (SubBidang::with('bidang')->get() as $subBidang) {
            $subBidangStats[$subBidang->id] = [
                'kode' => $subBidang->kode_sub,
                'nama' => $subBidang->nama_sub_bidang,
                'nama_bidang' => $subBidang->bidang->nama_bidang ?? 'Bidang tidak diketahui',
                'satuan' => match ($subBidang->kode_sub) {
                    'A.03' => 'pal',
                    'B.01' => 'orang',
                    'B.02' => 'orang',
                    default => 'unit',
                },
                'jumlah_entri' => 0,
                'jumlah_volume' => 0,
                'entri_bervolume' => 0,
            ];
        }

        $allData = (clone $userQuery)->with('subBidang.bidang')->get();

        foreach ($allData as $item) {
            $subBidang = $item->subBidang;
            if (!$subBidang) {
                continue;
            }

            $namaBidang = $subBidang->bidang->nama_bidang ?? null;
            if (isset($bidangStats[$namaBidang])) {
                $bidangStats[$namaBidang]['jumlah_entri']++;
            }

            if (!isset($subBidangStats[$subBidang->id])) {
                continue;
            }

            $subBidangStats[$subBidang->id]['jumlah_entri']++;
            if ($item->jumlah !== null) {
                $subBidangStats[$subBidang->id]['jumlah_volume'] += (float) $item->jumlah;
                $subBidangStats[$subBidang->id]['entri_bervolume']++;
            }
        }

        foreach ($subBidangStats as $subStat) {
            if ($subStat['jumlah_entri'] > 0 && isset($bidangStats[$subStat['nama_bidang']])) {
                $bidangStats[$subStat['nama_bidang']]['subbidang_terisi']++;
            }
        }

        $bidangChart = array_values($bidangStats);
        $subBidangChart = array_values(array_filter(
            $subBidangStats,
            fn ($subStat) => $subStat['jumlah_entri'] > 0
        ));
        $volumeChart = array_values(array_filter(
            $subBidangStats,
            fn ($subStat) => $subStat['entri_bervolume'] > 0
        ));
        $totalSubBidangTerisi = count($subBidangChart);
        $totalSubBidang = array_sum(array_column($bidangDefinitions, 'total_subbidang'));
        $totalEntriBervolume = $allData->filter(fn ($item) => $item->jumlah !== null)->count();

        $recentData = (clone $userQuery)->with('subBidang.bidang')->latest()->take(5)->get();

        return view('konservasi.dashboard', compact(
            'totalData',
            'totalLokasi',
            'totalEntriBervolume',
            'totalSubBidangTerisi',
            'totalSubBidang',
            'bidangChart',
            'subBidangChart',
            'volumeChart',
            'recentData'
        ));
    }

    // Halaman Edit Data
    public function edit($id)
    {
        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $item = $query->findOrFail($id);
        $bidangs = \App\Models\Bidang::all();

        $currentBidangId = $item->subBidang ? $item->subBidang->bidang_id : null;
        $subBidangs = \App\Models\SubBidang::where('bidang_id', $currentBidangId)->get();
        $fieldsBySubBidang = $this->fieldsBySubBidang();
        $currentKode = $item->subBidang->kode_sub ?? '';
        $currentFields = array_merge($fieldsBySubBidang[$currentKode] ?? [], ['keterangan']);
        $detailValues = $this->parseDetailValues($item->keterangan, $currentFields);
        $editFieldsBySubBidang = [];

        foreach ($fieldsBySubBidang as $kode => $fields) {
            $editFieldsBySubBidang[$kode] = array_map(fn ($field) => [
                'name' => $field,
                'label' => $this->formFieldLabel($field),
            ], $fields);
        }

        return view('konservasi.edit', compact(
            'item', 'bidangs', 'subBidangs', 'currentBidangId', 'currentKode',
            'detailValues', 'editFieldsBySubBidang'
        ));
    }

    // Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate(array_merge([
            'bidang_id'     => 'required|exists:ref_bidang,id',
            'sub_bidang_id' => 'required|exists:ref_sub_bidang,id',
            'tahun'         => 'nullable|numeric',
            'tahun_rpjp'    => 'nullable|numeric',
            'tahun_monitoring' => 'nullable|numeric',
            'tahun_evaluasi' => 'nullable|numeric',
            'tahun_ekosistem' => 'nullable|numeric',
            'tahun_zonasi'  => 'nullable|numeric',
            'tahun_b01'     => 'nullable|numeric',
            'tahun_b02'     => 'nullable|numeric',
<<<<<<< HEAD
=======
            'sk_parsial_file' => 'nullable|mimes:pdf|max:2048',
            'sk_provinsi_file' => 'nullable|mimes:pdf|max:2048',
            'sk_penetapan_file' => 'nullable|mimes:pdf|max:2048',
            'shapefile_zip' => 'nullable|mimes:zip|max:10240',
            'sk_rpjp_file'  => 'nullable|mimes:pdf|max:20480',
            'dokumen_batb'  => 'nullable|mimes:pdf|max:20480',
            'shapefile_monitoring_zip' => 'nullable|mimes:zip|max:10240',
            'file_dokumen_evaluasi' => 'nullable|mimes:pdf|max:20480',
            'shapefile_ekosistem_zip' => 'nullable|mimes:zip|max:10240',
            'file_sk_zonasi' => 'nullable|mimes:pdf|max:2048',
            'shapefile_zonasi_zip' => 'nullable|mimes:zip|max:10240',
>>>>>>> temp-fix
            'ada_akses_b02' => 'nullable|in:ya,tidak',
            'jenis_pengelolaan_b02' => 'required_if:ada_akses_b02,ya|nullable|in:akses_tradisional,kemitraan_konservasi',
            'jenis_akses_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'jenis_dimanfaatkan_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:2000',
            'nama_kelompok_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'masyarakat_hukum_adat_b02' => 'required_if:ada_akses_b02,ya|nullable|in:ya,tidak',
            'jumlah_laki_b02' => 'required_if:ada_akses_b02,ya|nullable|integer|min:0',
            'jumlah_perempuan_b02' => 'required_if:ada_akses_b02,ya|nullable|integer|min:0',
            'kabupaten_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'kecamatan_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'desa_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'nomor_surat_dirjen_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'nomor_pks_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
            'tanggal_mulai_ks_b02' => 'required_if:ada_akses_b02,ya|nullable|date',
            'tanggal_akhir_pks_b02' => 'required_if:ada_akses_b02,ya|nullable|date|after_or_equal:tanggal_mulai_ks_b02',
            'luas_area_b02' => 'required_if:ada_akses_b02,ya|nullable|numeric|min:0',
            'zona_blok_b02' => 'required_if:ada_akses_b02,ya|nullable|string|max:255',
<<<<<<< HEAD
        ], $this->fileUploadRules()), $this->fileUploadMessages());
=======
            'dokumen_kerjasama_b02' => 'nullable|mimes:pdf|max:2048',
            'shapefile_kerjasama_b02' => 'nullable|mimes:zip|max:10240',
        ]);
>>>>>>> temp-fix

        $query = DataKonservasi::query();

        if (Schema::hasColumn('data_konservasi', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $item = $query->findOrFail($id);

        $subBidang = SubBidang::findOrFail($request->sub_bidang_id);
        if ((int) $subBidang->bidang_id !== (int) $request->bidang_id) {
            return back()->withErrors(['sub_bidang_id' => 'Sub-bidang tidak sesuai dengan bidang yang dipilih.'])->withInput();
        }

        $fieldsBySubBidang = $this->fieldsBySubBidang();
        $fields = $fieldsBySubBidang[$subBidang->kode_sub] ?? [];
        $existingCode = $item->subBidang->kode_sub ?? '';
        $detailValues = $existingCode === $subBidang->kode_sub
            ? $this->parseDetailValues($item->keterangan, array_merge($fields, ['keterangan']))
            : [];
        $details = [];

        foreach ($request->only(array_merge($fields, ['keterangan'])) as $field => $value) {
            if (!is_scalar($value) || $value === '') {
                continue;
            }

            $details[] = $this->formFieldLabel($field) . ': ' . ($value === 'on' ? 'Ya' : $value);
        }

        if (!$request->exists('keterangan') && filled($detailValues['keterangan'] ?? null)) {
            $details[] = $this->formFieldLabel('keterangan') . ': ' . $detailValues['keterangan'];
        }
<<<<<<< HEAD
=======

        $fileInputs = [
            'sk_parsial_file' => 'dokumen_sk',
            'sk_provinsi_file' => 'dokumen_sk',
            'sk_penetapan_file' => 'dokumen_sk',
            'shapefile_zip' => 'shapefiles',
            'sk_rpjp_file' => 'dokumen_rpjp',
            'dokumen_batb' => 'dokumen_batb',
            'shapefile_monitoring_zip' => 'shapefiles',
            'file_dokumen_evaluasi' => 'dokumen_evaluasi',
            'shapefile_ekosistem_zip' => 'shapefiles',
            'file_sk_zonasi' => 'dokumen_zonasi',
            'shapefile_zonasi_zip' => 'shapefiles',
            'dokumen_kerjasama_b02' => 'dokumen_kerjasama',
            'shapefile_kerjasama_b02' => 'shapefiles',
        ];
>>>>>>> temp-fix

        foreach ($this->storeUploadedFiles($request, $subBidang->kode_sub, $detailValues) as $uploadedDetail) {
            $details[] = $uploadedDetail;
        }

        $yearFields = [
            'A.01' => 'tahun', 'A.02' => 'tahun_rpjp', 'A.03' => 'tahun_monitoring',
            'A.04' => 'tahun_evaluasi', 'A.05' => 'tahun_ekosistem', 'A.06' => 'tahun_zonasi',
            'B.01' => 'tahun_b01', 'B.02' => 'tahun_b02',
        ];
        $yearField = $yearFields[$subBidang->kode_sub] ?? null;
        $year = $yearField && $request->filled($yearField)
            ? $request->input($yearField)
            : ($request->filled('tahun') ? $request->input('tahun') : $item->tahun);
        $jumlah = match ($subBidang->kode_sub) {
            'A.03' => $request->filled('pal_baik') || $request->filled('pal_rusak') || $request->filled('pal_hilang')
                ? (int) $request->input('pal_baik', 0) + (int) $request->input('pal_rusak', 0) + (int) $request->input('pal_hilang', 0)
                : null,
            'B.01' => $request->filled('jumlah_laki') || $request->filled('jumlah_perempuan')
                ? (int) $request->input('jumlah_laki', 0) + (int) $request->input('jumlah_perempuan', 0)
                : null,
            'B.02' => $request->filled('jumlah_laki_b02') || $request->filled('jumlah_perempuan_b02')
                ? (int) $request->input('jumlah_laki_b02', 0) + (int) $request->input('jumlah_perempuan_b02', 0)
                : null,
            default => $request->input('jumlah'),
        };

        $item->update([
            'sub_bidang_id' => $subBidang->id,
            'tahun'         => $year,
            'bulan'         => $request->input('bulan', $item->bulan),
            'latitude'      => $request->input('latitude', $item->latitude),
            'longitude'     => $request->input('longitude', $item->longitude),
            'jumlah'        => $jumlah,
            'keterangan'    => count($details) ? implode(' | ', $details) : '-',
        ]);

        return redirect()->route('konservasi.index')->with('success', 'Data konservasi berhasil diperbarui!');
    }
}