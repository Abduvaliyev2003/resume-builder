<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\TemplateSeeder;
use App\Domains\User\Models\User;
use App\Domains\Template\Models\Template;
use App\Domains\Resume\Models\Resume;
use Laravel\Sanctum\Sanctum;

class ResumeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Template $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TemplateSeeder::class);
        $this->user = User::factory()->create();
        $this->template = Template::first();
        
        Sanctum::actingAs($this->user);
    }

    public function test_user_can_list_templates(): void
    {
        $response = $this->getJson('/api/templates');

        $response->assertStatus(200)
            ->assertJsonCount(9, 'templates');
    }

    public function test_user_can_create_resume(): void
    {
        $response = $this->postJson('/api/resumes', [
            'title' => 'Software Engineer Resume',
            'template_id' => $this->template->id,
            'sections' => [
                [
                    'section_type' => 'contact',
                    'content' => [
                        'name' => 'John Doe',
                        'email' => 'john@example.com',
                        'phone' => '1234567890',
                    ],
                    'order_index' => 1
                ],
                [
                    'section_type' => 'summary',
                    'content' => [
                        'text' => 'Experienced software engineer specialized in PHP and Laravel.',
                    ],
                    'order_index' => 2
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'resume' => [
                    'id',
                    'title',
                    'score',
                    'sections' => [
                        '*' => ['id', 'section_type', 'content', 'order_index']
                    ]
                ]
            ]);

        // Expecting contact (+20) + summary (+15) = 35 score
        $this->assertEquals(35, $response->json('resume.score'));
    }

    public function test_user_can_update_resume(): void
    {
        $resume = Resume::create([
            'user_id' => $this->user->id,
            'title' => 'Initial Title',
            'template_id' => $this->template->id,
        ]);

        $response = $this->putJson("/api/resumes/{$resume->id}", [
            'title' => 'Updated Title',
            'template_id' => $this->template->id,
            'sections' => [
                [
                    'section_type' => 'contact',
                    'content' => [
                        'name' => 'John Doe',
                        'email' => 'john@example.com',
                        'phone' => '1234567890',
                    ],
                    'order_index' => 1
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('resume.title', 'Updated Title');
    }

    public function test_user_can_duplicate_resume(): void
    {
        $resume = Resume::create([
            'user_id' => $this->user->id,
            'title' => 'Original Resume',
            'template_id' => $this->template->id,
        ]);

        $response = $this->postJson("/api/resumes/{$resume->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonPath('resume.title', 'Original Resume (Copy)');
    }

    public function test_user_can_run_ai_ats_analysis(): void
    {
        $resume = Resume::create([
            'user_id' => $this->user->id,
            'title' => 'My Resume',
            'template_id' => $this->template->id,
        ]);

        $response = $this->postJson("/api/resumes/{$resume->id}/ats-analyze");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'review' => ['id', 'resume_id', 'review_type', 'score', 'feedback_data']
            ])
            ->assertJsonPath('review.review_type', 'ats');
    }

    public function test_user_can_export_and_download_resume(): void
    {
        $resume = Resume::create([
            'user_id' => $this->user->id,
            'title' => 'Exportable Resume',
            'template_id' => $this->template->id,
        ]);

        // Export
        $exportResponse = $this->postJson("/api/resumes/{$resume->id}/export", [
            'file_type' => 'pdf',
        ]);

        $exportResponse->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'file' => ['id', 'resume_id', 'file_type', 'download_url']
            ]);

        $downloadUrl = $exportResponse->json('file.download_url');
        
        // Extract token
        $urlParts = explode('/', $downloadUrl);
        $token = end($urlParts);

        // Download
        $downloadResponse = $this->get("/api/downloads/{$token}");
        $downloadResponse->assertStatus(200)
            ->assertHeader('content-disposition', 'attachment; filename=resume_' . $exportResponse->json('file.id') . '.pdf');
    }

    public function test_user_can_view_analytics(): void
    {
        $resume = Resume::create([
            'user_id' => $this->user->id,
            'title' => 'Analytics Resume',
            'template_id' => $this->template->id,
        ]);

        // Trigger log activity
        $this->postJson("/api/resumes/{$resume->id}/ats-analyze");

        // Stats
        $response = $this->getJson('/api/analytics/stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => ['total_resumes', 'total_exports', 'total_ai_reviews']
            ])
            ->assertJsonPath('stats.total_resumes', 1)
            ->assertJsonPath('stats.total_ai_reviews', 1);

        // Logs
        $logsResponse = $this->getJson('/api/analytics/logs');
        $logsResponse->assertStatus(200)
            ->assertJsonStructure([
                'logs' => [
                    '*' => ['id', 'user_id', 'action', 'details', 'created_at']
                ]
            ]);
    }
}
