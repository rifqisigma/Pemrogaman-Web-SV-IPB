<?php

namespace Tests\Feature;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\User;
use App\Models\WageLog;
use Database\Seeders\ProvinsiSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NationalWageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed provinsis and users in the test database
        $this->seed(ProvinsiSeeder::class);
        $this->seed(UserSeeder::class);

        // Ensure at least a couple UMP rows exist for tests
        $p1 = Provinsi::where('kode_provinsi', '31')->first(); // DKI Jakarta
        $p2 = Provinsi::where('kode_provinsi', '32')->first(); // Jawa Barat

        if ($p1) {
            Ump::updateOrCreate(['provinsi_id' => $p1->id, 'tahun' => 2026], ['nominal' => 5396761]);
            Ump::updateOrCreate(['provinsi_id' => $p1->id, 'tahun' => 2025], ['nominal' => 5067381]);
        }
        if ($p2) {
            Ump::updateOrCreate(['provinsi_id' => $p2->id, 'tahun' => 2026], ['nominal' => 2191238]);
            Ump::updateOrCreate(['provinsi_id' => $p2->id, 'tahun' => 2025], ['nominal' => 2057495]);
        }
    }

    public function test_guest_can_access_home_with_map_and_dashboard()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('National', false);
        $response->assertSee('Wage', false);
        $response->assertSee('Peta Sebaran Upah', false);
    }

    public function test_guest_can_view_scientific_report_page()
    {
        $response = $this->get('/scientific-report');
        $response->assertStatus(200);
        $response->assertSee('Generator Sitasi Ilmiah', false);
        $response->assertSee('APA 7th Edition', false);
        $response->assertSee('Masuk Akun untuk Mengunduh PDF', false);
    }

    public function test_guest_cannot_download_scientific_report_pdf()
    {
        $response = $this->get('/scientific-report/download');
        $response->assertRedirect('/login');
    }

    public function test_guest_can_view_datasheet_page()
    {
        $response = $this->get('/datasheet');
        $response->assertStatus(200);
        $response->assertSee('Datasheet Upah', false);
        $response->assertSee('Pratinjau Data', false);
    }

    public function test_guest_cannot_download_datasheet_export()
    {
        $response = $this->get('/datasheet/export?format=xlsx');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_user_profile()
    {
        $response = $this->get('/user');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_panel()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_with_username_admin_and_pw_admin123()
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();

        $adminResponse = $this->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Dashboard Kontrol', false);
        $adminResponse->assertSee('Riwayat Pengguna & Audit Log', false);
    }

    public function test_regular_user_can_login_and_access_user_profile()
    {
        $response = $this->post('/login', [
            'login' => 'budi_santoso',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/user');
        $this->assertAuthenticated();

        $userResponse = $this->get('/user');
        $userResponse->assertStatus(200);
        $userResponse->assertSee('Budi Santoso', false);
        $userResponse->assertSee('Dashboard Upah Regional', false);
    }

    public function test_regular_user_cannot_access_admin_panel()
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertRedirect('/user');
    }

    public function test_authenticated_user_can_update_wage_and_creates_log()
    {
        $user = User::where('username', 'budi_santoso')->first();
        $this->actingAs($user);

        $provinsi = Provinsi::first();
        $initialLogCount = WageLog::where('user_id', $user->id)->count();

        $response = $this->post('/user/update-wage', [
            'name' => 'Budi Santoso',
            'provinsi_id' => $provinsi->id,
            'bidang_kerja' => 'Teknologi Informasi & Digital',
            'jabatan' => 'Principal Engineer',
            'status_pekerjaan' => 'Tetap',
            'gaji_pokok' => 21000000,
            'alasan_pembaruan' => 'Kenaikan promosi ke jenjang Principal level',
        ]);

        $response->assertRedirect('/user');

        $user->refresh();
        $this->assertEquals(21000000, $user->gaji_pokok);
        $this->assertEquals('Principal Engineer', $user->jabatan);
        $this->assertEquals($initialLogCount + 1, WageLog::where('user_id', $user->id)->count());
    }

    public function test_authenticated_user_can_download_pdf_and_datasheet()
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);

        $pdfResponse = $this->get('/scientific-report/download?tahun=2026');
        $pdfResponse->assertStatus(200);
        $this->assertTrue(str_contains($pdfResponse->headers->get('content-type'), 'application/pdf'));

        $excelResponse = $this->get('/datasheet/export?format=xlsx');
        $excelResponse->assertStatus(200);
    }

    public function test_admin_can_delete_user_wage_log()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $log = WageLog::first();
        $logId = $log->id;

        $response = $this->delete("/admin/wage-logs/{$logId}");
        $response->assertRedirect('/admin');

        $this->assertDatabaseMissing('wage_logs', ['id' => $logId]);
    }

    public function test_user_page_loads_provincial_historical_chart_data()
    {
        $user = User::where('username', 'budi_santoso')->first();
        $this->actingAs($user);

        $response = $this->get('/user');
        $response->assertStatus(200);
        $response->assertSee('provincialChart', false);
        $response->assertSee('histData.map', false);
    }

    public function test_service_api_page_loads_and_live_json_filters_work()
    {
        // 1. Browser visit loads interactive API playground view
        $htmlResponse = $this->get('/service-api');
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('Service API &amp; Endpoint Fleksibel', false);
        $htmlResponse->assertSee('Live Request Tester', false);

        // 2. JSON request with flexible filters: type=ump & tahun=2026
        $jsonResponse = $this->getJson('/service-api?type=ump&tahun=2026&limit=5');
        $jsonResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.filters_applied.type', 'ump')
            ->assertJsonPath('meta.filters_applied.tahun', 2026);

        // 3. JSON request with type=pekerja
        $workerResponse = $this->getJson('/service-api?type=pekerja&limit=5');
        $workerResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.filters_applied.type', 'pekerja');

        // 4. JSON request with type=all (summary overview)
        $summaryResponse = $this->getJson('/service-api?type=all');
        $summaryResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'meta',
                'data' => [
                    'ump_resmi' => ['total_provinsi', 'rerata_nasional'],
                    'sensus_pekerja' => ['total_sampel_laporan', 'rerata_upah_riil']
                ]
            ]);
    }
}
