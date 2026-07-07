<?php

namespace Tests\Feature;

use App\Domains\User\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'created_at'],
                'token'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_register_sends_verification_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'jane@example.com')->first();
        Notification::assertSentTo($user, \App\Domains\User\Notifications\EmailVerificationCodeNotification::class);
    }

    public function test_telegram_login_creates_new_user_and_sends_verification_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/telegram/login', [
            'email' => 'telegram-new@example.com',
            'password' => 'telegram-pass',
            'telegram_id' => 999001,
            'telegram_username' => 'telegramuser',
            'telegram_first_name' => 'Telegram',
            'telegram_last_name' => 'User',
        ]);

        $response->assertStatus(200);

        $user = User::where('email', 'telegram-new@example.com')->first();
        $this->assertNotNull($user);
        Notification::assertSentTo($user, \App\Domains\User\Notifications\EmailVerificationCodeNotification::class);
    }

    public function test_telegram_login_existing_user_does_not_send_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'telegram-existing@example.com',
            'password' => bcrypt('telegram-pass'),
        ]);

        $response = $this->postJson('/api/telegram/login', [
            'email' => 'telegram-existing@example.com',
            'password' => 'telegram-pass',
            'telegram_id' => 999002,
            'telegram_username' => 'existing',
            'telegram_first_name' => 'Existing',
            'telegram_last_name' => 'User',
        ]);

        $response->assertStatus(200);
        Notification::assertNotSentTo($user, \App\Domains\User\Notifications\EmailVerificationCodeNotification::class);
    }

    public function test_verified_route_requires_verified_email(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/resumes');

        $response->assertStatus(403);
    }

    public function test_code_verification_marks_email_as_verified(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
            'password' => bcrypt('password123'),
        ]);

        $code = app(\App\Domains\User\Services\EmailVerificationService::class)->generateCode($user);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/email/verify-code', [
            'code' => $code,
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_web_code_verification_marks_email_as_verified(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
            'password' => bcrypt('password123'),
        ]);

        $code = app(\App\Domains\User\Services\EmailVerificationService::class)->generateCode($user);

        $response = $this->actingAs($user)->post('/email/verify', [
            'code' => $code,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_invalid_code_is_rejected(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
            'password' => bcrypt('password123'),
        ]);

        app(\App\Domains\User\Services\EmailVerificationService::class)->generateCode($user);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/email/verify-code', [
            'code' => '000000', // Invalid code
        ]);

        $response->assertStatus(422);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_get_verification_status(): void
    {
        $unverifiedUser = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
        ]);

        $response = $this->actingAs($unverifiedUser, 'sanctum')->getJson('/api/email/verify-status');
        $response->assertStatus(200)
            ->assertJson(['verified' => false]);

        $verifiedUser = User::factory()->create([
            'email' => 'verified@example.com',
        ]);

        $response = $this->actingAs($verifiedUser, 'sanctum')->getJson('/api/email/verify-status');
        $response->assertStatus(200)
            ->assertJson(['verified' => true]);
    }

    public function test_verification_code_expires_in_ten_minutes(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'expiry@example.com',
            'password' => bcrypt('password123'),
        ]);

        app(\App\Domains\User\Services\EmailVerificationService::class)->generateCode($user);

        $verificationCode = \App\Domains\User\Models\EmailVerificationCode::where('user_id', $user->id)->first();

        $this->assertNotNull($verificationCode);
        $this->assertEqualsWithDelta(
            now()->addMinutes(10)->timestamp,
            $verificationCode->expires_at->timestamp,
            5
        );
    }

    public function test_send_verification_notification(): void
    {
        Notification::fake();

        $unverifiedUser = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
        ]);

        $response = $this->actingAs($unverifiedUser, 'sanctum')->postJson('/api/email/verification-notification');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Verification code sent.']);

        Notification::assertSentTo($unverifiedUser, \App\Domains\User\Notifications\EmailVerificationCodeNotification::class);
    }

    public function test_send_verification_notification_already_verified(): void
    {
        Notification::fake();

        $verifiedUser = User::factory()->create([
            'email' => 'verified@example.com',
        ]);

        $response = $this->actingAs($verifiedUser, 'sanctum')->postJson('/api/email/verification-notification');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Email already verified.']);

        Notification::assertNotSentTo($verifiedUser, \App\Domains\User\Notifications\EmailVerificationCodeNotification::class);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'created_at'],
                'token'
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(422);
    }
}
