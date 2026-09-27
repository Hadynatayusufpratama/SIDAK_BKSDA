<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\DataKonservasi;
use App\Models\SubBidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_hides_data_and_search_until_sub_bidang_is_selected(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)
            ->get(route('rekap.index'))
            ->assertOk()
            ->assertSee('Pilih Kategori Data Konservasi')
            ->assertDontSee('Riwayat Data Konservasi')
            ->assertDontSee('name="search"', false);
    }

    public function test_exports_only_the_selected_sub_bidang_and_include_all_rows(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $perencanaan = Bidang::create(['nama_bidang' => 'Perencanaan Konservasi']);
        $kawasan = Bidang::create(['nama_bidang' => 'Konservasi Kawasan']);
        $a01 = SubBidang::create([
            'bidang_id' => $perencanaan->id,
            'kode_sub' => 'A.01',
            'nama_sub_bidang' => 'Kawasan Konservasi',
        ]);
        $b01 = SubBidang::create([
            'bidang_id' => $kawasan->id,
            'kode_sub' => 'B.01',
            'nama_sub_bidang' => 'Kelompok Binaan',
        ]);

        foreach (range(1, 11) as $number) {
            DataKonservasi::create([
                'user_id' => $user->id,
                'sub_bidang_id' => $a01->id,
                'tahun' => 2026,
                'keterangan' => 'Kawasan Konservasi: A01 Record ' . $number,
            ]);
        }

        DataKonservasi::create([
            'user_id' => $user->id,
            'sub_bidang_id' => $b01->id,
            'tahun' => 2026,
            'keterangan' => 'Nama Kelompok: B01 Secret Record',
        ]);

        $this->actingAs($user)
            ->get(route('rekap.index', ['sub_bidang' => 'A.01']))
            ->assertOk()
            ->assertSee('SK Penunjukan Parsial')
            ->assertSee('name="search"', false)
            ->assertSee('A01 Record 1')
            ->assertDontSee('B01 Secret Record');

        $this->get(route('rekap.index', [
            'bidang' => 'perencanaan_konservasi',
            'sub_bidang' => 'A.01',
            'search' => 'A01 Record 11',
        ]))
            ->assertOk()
            ->assertSee('A01 Record 11')
            ->assertDontSee('A01 Record 10')
            ->assertDontSee('B01 Secret Record');

        $excelA01 = $this->actingAs($user)->get(route('konservasi.export.excel', [
            'bidang' => 'perencanaan_konservasi',
            'sub_bidang' => 'A.01',
        ]));

        $excelA01->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
            ->assertSee('Kawasan Konservasi')
            ->assertSee('A01 Record 11')
            ->assertDontSee('B01 Secret Record');

        $excelB01 = $this->get(route('konservasi.export.excel', [
            'bidang' => 'konservasi_kawasan',
            'sub_bidang' => 'B.01',
        ]));

        $excelB01->assertOk()
            ->assertSee('Nama Kelompok')
            ->assertSee('B01 Secret Record')
            ->assertDontSee('A01 Record 1');

        $pdf = $this->get(route('konservasi.export.pdf', [
            'bidang' => 'perencanaan_konservasi',
            'sub_bidang' => 'A.01',
        ]));

        $pdf->assertOk();
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
    }
}