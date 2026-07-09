<?php

namespace App\Domains\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey  = config('services.groq.api_key', '');
        $this->baseUrl = config('services.groq.base_url', 'https://api.groq.com/openai/v1');
        $this->model   = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    protected function getLanguageName(): string
    {
        return match (app()->getLocale()) {
            'uz' => 'Uzbek (in Uzbek cyrillic or latin depending on input, default latin)',
            'ru' => 'Russian',
            default => 'English',
        };
    }

    public function checkGrammar(string $text): array
    {
        $lang = $this->getLanguageName();
        $prompt = <<<PROMPT
You are a professional resume grammar checker. Analyze the following resume text and return a JSON object with:
- "has_issues": boolean
- "score": integer 0-100
- "corrections": array of {"original": string, "replacement": string, "reason": string}
- "suggestions": array of improvement tip strings

IMPORTANT: You must write the "reason" and "suggestions" text in {$lang} language. The original and replacement text should keep the language of the source text.
Return ONLY valid JSON, no markdown, no explanation.

Text to analyze:
{$text}
PROMPT;

        $raw = $this->callGroq($prompt);

        return $this->parseJson($raw) ?? $this->getMockGrammarResponse($text);
    }

    public function analyzeATS(array $resumeData): array
    {
        $json = json_encode($resumeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $lang = $this->getLanguageName();

        $prompt = <<<PROMPT
You are an expert ATS (Applicant Tracking System) analyzer. Analyze the following resume data and return a JSON object with:
- "score": integer 0-100 (ATS compatibility score)
- "ats_friendly": boolean
- "recommendations": array of actionable tip strings
- "keyword_matches": {"found": [string], "missing": [string]}

Base your score on: presence of key sections, use of action verbs, clean formatting indicators, and industry keywords.
IMPORTANT: You must write all "recommendations" strings in {$lang} language.
Return ONLY valid JSON, no markdown, no explanation.

Resume data:
{$json}
PROMPT;

        $raw = $this->callGroq($prompt);

        return $this->parseJson($raw) ?? $this->getMockATSResponse($resumeData);
    }

    public function detectMissingSections(array $sections): array
    {
        $list = implode(', ', $sections);
        $lang = $this->getLanguageName();

        $prompt = <<<PROMPT
You are a professional resume consultant. The candidate's resume has the following sections: [{$list}].

Analyze which standard resume sections are missing and return a JSON object with:
- "missing_sections": array of missing section name strings
- "importance_levels": object mapping section name to "High", "Medium", or "Low"
- "suggestions": array of strings explaining why each missing section matters

Standard sections to consider: contact, summary, experience, education, skills, certifications, languages, projects.
IMPORTANT: You must write all "suggestions" strings in {$lang} language.
Return ONLY valid JSON, no markdown, no explanation.
PROMPT;

        $raw = $this->callGroq($prompt);

        return $this->parseJson($raw) ?? $this->getMockMissingSectionsResponse($sections);
    }

    public function analyzeJobMatch(array $resumeData, string $jobDescription): array
    {
        $resumeJson = json_encode($resumeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $lang = $this->getLanguageName();

        $prompt = <<<PROMPT
You are a professional career coach and recruiter. Compare the resume data with the job description and return a JSON object with:
- "match_score": integer 0-100
- "matching_keywords": array of strings
- "missing_keywords": array of strings
- "recommendations": array of actionable improvement tip strings

IMPORTANT: You must write all "recommendations" strings in {$lang} language.
Return ONLY valid JSON, no markdown, no explanation.

Resume:
{$resumeJson}

Job Description:
{$jobDescription}
PROMPT;

        $raw = $this->callGroq($prompt);

        return $this->parseJson($raw) ?? $this->getMockJobMatchResponse($resumeData, $jobDescription);
    }

    public function improveText(string $text, string $context = 'resume'): array
    {
        $lang = $this->getLanguageName();
        $prompt = <<<PROMPT
You are a professional resume writer. Improve the following {$context} text to sound more professional, concise, and impactful. Use strong action verbs and quantify achievements where possible.

Return a JSON object with:
- "improved_text": string (the improved version, written in the same language as the original input text)
- "changes": array of strings describing what was changed and why (written in {$lang} language)

Return ONLY valid JSON, no markdown, no explanation.

Original text:
{$text}
PROMPT;

        $raw = $this->callGroq($prompt);

        return $this->parseJson($raw) ?? ['improved_text' => $text, 'changes' => []];
    }

    // ─────────────────────────────────────────────
    //  GROQ API CALL
    // ─────────────────────────────────────────────

    protected function callGroq(string $prompt): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('Groq API key is not configured. Using mock fallback.');
            return null;
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model'       => $this->model,
                    'messages'    => [
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens'  => 1024,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::warning('Groq API error: ' . $response->status() . ' - ' . $response->body());
        } catch (\Throwable $e) {
            Log::warning('Groq API connection failed: ' . $e->getMessage() . '. Using mock fallback.');
        }

        return null;
    }

    protected function parseJson(?string $raw): ?array
    {
        if (empty($raw)) {
            return null;
        }

        // Strip markdown code fences if present (```json ... ```)
        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
        $cleaned = preg_replace('/\s*```$/i', '', $cleaned);

        $decoded = json_decode(trim($cleaned), true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        Log::warning('Groq response JSON parse failed: ' . $raw);
        return null;
    }

    // ─────────────────────────────────────────────
    //  MOCK FALLBACKS (used when API key missing)
    // ─────────────────────────────────────────────

    protected function getMockGrammarResponse(string $text): array
    {
        $corrections = [];
        $suggestions = [];
        $words = explode(' ', $text);

        if (count($words) < 5) {
            $suggestions[] = 'Consider expanding your sentences to provide more professional detail.';
        }

        if (stripos($text, 'i am') !== false) {
            $corrections[] = [
                'original'    => 'I am',
                'replacement' => 'Responsible for',
                'reason'      => 'Use action verbs instead of first-person pronouns.',
            ];
        }

        return [
            'has_issues'  => !empty($corrections),
            'score'       => empty($corrections) ? 100 : 85,
            'corrections' => $corrections,
            'suggestions' => array_merge($suggestions, [
                'Use active voice throughout the text.',
                'Ensure consistent verb tenses in bullet points.',
            ]),
        ];
    }

    protected function getMockATSResponse(array $resumeData): array
    {
        $sections = array_column($resumeData['sections'] ?? [], 'section_type');
        $score = 50;

        if (in_array('contact', $sections))    $score += 15;
        if (in_array('experience', $sections)) $score += 15;
        if (in_array('education', $sections))  $score += 10;
        if (in_array('skills', $sections))     $score += 10;

        return [
            'score'            => $score,
            'ats_friendly'     => $score >= 70,
            'recommendations'  => [
                "Include standard headings like 'Work Experience' and 'Education'.",
                'Ensure your phone number and email are in plain text.',
                'Avoid complex graphics or multi-column layouts that ATS might struggle to parse.',
            ],
            'keyword_matches'  => [
                'found'   => ['laravel', 'php', 'postgresql', 'git'],
                'missing' => ['docker', 'redis', 'unit testing'],
            ],
        ];
    }

    protected function getMockMissingSectionsResponse(array $sections): array
    {
        $required = ['contact', 'summary', 'experience', 'education', 'skills'];
        $missing  = array_values(array_diff($required, $sections));

        return [
            'missing_sections'  => $missing,
            'importance_levels' => array_fill_keys($missing, 'High'),
            'suggestions'       => array_map(fn($sec) =>
                "The '{$sec}' section is critical for recruiters and should be added immediately.", $missing),
        ];
    }

    protected function getMockJobMatchResponse(array $resumeData, string $jobDescription): array
    {
        $keywords = ['php', 'laravel', 'postgres', 'docker', 'redis', 'aws', 'vue', 'react', 'api', 'solid'];
        $matches  = 0;

        foreach ($keywords as $kw) {
            if (stripos($jobDescription, $kw) !== false) {
                $matches++;
            }
        }

        $score = min(40 + ($matches * 6), 100);

        return [
            'match_score'       => $score,
            'matching_keywords' => array_values(array_filter($keywords, fn($kw) => stripos($jobDescription, $kw) !== false)),
            'missing_keywords'  => array_values(array_filter($keywords, fn($kw) => stripos($jobDescription, $kw) === false)),
            'recommendations'   => [
                'Tailor your profile summary to mention key skills matching the job description.',
                'Detail your experience with technologies mentioned in the job post (e.g., Docker, AWS).',
            ],
        ];
    }
}
