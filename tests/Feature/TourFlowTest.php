<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TourFlowTest extends TestCase
{
    use RefreshDatabase;

    /** A real 1x1 PNG (no GD needed). */
    private function fakePng(): UploadedFile
    {
        $bytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent('tour.png', $bytes);
    }

    private function approvedCompany(): User
    {
        return User::create([
            'name' => 'Test Co',
            'email' => 'co@test.az',
            'password' => 'password',
            'role' => User::ROLE_COMPANY,
            'status' => User::STATUS_APPROVED,
        ]);
    }

    public function test_approved_company_can_create_a_pending_tour_with_image(): void
    {
        $company = $this->approvedCompany();

        $response = $this->actingAs($company)->post(route('company.tours.store'), [
            'title' => 'Murovdağ Yürüşü',
            'description' => 'Qısa təsvir',
            'content' => '<p>Ətraflı məzmun</p>',
            'price' => 120,
            'image' => $this->fakePng(),
        ]);

        $response->assertRedirect(route('company.tours.index'));

        $tour = Tour::where('title', 'Murovdağ Yürüşü')->first();
        $this->assertNotNull($tour);
        $this->assertSame(Tour::STATUS_PENDING, $tour->status);
        $this->assertSame($company->id, $tour->user_id);
        $this->assertNotNull($tour->image);
        $this->assertNotEmpty($tour->slug);

        // The image was physically moved into the public uploads folder.
        $path = public_path('uploads/tours/'.$tour->image);
        $this->assertFileExists($path);
        @unlink($path); // cleanup
    }

    public function test_pending_company_cannot_create_tours(): void
    {
        $company = User::create([
            'name' => 'Pending Co',
            'email' => 'pending@test.az',
            'password' => 'password',
            'role' => User::ROLE_COMPANY,
            'status' => User::STATUS_PENDING,
        ]);

        $this->actingAs($company)
            ->get(route('company.tours.create'))
            ->assertRedirect(route('company.dashboard'));

        $this->actingAs($company)->post(route('company.tours.store'), [
            'title' => 'Blocked Tour',
            'description' => 'x',
            'content' => '<p>x</p>',
            'price' => 10,
            'image' => $this->fakePng(),
        ])->assertRedirect(route('company.dashboard'));

        $this->assertDatabaseMissing('tours', ['title' => 'Blocked Tour']);
    }

    public function test_admin_approval_publishes_tour_on_home(): void
    {
        $company = $this->approvedCompany();

        $tour = Tour::create([
            'user_id' => $company->id,
            'title' => 'Hidden Until Approved',
            'slug' => 'hidden-until-approved',
            'description' => 'desc',
            'content' => '<p>c</p>',
            'price' => 50,
            'status' => Tour::STATUS_PENDING,
        ]);

        // Not visible while pending.
        $this->get(route('home'))->assertDontSee('Hidden Until Approved');

        // Admin approves.
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.az',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tours.approve', $tour))
            ->assertRedirect();

        $this->assertSame(Tour::STATUS_APPROVED, $tour->fresh()->status);

        // Now visible on the public home page.
        $this->get(route('home'))->assertSee('Hidden Until Approved');
    }

    public function test_guest_is_redirected_from_admin_area(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('company.dashboard'))->assertRedirect(route('company.login'));
    }
}
