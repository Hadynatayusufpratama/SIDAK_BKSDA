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

class B02ConservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_b02_input_is_saved_and_rendered_in_its_recap_columns(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Kawasan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'B.02',
            'nama_sub_bidang' => 'Pemberian Akses Pemanfaatan Tradisional dan Kemitraan Konservasi',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.create'))
            ->assertOk()
            ->assertSee('FORM DINAMIS SUB-BIDANG B.02', false)
            ->assertSee('name="jenis_akses_b02"', false)
            ->assertSee('name="nomor_pks_b02"', false)
            ->assertSee('name="shapefile_kerjasama_b02"', false);

        $this->post(route('konservasi.store'), [
            'sub_bidang_id' => $subBidang->id,
            'tahun_b02' => '2026',
            'kawasan_nama_b02' => 'CA Gunung Dako',
            'ada_akses_b02' => 'ya',
            'jenis_pengelolaan_b02' => 'akses_tradisional',
            'jenis_akses_b02' => 'pemungutan_hhbk',
            'jenis_dimanfaatkan_b02' => 'Rotan dan damar',
            'nama_kelompok_b02' => 'Kelompok Hutan Lestari',
            'masyarakat_hukum_adat_b02' => 'tidak',
            'jumlah_laki_b02' => '7',
            'jumlah_perempuan_b02' => '5',
            'kabupaten_b02' => 'Donggala',
            'kecamatan_b02' => 'Damsol',
            'desa_b02' => 'Sioyong',
            'nomor_surat_dirjen_b02' => 'S.123/KSDAE/2026',
            'nomor_pks_b02' => 'PKS-02/2026',
            'tanggal_mulai_ks_b02' => '2026-01-15',
            'tanggal_akhir_pks_b02' => '2028-01-14',
            'luas_area_b02' => '125.5',
            'zona_blok_b02' => 'Blok Pemanfaatan Tradisional',
            'dokumen_kerjasama_b02' => UploadedFile::fake()->create('perjanjian.pdf', 10, 'application/pdf'),
            'shapefile_kerjasama_b02' => UploadedFile::fake()->createWithContent('area.zip', "PK\x03\x04"),
        ])->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertSame(2026, $record->tahun);
        $this->assertSame(12, $record->jumlah);
        $this->assertStringContainsString('Jenis Akses: pemungutan_hhbk', $record->keterangan);
        $this->assertStringContainsString('Laki-laki: 7', $record->keterangan);
        Storage::disk('public')->assertExists('dokumen_kerjasama/' . basename(
            explode(': ', collect(explode(' | ', $record->keterangan))->first(fn ($detail) => str_starts_with($detail, 'Dokumen Kerjasama B02: ')), 2)[1]
        ));

        $this->get(route('konservasi.index', [
            'bidang' => 'konservasi_kawasan',
            'sub_bidang' => 'B.02',
        ]))
            ->assertOk()
            ->assertSee('Jenis Pengelolaan Bersama')
            ->assertSee('Jenis yang Dimanfaatkan')
            ->assertSee('Kelompok Hutan Lestari')
            ->assertSee('Pemungutan HHBK')
            ->assertSee('Rotan dan damar')
            ->assertSee('Donggala')
            ->assertSee('PKS-02/2026')
            ->assertSee('Blok Pemanfaatan Tradisional')
            ->assertSee('Lihat dokumen')
            ->assertSee('Lihat shapefile');

        $this->get(route('konservasi.export.excel', [
            'bidang' => 'konservasi_kawasan',
            'sub_bidang' => 'B.02',
        ]))
            ->assertOk()
            ->assertSee('Jenis Pengelolaan Bersama')
            ->assertSee('Rotan dan damar')
            ->assertSee('PKS-02/2026')
            ->assertDontSee('Aksi');

        $this->get(route('konservasi.edit', $record->id))
            ->assertOk()
            ->assertSee('Data Sub-Bidang B.02')
            ->assertSee('value="PKS-02/2026"', false)
            ->assertSee('value="akses_tradisional" checked', false)
            ->assertDontSee('Waktu & Koordinat Spasial')
            ->assertDontSee('Keterangan Tambahan')
            ->assertDontSee('name="latitude"', false)
            ->assertDontSee('name="longitude"', false)
            ->assertDontSee('name="bulan"', false);

        $this->put(route('konservasi.update', $record->id), [
            'bidang_id' => $bidang->id,
            'sub_bidang_id' => $subBidang->id,
            'tahun_b02' => '2026',
            'kawasan_nama_b02' => 'CA Gunung Dako',
            'ada_akses_b02' => 'ya',
            'jenis_pengelolaan_b02' => 'kemitraan_konservasi',
            'jenis_akses_b02' => 'pemungutan_hhbk',
            'jenis_dimanfaatkan_b02' => 'Rotan, damar, dan madu hutan',
            'nama_kelompok_b02' => 'Kelompok Hutan Lestari',
            'masyarakat_hukum_adat_b02' => 'tidak',
            'jumlah_laki_b02' => '8',
            'jumlah_perempuan_b02' => '6',
            'kabupaten_b02' => 'Donggala',
            'kecamatan_b02' => 'Damsol',
            'desa_b02' => 'Sioyong',
            'nomor_surat_dirjen_b02' => 'S.123/KSDAE/2026',
            'nomor_pks_b02' => 'PKS-02/2026',
            'tanggal_mulai_ks_b02' => '2026-01-15',
            'tanggal_akhir_pks_b02' => '2028-01-14',
            'luas_area_b02' => '125.75',
            'zona_blok_b02' => 'Blok Pemanfaatan Tradisional',
        ])->assertRedirect(route('konservasi.index'));

        $record->refresh();
        $this->assertSame(14, $record->jumlah);
        $this->assertStringContainsString('Jenis Pengelolaan Bersama Masyarakat: kemitraan_konservasi', $record->keterangan);
        $this->assertStringContainsString('Dokumen Kerjasama B02: dokumen_kerjasama/', $record->keterangan);
    }
}