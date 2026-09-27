<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\DataKonservasi;
use App\Models\SubBidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_all_six_fields_and_separates_volume_by_sub_bidang(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $fields = [
            ['Perencanaan Konservasi', 'A.01', 'Kawasan Konservasi'],
            ['Konservasi Kawasan', 'B.01', 'Kelompok Binaan'],
            ['Konservasi Spesies dan Genetik', 'C.01', 'Perjumpaan Spesies'],
            ['Pemanfaatan Jasa Lingkungan', 'D.01', 'Pengunjung Kawasan'],
            ['Pemulihan Ekosistem dan Bina Area Preservasi', 'E.01', 'Pemulihan Ekosistem'],
            ['Kesekretariatan', 'F.01', 'Sebaran PNS'],
        ];

        $subBidangs = [];
        foreach ($fields as [$namaBidang, $kodeSub, $namaSub]) {
            $bidang = Bidang::create(['nama_bidang' => $namaBidang]);
            $subBidang = SubBidang::create([
                'bidang_id' => $bidang->id,
                'kode_sub' => $kodeSub,
                'nama_sub_bidang' => $namaSub,
            ]);
            $subBidangs[$kodeSub] = $subBidang;
        }
        $subBidangs['A.03'] = SubBidang::create([
            'bidang_id' => $subBidangs['A.01']->bidang_id,
            'kode_sub' => 'A.03',
            'nama_sub_bidang' => 'Monitoring Batas Kawasan Konservasi',
        ]);

        DataKonservasi::create([
            'user_id' => $user->id,
            'sub_bidang_id' => $subBidangs['A.01']->id,
            'tahun' => 2026,
            'keterangan' => 'Kawasan: SM Bakiriang',
        ]);
        DataKonservasi::create([
            'user_id' => $user->id,
            'sub_bidang_id' => $subBidangs['A.03']->id,
            'tahun' => 2026,
            'jumlah' => 12,
            'keterangan' => 'Pal Total: 12',
        ]);
        DataKonservasi::create([
            'user_id' => $user->id,
            'sub_bidang_id' => $subBidangs['B.01']->id,
            'tahun' => 2026,
            'jumlah' => 5,
            'keterangan' => 'Nama Kelompok: Kelompok Lestari',
        ]);

        $this->actingAs($user)
            ->get(route('konservasi.dashboard'))
            ->assertOk()
            ->assertSee('Jumlah Entri per Enam Bidang')
            ->assertSee('Volume Input per Sub-Bidang')
            ->assertSee('Perencanaan Konservasi')
            ->assertSee('Konservasi Kawasan')
            ->assertSee('Konservasi Spesies dan Genetik')
            ->assertSee('Pemanfaatan Jasa Lingkungan')
            ->assertSee('Pemulihan Ekosistem dan Bina Area Preservasi')
            ->assertSee('Kesekretariatan')
            ->assertSee('2 subbidang memiliki nilai volume')
            ->assertSee('A.03 · Monitoring Batas Kawasan Konservasi')
            ->assertSee('B.01 · Kelompok Binaan')
            ->assertSee('12 pal')
            ->assertSee('5 orang');
    }
}