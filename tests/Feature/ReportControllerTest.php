<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Donor;
use App\Models\BloodRequest;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // create an admin user and authenticate
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    public function test_donor_report_page_shows_statistics()
    {
        Donor::factory()->count(3)->create(['blood_type' => 'A+', 'is_available' => true]);

        $response = $this->get(route('admin.reports.generate', ['report_type' => 'donors']));
        $response->assertStatus(200);
        $response->assertSee('Total Donors');
        $response->assertSee('By Blood Type');
    }

    public function test_request_report_page_shows_tables()
    {
        BloodRequest::factory()->count(2)->create(['status' => 'open', 'urgency_level' => 'high']);

        $response = $this->get(route('admin.reports.generate', ['report_type' => 'requests']));
        $response->assertStatus(200);
        $response->assertSee('Status Breakdown');
        $response->assertSee('Urgency Breakdown');
    }

    public function test_donation_report_page_shows_totals()
    {
        Donation::factory()->count(1)->create(['status' => 'completed', 'quantity' => 2]);

        $response = $this->get(route('admin.reports.generate', ['report_type' => 'donations']));
        $response->assertStatus(200);
        $response->assertSee('Total Donations');
        $response->assertSee('Completed');
    }

    public function test_csv_export_for_donor_report() {
        Donor::factory()->create(['blood_type' => 'B-', 'is_available' => true]);

        $response = $this->get(route('admin.reports.generate', ['report_type' => 'donors', 'format' => 'csv']));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $this->assertStringContainsString('Blood Type,Count', $response->getContent());
    }
}
