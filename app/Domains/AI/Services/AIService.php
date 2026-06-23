<?php

namespace App\Domains\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.fastapi.url', env('FASTAPI_SERVICE_URL', 'http://localhost:8000'));
        $this->apiKey = config('services.fastapi.key', env('FASTAPI_API_KEY', 'secret'));
    }

    public function checkGrammar(string $text): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->timeout(5)->post("{$this->baseUrl}/api/v1/grammar-check", [
                'text' => $text,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::warning("FastAPI Connection Failed: " . $e->getMessage() . ". Using mock fallback.");
        }

        // Mock Fallback
        return $this->getMockGrammarResponse($text);
    }

    public function analyzeATS(array $resumeData): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->timeout(8)->post("{$this->baseUrl}/api/v1/ats-analyze", [
                'resume_data' => $resumeData,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::warning("FastAPI Connection Failed: " . $e->getMessage() . ". Using mock fallback.");
        }

        // Mock Fallback
        return $this->getMockATSResponse($resumeData);
    }

    public function detectMissingSections(array $sections): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->timeout(5)->post("{$this->baseUrl}/api/v1/missing-sections", [
                'sections' => $sections,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::warning("FastAPI Connection Failed: " . $e->getMessage() . ". Using mock fallback.");
        }

        // Mock Fallback
        return $this->getMockMissingSectionsResponse($sections);
    }

    public function analyzeJobMatch(array $resumeData, string $jobDescription): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->timeout(8)->post("{$this->baseUrl}/api/v1/job-match", [
                'resume_data' => $resumeData,
                'job_description' => $jobDescription,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::warning("FastAPI Connection Failed: " . $e->getMessage() . ". Using mock fallback.");
        }

        // Mock Fallback
        return $this->getMockJobMatchResponse($resumeData, $jobDescription);
    }

    protected function getMockGrammarResponse(string $text): array
    {
        // Simple mock analysis logic
        $corrections = [];
        $suggestions = [];
        $words = explode(' ', $text);

        if (count($words) < 5) {
            $suggestions[] = "Consider expanding your sentences to provide more professional detail.";
        }

        if (stripos($text, 'i am') !== false) {
            $corrections[] = [
                'original' => 'I am',
                'replacement' => 'Responsible for',
                'reason' => 'Use action verbs instead of first-person pronouns.',
            ];
        }

        return [
            'has_issues' => !empty($corrections),
            'score' => empty($corrections) ? 100 : 85,
            'corrections' => $corrections,
            'suggestions' => array_merge($suggestions, [
                "Use active voice throughout the text.",
                "Ensure consistent verb tenses in bullet points."
            ]),
        ];
    }

    protected function getMockATSResponse(array $resumeData): array
    {
        $sections = array_column($resumeData['sections'] ?? [], 'section_type');
        $score = 50;
        
        if (in_array('contact', $sections)) $score += 15;
        if (in_array('work', $sections) || in_array('experience', $sections)) $score += 15;
        if (in_array('education', $sections)) $score += 10;
        if (in_array('skills', $sections)) $score += 10;

        return [
            'score' => $score,
            'ats_friendly' => $score >= 70,
            'recommendations' => [
                "Include standard headings like 'Work Experience' and 'Education'.",
                "Ensure your phone number and email are in plain text.",
                "Avoid complex graphics or multi-column layouts that ATS might struggle to parse."
            ],
            'keyword_matches' => [
                'found' => ['laravel', 'php', 'postgresql', 'git'],
                'missing' => ['docker', 'redis', 'unit testing']
            ]
        ];
    }

    protected function getMockMissingSectionsResponse(array $sections): array
    {
        $required = ['contact', 'summary', 'work', 'education', 'skills'];
        $missing = array_values(array_diff($required, $sections));

        return [
            'missing_sections' => $missing,
            'importance_levels' => array_fill_keys($missing, 'High'),
            'suggestions' => array_map(function($sec) {
                return "The '{$sec}' section is critical for recruiters and should be added immediately.";
            }, $missing)
        ];
    }

    protected function getMockJobMatchResponse(array $resumeData, string $jobDescription): array
    {
        // Simple mock matching keywords
        $matches = 0;
        $keywords = ['php', 'laravel', 'postgres', 'docker', 'redis', 'aws', 'vue', 'react', 'api', 'solid'];
        
        foreach ($keywords as $kw) {
            if (stripos($jobDescription, $kw) !== false) {
                $matches++;
            }
        }

        $score = min(40 + ($matches * 6), 100);

        return [
            'match_score' => $score,
            'matching_keywords' => array_values(array_filter($keywords, fn($kw) => stripos($jobDescription, $kw) !== false)),
            'missing_keywords' => array_values(array_filter($keywords, fn($kw) => stripos($jobDescription, $kw) === false)),
            'recommendations' => [
                "Tailor your profile summary to mention key skills matching the job description.",
                "Detail your experience with technologies mentioned in the job post (e.g., Docker, AWS)."
            ]
        ];
    }
}
