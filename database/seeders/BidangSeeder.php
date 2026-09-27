<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Support\Facades\Schema;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key check (Aman untuk SQLite & MySQL)
        Schema::disableForeignKeyConstraints();

        Bidang::truncate();
        SubBidang::truncate();

        // Hidupkan kembali foreign key check
        Schema::enableForeignKeyConstraints();

        $data = [
            // Gambar 1: Perencanaan Konservasi (Kode A)
            [
                'bidang' => 'Perencanaan Konservasi',
                'subs' => [
                    ['A.01', 'Kawasan Konservasi'],
                    ['A.02', 'Perencanaan Pengelolaan Kawasan Konservasi'],
                    ['A.03', 'Monitoring Batas Kawasan Konservasi'],
                    ['A.04', 'Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi'],
                    ['A.05', 'Ekosistem Kawasan Konservasi'],
                    ['A.06', 'Penataan Kawasan Konservasi'],
                    ['A.07', 'Kerja Sama Penyelenggaraan KSA dan KPA'],
                ]
            ],
            // Gambar 2: Konservasi Kawasan (Kode B)
            [
                'bidang' => 'Konservasi Kawasan',
                'subs' => [
                    ['B.01', 'Kelompok Binaan UPT dalam rangka Pemberdayaan Masyarakat'],
                    ['B.02', 'Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi'],
                    ['B.03', 'Permasalahan Strategis Kawasan Konservasi'],
                    ['B.04', 'Gangguan Kawasan Konservasi'],
                    ['B.06', 'Penanganan Perkara Tindak Pidana'],
                    ['B.07', 'Tenaga Pengamanan Hutan Per Satuan Kerja'],
                    ['B.08', 'Tenaga Pengamanan Hutan Per Resor'],
                    ['B.09', 'Sarana Pengamanan Hutan'],
                    ['B.10', 'Kebakaran Hutan dan Lahan di Kawasan Konservasi'],
                    ['B.11', 'Tenaga Pengendalian Kebakaran Hutan'],
                    ['B.12', 'Peralatan Tangan Pengendalian Kebakaran Hutan'],
                    ['B.13', 'Peralatan Lainnya untuk Kebutuhan Pengendalian Kebakaran Hutan'],
                    ['B.14', 'Rekapitulasi Kader Bina Cinta Alam'],
                ]
            ],
            // Gambar 3: Konservasi Spesies dan Genetik (Kode C)
            [
                'bidang' => 'Konservasi Spesies dan Genetik',
                    SubBidang::query()->delete();
                    Bidang::query()->delete();
                    ['C.03', 'Koleksi TSL di Lembaga Konservasi'],
                    ['C.04', 'Penangkaran Tumbuhan dan Satwa Liar'],
                    ['C.05', 'Jenis TSL yang ditangkarkan di Penangkaran'],
                    ['C.06', 'Pengedar Tumbuhan dan Satwa Liar (Dalam dan Luar Negeri)'],
                    ['C.07', 'Kuota Pemanfaatan Tumbuhan dan Satwa Liar'],
                    ['C.08', 'Realisasi Penangkapan/ Pengambilan Tumbuhan dan Satwa Liar'],
                    ['C.09', 'Realisasi Ekspor Tumbuhan dan Satwa Liar Hasil Penangkaran'],
                    ['C.10', 'Realisasi Ekspor Tumbuhan dan Satwa Liar Pengambilan dari Alam'],
                    ['C.11', 'Rekapitulasi Sitaan/ Penyerahan/ Penyelamatkan Satwa'],
                    ['C.12', 'PNBP dari kegiatan Pemanfaatan Tumbuhan dan Satwa Liar'],
                    ['C.14', 'Interaksi Negatif Satwa Liar dan Manusia'],
                    ['C.15', 'Rekapitulasi Pelepasliaran Kembali Satwa'],
                    ['C.16', 'Rekapitulasi Kelahiran Satwa'],
                    ['C.17', 'Rekapitulasi Kematian Satwa Liar'],
                ]
            ],
            // Gambar 4: Pemanfaatan Jasa Lingkungan (Kode D)
            [
                'bidang' => 'Pemanfaatan Jasa Lingkungan',
                'subs' => [
                    ['D.01', 'Pengunjung Kawasan Konservasi'],
                    ['D.02', 'PNBP Wisata Alam di Kawasan Konservasi'],
                    ['D.03', 'Desain Tapak Pemanfaatan Jasa Lingkungan Wisata Alam'],
                    ['D.04', 'Potensi Wisata Alam di Kawasan Konservasi'],
                    ['D.05', 'Pemanfaatan Jasa Lingkungan Penyediaan Jasa Wisata Alam (PBPJWA)'],
                    ['D.06', 'Pengusahaan Sarana Jasa Lingkungan Wisata Alam (PBPSWA)'],
                    ['D.07', 'Sarana dan Prasarana Wisata Alam di Kawasan Konservasi'],
                    ['D.08', 'Dampak Aktivitas Wisata Alam'],
                    ['D.09', 'Potensi Pemanfaatan Air dan Energi Air di Kawasan Konservasi'],
                    ['D.10', 'Areal Pemanfaatan Air dan Energi Air di Kawasan Konservasi'],
                    ['D.11', 'Pemanfaatan Air dan Energi Air di Kawasan Konservasi'],
                    ['D.12', 'Potensi Pemanfaatan Karbon di Kawasan Konservasi'],
                    ['D.13', 'Potensi Pemanfaatan Energi Panas Bumi di Kawasan Konservasi'],
                    ['D.14', 'Pemanfaatan Jasa Lingkungan Panas Bumi di Kawasan Konservasi'],
                    ['D.15', 'Kejadian Kecelakaan di dalam Kawasan Konservasi'],
                    ['D.16', 'Promosi dan Publikasi Jasa Lingkungan Kawasan Konservasi'],
                ]
            ],
            // Gambar 5: Pemulihan Ekosistem dan Bina Area Preservasi (Kode E)
            [
                'bidang' => 'Pemulihan Ekosistem dan Bina Area Preservasi',
                'subs' => [
                    ['E.01', 'Perencanaan Pemulihan Ekosistem'],
                    ['E.02', 'Realisasi Pemulihan Ekosistem'],
                    ['E.03', 'Hasil Inventarisasi Area dengan Potensi Kehati Tinggi di Luar Kawasan Konservasi'],
                    ['E.04', 'Kawasan Ekosistem Esensial'],
                    ['E.05', 'Perencanaan Kawasan Ekosistem Esensial'],
                    ['E.06', 'Penilaian Efektivitas Pengelolaan KEE'],
                    ['E.07', 'Situs Ramsar'],
                ]
            ],
            // Gambar 6: Kesekretariatan (Kode F)
            [
                'bidang' => 'Kesekretariatan',
                'subs' => [
                    ['F.01', 'Sebaran PNS/CPNS Menurut Jabatan dan Jenis Kelamin'],
                    ['F.02', 'Sebaran PNS/CPNS Menurut Tingkat Pendidikan dan Jenis Kelamin'],
                    ['F.03', 'Sebaran PNS/CPNS Menurut Golongan dan Jenis Kelamin'],
                    ['F.04', 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi dan Jenis Kelamin'],
                    ['F.05', 'Sebaran Pejabat Fungsional Tertentu Menurut Fungsi, Tingkat Pendidikan dan Jenis Kelamin'],
                    ['F.06', 'Sebaran ASN P3K menurut Tingkat Pendidikan dan Jenis Kelamin'],
                    ['F.07', 'Kerja Sama Teknik Bidang KSDAE'],
                    ['F.08', 'Perijinan Masuk Kawasan Konservasi'],
                ]
            ]
        ];

        foreach ($data as $item) {
            $bidang = Bidang::create(['nama_bidang' => $item['bidang']]);
            foreach ($item['subs'] as $sub) {
                SubBidang::create([
                    'bidang_id' => $bidang->id,
                    'kode_sub' => $sub[0],
                    'nama_sub_bidang' => $sub[1],
                ]);
            }
        }
    }
}