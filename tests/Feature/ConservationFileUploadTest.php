<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\DataKonservasi;
use App\Models\SubBidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Tests\TestCase;

class ConservationFileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_create_form_file_input_has_upload_rules_and_storage_config(): void
    {
        $view = file_get_contents(resource_path('views/konservasi/create.blade.php'));
        preg_match_all('/<input\b(?=[^>]*\btype="file")[^>]*>/i', $view, $tags);
        $inputNames = [];

        foreach ($tags[0] as $tag) {
            if (preg_match('/\bname="([^"]+)"/', $tag, $nameMatch)) {
                $inputNames[] = str_replace('[]', '', $nameMatch[1]);
            }
        }

        $method = (new \ReflectionClass(\App\Http\Controllers\KonservasiController::class))
            ->getMethod('fileUploadConfig');
        $config = $method->invoke(app(\App\Http\Controllers\KonservasiController::class));

        $this->assertNotEmpty($inputNames);
        $this->assertSame([], array_values(array_diff(array_unique($inputNames), array_keys($config))));
        $this->assertSame([], array_filter($config, fn ($upload) => $upload['max'] !== 10240));
    }

    public function test_c04_permit_document_upload_is_stored(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.04',
            'nama_sub_bidang' => 'Penangkaran Tumbuhan dan Satwa Liar',
        ]);

        $this->actingAs($user)
            ->post(route('konservasi.store'), [
                'sub_bidang_id' => $subBidang->id,
                'tahun_c04' => '2026',
                'dokumen_perizinan_c04' => UploadedFile::fake()->createWithContent(
                    'izin-penangkaran.pdf',
                    "%PDF-1.4\nDokumen izin uji\n%%EOF"
                ),
            ])
            ->assertRedirect(route('konservasi.index'));

        $record = DataKonservasi::firstOrFail();
        $this->assertStringContainsString('Dokumen Perizinan C04: dokumen_perizinan/', $record->keterangan);
        $storedPath = substr($record->keterangan, strpos($record->keterangan, 'Dokumen Perizinan C04: ') + strlen('Dokumen Perizinan C04: '));
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_c04_oversized_document_gets_a_clear_file_size_message(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.04',
            'nama_sub_bidang' => 'Penangkaran Tumbuhan dan Satwa Liar',
        ]);

        $this->actingAs($user)
            ->from(route('konservasi.create'))
            ->post(route('konservasi.store'), [
                'sub_bidang_id' => $subBidang->id,
                'dokumen_perizinan_c04' => UploadedFile::fake()->create('izin-besar.pdf', 11000, 'application/pdf'),
            ])
            ->assertRedirect(route('konservasi.create'));

        $this->get(route('konservasi.create'))
            ->assertOk()
            ->assertSee('Ukuran Dokumen perizinan C.04 maksimal 10 MB per file.');

        $this->assertDatabaseCount('data_konservasi', 0);
    }

    public function test_c04_document_at_ten_mb_limit_is_accepted(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.04',
            'nama_sub_bidang' => 'Penangkaran Tumbuhan dan Satwa Liar',
        ]);

        $this->actingAs($user)
            ->post(route('konservasi.store'), [
                'sub_bidang_id' => $subBidang->id,
                'tahun_c04' => '2026',
                'dokumen_perizinan_c04' => UploadedFile::fake()->create('izin-10mb.pdf', 10240, 'application/pdf'),
            ])
            ->assertRedirect(route('konservasi.index'));

        $this->assertDatabaseCount('data_konservasi', 1);
    }

    public function test_php_upload_failure_shows_actionable_message_for_the_selected_file(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Spesies dan Genetik']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'C.04',
            'nama_sub_bidang' => 'Penangkaran Tumbuhan dan Satwa Liar',
        ]);

        $this->actingAs($user)
            ->from(route('konservasi.create'))
            ->post(route('konservasi.store'), [
                'sub_bidang_id' => $subBidang->id,
                'dokumen_perizinan_c04' => new SymfonyUploadedFile(
                    __FILE__,
                    'izin-gagal.pdf',
                    'application/pdf',
                    UPLOAD_ERR_CANT_WRITE,
                    true
                ),
            ])
            ->assertRedirect(route('konservasi.create'));

        $this->get(route('konservasi.create'))
            ->assertOk()
            ->assertSee('Dokumen perizinan C.04 gagal diterima server.')
            ->assertSee('periksa batas upload PHP atau folder temporary server.');
    }

    public function test_d07_rejects_more_than_three_uploaded_photos_with_a_clear_message(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $bidang = Bidang::create(['nama_bidang' => 'Konservasi Kawasan']);
        $subBidang = SubBidang::create([
            'bidang_id' => $bidang->id,
            'kode_sub' => 'D.07',
            'nama_sub_bidang' => 'Sarana dan Prasarana Wisata Alam di Kawasan Konservasi',
        ]);
        $photos = array_map(
            fn ($number) => UploadedFile::fake()->create("foto-{$number}.jpg", 10, 'image/jpeg'),
            range(1, 4)
        );

        $this->actingAs($user)
            ->from(route('konservasi.create'))
            ->post(route('konservasi.store'), [
                'sub_bidang_id' => $subBidang->id,
                'foto_sarana_prasarana_d07' => $photos,
            ])
            ->assertRedirect(route('konservasi.create'));

        $this->get(route('konservasi.create'))
            ->assertOk()
            ->assertSee('Foto sarana prasarana D.07 maksimal 3 file.');

        $this->assertDatabaseCount('data_konservasi', 0);
    }
}