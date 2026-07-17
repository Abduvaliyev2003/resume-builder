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
You are an expert professional resume editor. Carefully analyze the resume text below for grammar, spelling, punctuation, style, and professional tone issues.

Return a JSON object with exactly these fields:
- "has_issues": boolean — true if any issues were found
- "score": integer 0-100 — writing quality score (100 = perfect professional resume text)
- "corrections": array of objects, each with:
    - "original": the exact problematic phrase from the text
    - "replacement": the corrected version
    - "reason": a clear, specific explanation of WHY this change improves the resume (e.g. "First-person pronouns weaken resume impact; use action verbs instead" or "Passive voice is less compelling to recruiters than active voice")
- "suggestions": array of 3-5 specific, actionable improvement tips written as complete sentences

CRITICAL RULES:
- Write "reason" values and "suggestions" in {$lang} language
- Keep "original" and "replacement" in the source text language
- Be SPECIFIC — instead of "improve your writing", say "Add measurable results like 'increased sales by 30%' to your experience bullet points"
- Avoid generic advice. Each suggestion must directly relate to the analyzed text
- If no grammar issues exist, still provide 2-3 style improvement suggestions
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
You are a senior ATS (Applicant Tracking System) specialist with 10+ years experience evaluating resumes for Fortune 500 companies. Analyze the resume data below and score how well it will pass automated screening systems.

Return a JSON object with exactly these fields:
- "score": integer 0-100 — ATS compatibility score
- "ats_friendly": boolean — true if score >= 70
- "recommendations": array of 4-6 specific, actionable recommendations. Each item must:
    - Start with an action verb (Add, Include, Replace, Remove, Rewrite, etc.)
    - Explain WHAT to do and WHY it matters for ATS parsing
    - Be concrete (e.g. "Add a 'Skills' section listing at least 8-12 specific technical skills using exact keywords from job postings, because ATS systems match these verbatim")
- "keyword_matches": object with:
    - "found": array of relevant keywords/skills detected in the resume
    - "missing": array of common industry keywords that are absent but would boost ATS score

SCORING CRITERIA:
- Contact section present with email and phone: +15 pts
- Professional summary/objective: +10 pts
- Work experience with action verbs and dates: +20 pts
- Education section: +10 pts
- Skills section with specific technologies: +15 pts
- Certifications or languages: +5 pts each (max 10)
- Clean single-column structure indicators: +10 pts
- Use of quantified achievements (numbers, percentages): +10 pts

CRITICAL RULES:
- Write all "recommendations" in {$lang} language
- Be SPECIFIC to this actual resume — do not give generic advice
- If a section is missing, say exactly which section and exactly how to add it
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
You are a professional resume consultant who has reviewed thousands of resumes for top companies. The candidate's resume currently has these sections: [{$list}].

Analyze which important sections are missing and return a JSON object with exactly these fields:
- "missing_sections": array of section name strings that are absent
- "importance_levels": object mapping each missing section name to "High", "Medium", or "Low"
- "suggestions": array of specific explanations — one per missing section — written as complete, helpful paragraphs that explain:
    1. WHY this section matters (recruiter perspective)
    2. WHAT to include in it specifically
    3. HOW it will improve the candidate's chances

Standard sections to evaluate: contact, summary, experience, education, skills, certifications, languages, projects.

Example of a GOOD suggestion (not generic):
"Adding a 'Professional Summary' section (2-3 sentences at the top) helps recruiters instantly understand your value proposition. Include your years of experience, your main skill area, and one key achievement. Without it, recruiters may skip your resume in under 6 seconds."

CRITICAL RULES:
- Write all "suggestions" in {$lang} language
- Only list sections that are genuinely missing from [{$list}]
- If no sections are missing, return empty arrays and add a positive note
- Be SPECIFIC and PRACTICAL, not generic
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
You are a senior recruiter and career coach. Your job is to compare this candidate's resume against the job description and give them an honest, detailed, actionable match analysis.

Return a JSON object with exactly these fields:
- "match_score": integer 0-100 — how well the resume matches this specific job (be accurate, not generous)
- "matching_keywords": array of specific skills, tools, and qualifications found in BOTH the resume and job description
- "missing_keywords": array of specific skills, technologies, or qualifications mentioned in the job description but ABSENT from the resume
- "recommendations": array of 4-6 SPECIFIC, PERSONALIZED action items. Each recommendation must:
    - Reference actual content from the resume or job description
    - Tell the candidate EXACTLY what to add, change, or emphasize
    - Explain WHY that change will increase their match score
    - Example: "The job requires 'Docker and Kubernetes experience' but your resume doesn't mention containers. Add a bullet point in your experience section describing any containerization work, or list Docker in your skills section."

SCORING GUIDE:
- 80-100: Strong match — candidate should apply immediately
- 60-79: Good match with minor gaps — apply and address gaps in cover letter
- 40-59: Partial match — significant upskilling or resume rewriting needed
- Below 40: Weak match — consider if this role is the right target

CRITICAL RULES:
- Write all "recommendations" in {$lang} language
- Be HONEST about the score — do not inflate it
- Be SPECIFIC to this exact resume and job description — no generic advice
- If there are critical missing requirements (e.g., required degree, years of experience), explicitly call them out
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
You are an elite resume writer who has helped candidates land jobs at top companies. Your task is to rewrite the following {$context} text to make it significantly more professional, impactful, and compelling for recruiters and ATS systems.

Return a JSON object with exactly these fields:
- "improved_text": string — the fully rewritten, polished version (keep it in the SAME language as the original text)
- "changes": array of 3-6 specific explanations of what you changed and why. Each item must:
    - Describe the SPECIFIC change made (not just "improved the tone")
    - Explain WHY this change makes the text stronger
    - Be written in {$lang} language
    - Example: "Changed 'I was responsible for managing the team' to 'Led a 5-person engineering team' — removes first-person pronoun, uses a strong action verb, and adds a specific number which is 40% more compelling to recruiters"

IMPROVEMENT RULES:
- Start bullet points with strong action verbs (Led, Built, Optimized, Delivered, Reduced, Increased, etc.)
- Replace vague phrases with specific, quantified achievements where possible
- Remove first-person pronouns (I, me, my, we)
- Use active voice instead of passive voice
- Keep it concise — cut filler words
- Maintain the factual content — do not invent information

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
                    'temperature' => 0.2,
                    'max_tokens'  => 2048,
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
        // ── 1. Build a plain text blob from the resume ────────────────────────
        $resumeText = $resumeData['title'] ?? '';
        foreach ($resumeData['sections'] ?? [] as $section) {
            $content = $section['content'] ?? [];
            $resumeText .= ' ' . $this->flattenContent($content);
        }
        $resumeText = mb_strtolower($resumeText);

        // ── 2. Extract meaningful keywords from the job description ───────────
        // Strip common stop-words and keep only words with 4+ characters
        $stopWords = [
            'that', 'this', 'with', 'have', 'will', 'from', 'they', 'been',
            'more', 'also', 'into', 'your', 'their', 'about', 'which', 'when',
            'able', 'must', 'should', 'would', 'could', 'work', 'team', 'role',
            'good', 'well', 'using', 'used', 'need', 'like', 'make', 'such',
            'both', 'each', 'some', 'what', 'than', 'then', 'only', 'very',
        ];

        preg_match_all('/\b[a-zA-Z][a-zA-Z0-9+#.\-]{3,}\b/', $jobDescription, $found);
        $jobWords = array_unique(array_map('strtolower', $found[0] ?? []));
        $jobWords = array_values(array_filter($jobWords, fn($w) => !in_array($w, $stopWords)));

        // ── 3. Split into matched / missing ──────────────────────────────────
        $matching = [];
        $missing  = [];
        foreach ($jobWords as $word) {
            if (str_contains($resumeText, $word)) {
                $matching[] = $word;
            } else {
                $missing[] = $word;
            }
        }

        // Keep top 10 of each for readability
        $matching = array_slice($matching, 0, 10);
        $missing  = array_slice($missing,  0, 10);

        // ── 4. Score based on match ratio ─────────────────────────────────────
        $total = count($jobWords) ?: 1;
        $score = (int) min(round((count($matching) / $total) * 100), 100);

        // ── 5. Generic recommendations that apply to any profession ───────────
        $topMissing = implode(', ', array_slice($missing, 0, 3));
        $recommendations = [
            'Rewrite your Professional Summary to include 2-3 keywords directly from the job description so recruiters and ATS systems immediately see the relevance.',
            $topMissing
                ? "The job description mentions these terms not found in your resume: \"{$topMissing}\". Add them where accurate in your Skills or Experience sections."
                : 'Your resume covers most keywords from the job description. Focus on quantifying your achievements with numbers and results.',
            'Mirror the exact job title used in the posting inside your resume title or summary to boost ATS keyword matching.',
            'Tailor each bullet point in your Experience section to highlight responsibilities and outcomes that directly match what the employer described.',
        ];

        return [
            'match_score'       => $score,
            'matching_keywords' => $matching,
            'missing_keywords'  => $missing,
            'recommendations'   => $recommendations,
        ];
    }

    /**
     * Recursively flatten a section content array to a plain string.
     * Works for any section type (contact, skills, experience, education, etc.)
     */
    protected function flattenContent(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (is_array($value)) {
            return implode(' ', array_map(fn($v) => $this->flattenContent($v), $value));
        }
        return (string) $value;
    }
}
