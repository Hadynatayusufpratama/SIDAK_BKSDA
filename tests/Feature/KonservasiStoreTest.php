<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\DataKonservasi;
use App\Models\SubBidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KonservasiStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_b01_form_values_are_saved_for_the_rekapitulasi(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Kawasan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'B.01',
            'nama_sub_bidang' => 'Kelompok Binaan',
        ]);

        $response = $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun' => '2026',
            'tahun_rpjp' => '2026',
            'tahun_b01' => '2025',
            'periode_semester' => 'Semester II',
            'kawasan_nama_b01' => 'SM Bakiriang',
            'ada_kegiatan_b01' => 'ya',
            'nama_kelompok' => 'Kelompok Hutan Lestari',
            'jumlah_laki' => '7',
            'jumlah_perempuan' => '5',
            'provinsi' => 'Sulawesi Tengah',
            'kabupaten' => 'Banggai',
            'kecamatan' => 'Luwuk',
            'desa' => 'Bubung',
            'jenis_hhbk' => 'Rotan',
            'jenis_bantuan' => 'Peralatan',
            'jumlah_bantuan' => '1500000',
            'sumber_dana' => 'APBN KSDAE',
            'keterangan' => 'Pendampingan tahap kedua',
        ]);

        $response->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertSame(2025, $record->tahun);
        $this->assertSame(12, $record->jumlah);
        $this->assertSame(1, substr_count($record->keterangan, 'Tahun:'));
        $this->assertStringNotContainsString('Tahun: 2026', $record->keterangan);
        $this->assertStringContainsString('Periode Semester: Semester II', $record->keterangan);
        $this->assertStringContainsString('Nama Kelompok: Kelompok Hutan Lestari', $record->keterangan);
        $this->assertStringContainsString('Kabupaten: Banggai', $record->keterangan);
        $this->assertStringContainsString('Pemungutan HHBK: Rotan', $record->keterangan);
        $this->assertStringContainsString('Keterangan: Pendampingan tahap kedua', $record->keterangan);

        $this->actingAs($user)
            ->get(route('konservasi.index'))
            ->assertOk()
            ->assertSee('Tahun: 2025')
            ->assertSee('Nama Kelompok: Kelompok Hutan Lestari')
            ->assertSee('Pemungutan HHBK: Rotan');
    }

    public function test_a01_rekap_shows_columns_matching_the_kawasan_konservasi_form(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.01',
            'nama_sub_bidang' => 'Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun' => '2025',
            'kawasan_nama' => 'SM Bakiriang',
            'ada_perubahan' => 'ya',
            'sk_parsial_nomor' => '123/2025',
            'sk_parsial_tanggal' => '2025-06-15',
            'sk_parsial_luas' => '15.25',
            'sk_provinsi_tersedia' => 'ya',
            'sk_provinsi_nomor' => '456/2025',
            'sk_provinsi_tanggal' => '2025-07-10',
            'sk_provinsi_luas' => '120.50',
            'sk_penetapan_tersedia' => 'tidak',
            'keterangan' => 'Pembaruan batas kawasan',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.01',
            ]))
            ->assertOk()
            ->assertSee('SK Penunjukan Parsial')
            ->assertSee('SK Penunjukan Provinsi')
            ->assertSee('SK Penetapan')
            ->assertSee('Batas Geografis Kawasan')
            ->assertSee('SM Bakiriang')
            ->assertSee('123/2025')
            ->assertSee('15.25 Ha')
            ->assertSee('Tersedia')
            ->assertSee('Tidak tersedia')
            ->assertSee('Pembaruan batas kawasan');
    }

    public function test_a02_rekap_shows_rpjp_fields_from_the_form(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.02',
            'nama_sub_bidang' => 'Perencanaan Pengelolaan Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_rpjp' => '2025',
            'kawasan_nama_rpjp' => 'SM Bakiriang',
            'ketersediaan_rpjp' => 'ya',
            'sk_rpjp_nomor' => 'SK-RPJP-2025/01',
            'sk_rpjp_tanggal_pengesahan' => '2025-04-12',
            'sk_rpjp_periode_berakhir' => '2045-12-31',
            'keterangan' => 'Dokumen telah diperbarui',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.02',
            ]))
            ->assertOk()
            ->assertSee('SK Penetapan Dokumen RPJP')
            ->assertSee('Nomor Surat Keputusan')
            ->assertSee('Tanggal Pengesahan')
            ->assertSee('Periode Berakhir RPJ Panjang')
            ->assertSee('SM Bakiriang')
            ->assertSee('Tersedia')
            ->assertSee('SK-RPJP-2025/01')
            ->assertSee('2025-04-12')
            ->assertSee('2045-12-31')
            ->assertSee('Dokumen telah diperbarui');
    }

    public function test_a03_rekap_shows_monitoring_boundary_fields_from_the_form(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.03',
            'nama_sub_bidang' => 'Monitoring Batas Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_monitoring' => '2026',
            'kawasan_nama_monitoring' => 'CA Gunung Tinombala',
            'ada_kegiatan_monitoring' => 'ya',
            'jenis_kegiatan' => 'Rekonstruksi',
            'nomor_batb' => 'BATB-03/2026',
            'tanggal_batb' => '2026-09-27',
            'pal_baik' => '8',
            'pal_rusak' => '2',
            'pal_hilang' => '1',
            'pal_total' => '11',
            'panjang_pal_km' => '23.75',
            'keterangan' => 'Pemantauan selesai',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.03',
            ]))
            ->assertOk()
            ->assertSee('Berita Acara Tata Batas (BATB)')
            ->assertSee('Jenis Kegiatan')
            ->assertSee('Pal Baik')
            ->assertSee('Lokasi Geografis Hasil Kegiatan')
            ->assertSee('CA Gunung Tinombala')
            ->assertSee('Rekonstruksi')
            ->assertSee('BATB-03/2026')
            ->assertSee('23.75 Km')
            ->assertSee('Pemantauan selesai');
    }

    public function test_a04_rekap_shows_function_evaluation_fields_from_the_form(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.04',
            'nama_sub_bidang' => 'Hasil Evaluasi Kesesuaian Fungsi Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_evaluasi' => '2026',
            'kawasan_nama_evaluasi' => 'TWA Wera',
            'ketersediaan_evaluasi' => 'ya',
            'tanggal_pelaksanaan_evaluasi' => '2026-08-14',
            'rekomendasi_evaluasi' => 'Pertahankan fungsi kawasan',
            'tindak_lanjut_evaluasi' => 'Laksanakan pemulihan habitat',
            'keterangan' => 'Evaluasi tahap pertama',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.04',
            ]))
            ->assertOk()
            ->assertSee('Tanggal Pelaksanaan')
            ->assertSee('Rekomendasi')
            ->assertSee('Tindak Lanjut')
            ->assertSee('File Dokumen')
            ->assertSee('TWA Wera')
            ->assertSee('Tersedia')
            ->assertSee('2026-08-14')
            ->assertSee('Pertahankan fungsi kawasan')
            ->assertSee('Laksanakan pemulihan habitat')
            ->assertSee('Evaluasi tahap pertama');
    }

    public function test_a05_rekap_shows_ecosystem_data_and_uploaded_shapefile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.05',
            'nama_sub_bidang' => 'Ekosistem Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_ekosistem' => '2026',
            'kawasan_nama_ekosistem' => 'TWA Wera',
            'ketersediaan_ekosistem' => 'ya',
            'shapefile_ekosistem_zip' => UploadedFile::fake()->createWithContent('ekosistem.zip', "PK\x03\x04"),
            'keterangan' => 'Pemetaan ekosistem terbaru',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.05',
            ]))
            ->assertOk()
            ->assertSee('Shapefile Area (ZIP)')
            ->assertSee('Ketersediaan Data')
            ->assertSee('TWA Wera')
            ->assertSee('Tersedia')
            ->assertSee('Lihat shapefile')
            ->assertSee('Pemetaan ekosistem terbaru')
            ->assertSee('Ketersediaan Ekosistem: ya')
            ->assertSee('x-text="detail.label"', false)
            ->assertSee('x-text="detail.value"', false)
            ->assertSee('Belum ada rincian untuk data ini.');
    }

    public function test_a06_rekap_shows_zoning_fields_and_uploaded_files(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'A.06',
            'nama_sub_bidang' => 'Penataan Kawasan Konservasi',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_zonasi' => '2026',
            'kawasan_nama_zonasi' => 'TWA Wera',
            'ketersediaan_zonasi' => 'sudah',
            'nomor_sk_zonasi' => 'SK-ZONASI-06/2026',
            'tanggal_sk_zonasi' => '2026-05-18',
            'file_sk_zonasi' => UploadedFile::fake()->create('zonasi.pdf', 1, 'application/pdf'),
            'shapefile_zonasi_zip' => UploadedFile::fake()->createWithContent('zonasi.zip', "PK\x03\x04"),
            'keterangan' => 'Zonasi telah ditetapkan',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.index', [
                'bidang' => 'perencanaan_konservasi',
                'sub_bidang' => 'A.06',
            ]))
            ->assertOk()
            ->assertSee('Ketersediaan Data Zonasi/Blok')
            ->assertSee('Shapefile Area (ZIP)')
            ->assertSee('TWA Wera')
            ->assertSee('Sudah')
            ->assertSee('SK-ZONASI-06/2026')
            ->assertSee('2026-05-18')
            ->assertSee('Lihat file SK')
            ->assertSee('Lihat shapefile')
            ->assertSee('Zonasi telah ditetapkan');
    }

    public function test_edit_prefills_and_updates_the_selected_sub_bidang_fields(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Kawasan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'B.01',
            'nama_sub_bidang' => 'Kelompok Binaan',
        ]);
        $record = DataKonservasi::create([
            'user_id' => $user->id,
            'sub_bidang_id' => $subBidang->id,
            'tahun' => 2025,
            'bulan' => 3,
            'latitude' => '-0.89',
            'longitude' => '119.87',
            'keterangan' => 'Tahun: 2025 | Periode Semester: Semester I | Kawasan Konservasi: SM Bakiriang | Nama Kelompok: Kelompok Lama | Jumlah Laki-laki: 5 | Jumlah Perempuan: 4 | Keterangan: Catatan lama',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.edit', $record->id))
            ->assertOk()
            ->assertSee('Data Sub-Bidang B.01')
            ->assertSee('value="Kelompok Lama"', false)
            ->assertSee('value="Semester I"', false)
            ->assertSee('value="Catatan lama"', false);

        $this->actingAs($user)
            ->put(route('konservasi.update', $record->id), [
                'bidang_id' => $bidang->id,
                'sub_bidang_id' => $subBidang->id,
                'tahun_b01' => '2026',
                'periode_semester' => 'Semester II',
                'kawasan_nama_b01' => 'SM Bakiriang',
                'nama_kelompok' => 'Kelompok Baru',
                'jumlah_laki' => '6',
                'jumlah_perempuan' => '4',
                'keterangan' => 'Catatan baru',
                'bulan' => '4',
            ])
            ->assertRedirect(route('konservasi.index'));

        $record->refresh();
        $this->assertSame(2026, $record->tahun);
        $this->assertSame(10, $record->jumlah);
        $this->assertStringContainsString('Nama Kelompok: Kelompok Baru', $record->keterangan);
        $this->assertStringContainsString('Periode Semester: Semester II', $record->keterangan);
        $this->assertStringContainsString('Keterangan: Catatan baru', $record->keterangan);
    }

    public function test_c15_release_fields_are_saved_with_period_count_and_coordinates(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.15',
            'nama_sub_bidang' => 'Rekapitulasi Pelepasliaran Kembali Satwa',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_c15' => '2025',
            'bulan_c15' => '9',
            'satuan_kerja_c15' => 'Balai KSDA Sulawesi Tengah',
            'ada_pelepasan_satwa_c15' => 'ya',
            'spesies_c15' => 'Maleo',
            'jumlah_individu_c15' => '2',
            'asal_usul_c15' => 'Hasil Penyelamatan Satwa',
            'longitude_c15' => '119.87123',
            'latitude_c15' => '-0.897123',
            'keterangan_c15' => 'Pelepasliaran di habitat alami',
            'keterangan' => 'Catatan umum duplikat',
        ])->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertSame(2025, $record->tahun);
        $this->assertSame(9, $record->bulan);
        $this->assertSame(2, $record->jumlah);
        $this->assertSame('-0.897123', $record->latitude);
        $this->assertSame('119.87123', $record->longitude);
        $this->assertStringContainsString('Spesies yang Dilepasliarkan: Maleo', $record->keterangan);
        $this->assertStringContainsString('Asal-usul: Hasil Penyelamatan Satwa', $record->keterangan);
        $this->assertStringContainsString('Keterangan: Pelepasliaran di habitat alami', $record->keterangan);
        $this->assertSame(1, substr_count($record->keterangan, 'Keterangan:'));
        $this->assertStringNotContainsString('Catatan umum duplikat', $record->keterangan);
    }

    public function test_c16_birth_fields_are_saved_with_period_count_and_coordinates(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.16',
            'nama_sub_bidang' => 'Rekapitulasi Kelahiran Satwa',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_c16' => '2025',
            'bulan_c16' => '9',
            'satuan_kerja_c16' => 'Balai KSDA Sulawesi Tengah',
            'ada_kelahiran_satwa_c16' => 'ya',
            'spesies_c16' => 'Maleo',
            'tanggal_kelahiran_c16' => '2025-09-12',
            'usulan_nama_satwa_c16' => 'Bintang',
            'jantan_c16' => '1',
            'betina_c16' => '2',
            'belum_diketahui_c16' => '0',
            'longitude_c16' => '119.87123',
            'latitude_c16' => '-0.897123',
            'keterangan_c16' => 'Kelahiran di habitat alami',
            'keterangan' => 'Catatan umum duplikat',
        ])->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertSame(2025, $record->tahun);
        $this->assertSame(9, $record->bulan);
        $this->assertSame(3, $record->jumlah);
        $this->assertSame('-0.897123', $record->latitude);
        $this->assertSame('119.87123', $record->longitude);
        $this->assertStringContainsString('Spesies Satwa: Maleo', $record->keterangan);
        $this->assertStringContainsString('Tanggal Kelahiran: 2025-09-12', $record->keterangan);
        $this->assertStringContainsString('Usulan Nama Satwa: Bintang', $record->keterangan);
        $this->assertStringContainsString('Keterangan: Kelahiran di habitat alami', $record->keterangan);
        $this->assertSame(1, substr_count($record->keterangan, 'Keterangan:'));
        $this->assertStringNotContainsString('Catatan umum duplikat', $record->keterangan);
    }

    public function test_c17_death_fields_are_saved_with_period_count_and_coordinates(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.17',
            'nama_sub_bidang' => 'Rekapitulasi Kematian Satwa Liar',
        ]);

        $this->actingAs($user)->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_c17' => '2025',
            'bulan_c17' => '9',
            'satuan_kerja_c17' => 'Balai KSDA Sulawesi Tengah',
            'ada_kematian_satwa_c17' => 'ya',
            'spesies_c17' => 'Maleo',
            'tanggal_kematian_c17' => '2025-09-12',
            'perkiraan_usia_c17' => 'Dewasa',
            'penyebab_kematian_c17' => 'Sakit/Penyakit',
            'jantan_c17' => '1',
            'betina_c17' => '2',
            'belum_diketahui_c17' => '0',
            'upaya_penanganan_c17' => 'Pemeriksaan dan penanganan satwa',
            'longitude_c17' => '119.87123',
            'latitude_c17' => '-0.897123',
            'keterangan_c17' => 'Kematian tercatat di habitat alami',
            'keterangan' => 'Catatan umum duplikat',
        ])->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertSame(2025, $record->tahun);
        $this->assertSame(9, $record->bulan);
        $this->assertSame(3, $record->jumlah);
        $this->assertSame('-0.897123', $record->latitude);
        $this->assertSame('119.87123', $record->longitude);
        $this->assertStringContainsString('Spesies Satwa: Maleo', $record->keterangan);
        $this->assertStringContainsString('Tanggal Kematian: 2025-09-12', $record->keterangan);
        $this->assertStringContainsString('Perkiraan Usia: Dewasa', $record->keterangan);
        $this->assertStringContainsString('Penyebab Kematian: Sakit/Penyakit', $record->keterangan);
        $this->assertStringContainsString('Keterangan: Kematian tercatat di habitat alami', $record->keterangan);
        $this->assertSame(1, substr_count($record->keterangan, 'Keterangan:'));
        $this->assertStringNotContainsString('Catatan umum duplikat', $record->keterangan);
    }

    public function test_file_uploads_are_stored_for_pdf_zip_and_image_fields(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $createSubBidang = fn (string $kode) => SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => $kode,
            'nama_sub_bidang' => $kode,
        ]);

        $this->actingAs($user);

        $odtwa = $createSubBidang('D.04');
        $this->post(route('konservasi.store'), [
            'sub_bidang_id' => $odtwa->id,
            'foto_odtwa' => UploadedFile::fake()->image('odtwa.jpg'),
        ])->assertRedirect(route('konservasi.index'));
        $this->assertCount(1, Storage::disk('public')->allFiles('foto_odtwa'));

        $izin = $createSubBidang('C.02');
        $this->post(route('konservasi.store'), [
            'sub_bidang_id' => $izin->id,
            'dokumen_perizinan_c02' => UploadedFile::fake()->create('izin.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('konservasi.index'));
        $this->assertCount(1, Storage::disk('public')->allFiles('dokumen_perizinan'));

        $shapefile = $createSubBidang('D.10');
        $this->post(route('konservasi.store'), [
            'sub_bidang_id' => $shapefile->id,
            'shapefile_d10' => UploadedFile::fake()->create('area.zip', 100, 'application/zip'),
        ])->assertRedirect(route('konservasi.index'));
        $this->assertCount(1, Storage::disk('public')->allFiles('shapefiles'));

        $sarana = $createSubBidang('D.07');
        $this->post(route('konservasi.store'), [
            'sub_bidang_id' => $sarana->id,
            'foto_sarana_prasarana_d07' => [
                UploadedFile::fake()->image('sarana-1.jpg'),
                UploadedFile::fake()->image('sarana-2.jpg'),
            ],
        ])->assertRedirect(route('konservasi.index'));
        $this->assertCount(2, Storage::disk('public')->allFiles('foto_sarana_prasarana'));
    }

}