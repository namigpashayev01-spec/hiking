<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_contact_form(): void
    {
        $this->post(route('contact.send'), [
            'name' => 'Aysel Quliyeva',
            'email' => 'aysel@example.com',
            'phone' => '+994 50 111 22 33',
            'subject' => 'Tour about Şahdağ',
            'message' => 'I am interested in the Şahdağ tour next month.',
        ])->assertRedirect(route('contact'))
          ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'aysel@example.com',
            'subject' => 'Tour about Şahdağ',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_requires_fields(): void
    {
        $this->post(route('contact.send'), [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_admin_sees_message_and_can_mark_read(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.az',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
        ]);

        $message = ContactMessage::create([
            'name' => 'Visitor',
            'email' => 'v@test.az',
            'subject' => 'Hello',
            'message' => 'Hi',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Hello');

        $this->actingAs($admin)
            ->get(route('admin.messages.show', $message))
            ->assertOk();

        $this->assertTrue($message->fresh()->is_read);
    }
}
