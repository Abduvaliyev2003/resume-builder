<?php

namespace App\Domains\Resume\Services;

use App\Domains\Resume\Models\Resume;

/**
 * ResumeTemplateRenderer — Strategy-based Template Rendering Service
 *
 * WHY THIS EXISTS:
 * Previously, PDF export used a single `resumes.pdf` Blade file and the preview
 * used inline `@if($layout === ...)` chains in `preview.blade.php`. This meant
 * the same layout logic was duplicated across two places, and adding a new template
 * required editing multiple files.
 *
 * WHAT PROBLEM IT SOLVES:
 * - PDF did not match the selected template (it used its own simplified layout).
 * - Adding a new template required changes in 3+ places.
 * - Preview and PDF could drift out of sync visually.
 *
 * DESIGN PATTERN — Strategy + Service Locator:
 * Each template style maps to a dedicated Blade file. The service resolves the
 * correct "strategy" (Blade view) at runtime based on `template->style`.
 * All consumers (PDF, preview, print, future DOCX) call this service.
 *
 * HOW TO EXTEND:
 * 1. Create `resources/views/resume/templates/yourtemplate.blade.php`
 * 2. Add 'yourtemplate' to the $templateMap array below.
 * 3. Done — no other file needs changing.
 */
class ResumeTemplateRenderer
{
    /**
     * Maps template style slugs to Blade view paths.
     *
     * The key is the value stored in templates.style column.
     * The value is the Blade view name under resources/views/.
     */
    protected array $templateMap = [
        'professional' => 'resume.templates.professional',
        'circular'     => 'resume.templates.circular',
        'vertical'     => 'resume.templates.vertical',
        'elegant'      => 'resume.templates.elegant',
        'modern'       => 'resume.templates.modern',
        'chrono'       => 'resume.templates.chrono',
        'horizontal'   => 'resume.templates.horizontal',
        'luxurious'    => 'resume.templates.luxurious',
    ];

    /**
     * Default template used when no match is found.
     * Must exist in the templateMap.
     */
    protected string $fallbackTemplate = 'resume.templates.professional';

    /**
     * Render the resume as HTML, using the correct template for the selected style.
     *
     * This is the primary method used by:
     *  - ResumePdfService (Browsershot PDF generation)
     *  - Preview controller (live server-side preview)
     *  - Print endpoint
     *
     * @param Resume $resume  The resume Eloquent model (with sections + template loaded)
     * @param bool   $forPdf  When true, adds PDF-specific CSS (page sizes, print fonts)
     */
    public function render(Resume $resume, bool $forPdf = false): string
    {
        $resume->loadMissing(['sections', 'template']);

        $data = $this->normalizeResumeData($resume);
        $viewName = $this->resolveView($resume);

        return view($viewName, array_merge($data, [
            'resume'  => $resume,
            'for_pdf' => $forPdf,
        ]))->render();
    }

    /**
     * Convenience method for PDF generation — automatically enables PDF mode.
     */
    public function renderForPdf(Resume $resume): string
    {
        return $this->render($resume, true);
    }

    /**
     * Resolve the Blade view name for the given resume's template style.
     *
     * If the template style is unknown (e.g. a new template was added to the DB
     * but no Blade file created yet), it falls back to the professional template.
     */
    public function resolveView(Resume $resume): string
    {
        $style = $resume->template?->style ?? '';

        if (isset($this->templateMap[$style]) && view()->exists($this->templateMap[$style])) {
            return $this->templateMap[$style];
        }

        return $this->fallbackTemplate;
    }

    /**
     * Normalize all resume section data into a flat, predictable array.
     *
     * WHY NORMALIZE:
     * Raw section content is stored as JSON in the database. Different sections
     * have different keys. Normalizing here means every Blade template receives
     * the same variable names, reducing template-level PHP logic.
     *
     * Each Blade template receives these variables:
     *   $contact, $summary, $skills, $experience, $education,
     *   $certifications, $languages, $primaryColor, $accentColor, $templateStyle
     */
    public function normalizeResumeData(Resume $resume): array
    {
        $sections = $resume->sections->keyBy('section_type');

        $contact        = $sections->get('contact')?->content        ?? [];
        if (empty($contact['email']) && auth()->check()) {
            $contact['email'] = auth()->user()->email;
        }

        $summary        = $sections->get('summary')?->content        ?? [];
        $skills         = $sections->get('skills')?->content         ?? [];
        $experience     = $sections->get('experience')?->content     ?? [];
        $education      = $sections->get('education')?->content      ?? [];
        $certifications = $sections->get('certifications')?->content ?? [];
        $languages      = $sections->get('languages')?->content      ?? [];

        // Normalize experience items — compute formatted duration from structured dates
        if (!empty($experience['items'])) {
            $experience['items'] = array_map(
                fn ($item) => $this->normalizeExperienceItem($item),
                $experience['items']
            );
        }

        // Normalize education items — compute formatted year from structured dates
        if (!empty($education['items'])) {
            $education['items'] = array_map(
                fn ($item) => $this->normalizeEducationItem($item),
                $education['items']
            );
        }

        return [
            'contact'        => $contact,
            'summary'        => $summary,
            'skills'         => $skills,
            'experience'     => $experience,
            'education'      => $education,
            'certifications' => $certifications,
            'languages'      => $languages,
            'primaryColor'   => $resume->template?->structure['colors']['primary'] ?? '#2563eb',
            'accentColor'    => $resume->template?->structure['colors']['accent']  ?? '#60a5fa',
            'templateStyle'  => $resume->template?->style ?? 'professional',
        ];
    }

    /**
     * Normalize a single experience item.
     *
     * OLD: { "duration": "Jan 2024 - Present" }
     * NEW: { "start_date": "2024-01", "end_date": null, "is_present": true }
     *
     * This method produces a `duration` display string from structured data,
     * while also preserving legacy free-text duration strings for backward compatibility.
     */
    protected function normalizeExperienceItem(array $item): array
    {
        // If new structured format exists, compute duration from it
        if (isset($item['start_date'])) {
            $item['duration'] = $this->formatDateRange(
                $item['start_date'],
                $item['end_date'] ?? null,
                $item['is_present'] ?? false
            );
        }
        // Else: legacy free-text `duration` is preserved as-is

        return $item;
    }

    /**
     * Normalize a single education item.
     *
     * OLD: { "year": "2024" }
     * NEW: { "start_date": "2020-09", "end_date": "2024-06", "is_present": false }
     */
    protected function normalizeEducationItem(array $item): array
    {
        if (isset($item['end_date']) || isset($item['start_date'])) {
            $endDate   = $item['end_date'] ?? null;
            $isPresent = $item['is_present'] ?? false;

            $item['year'] = $isPresent
                ? 'Present'
                : $this->formatSingleDate($endDate ?: ($item['start_date'] ?? ''));
        }

        return $item;
    }

    /**
     * Format a date range into a human-readable string.
     *
     * Examples:
     *   formatDateRange('2022-01', null, true)        → "Jan 2022 – Present"
     *   formatDateRange('2022-01', '2025-03', false)  → "Jan 2022 – Mar 2025"
     *   formatDateRange('2022-01', null, false)        → "Jan 2022"
     */
    public function formatDateRange(?string $startDate, ?string $endDate, bool $isPresent = false): string
    {
        $start = $this->formatSingleDate($startDate);

        if ($isPresent) {
            return $start ? "{$start} – Present" : 'Present';
        }

        if ($endDate) {
            $end = $this->formatSingleDate($endDate);
            return $start && $end ? "{$start} – {$end}" : ($start ?: $end);
        }

        return $start;
    }

    /**
     * Convert a YYYY-MM date string to "Mon YYYY" format.
     *
     * Input:  "2024-01"
     * Output: "Jan 2024"
     */
    protected function formatSingleDate(?string $date): string
    {
        if (!$date) {
            return '';
        }

        $parts = explode('-', $date);
        if (count($parts) < 2) {
            return $date; // Return as-is if format is unexpected
        }

        $year  = $parts[0];
        $month = (int) $parts[1];

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
        ];

        return ($months[$month] ?? '') . ' ' . $year;
    }
}
