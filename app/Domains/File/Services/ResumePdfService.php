<?php

namespace App\Domains\File\Services;

use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Services\ResumeTemplateRenderer;
use Spatie\Browsershot\Browsershot;

/**
 * ResumePdfService — PDF Generation using Browsershot (headless Chrome)
 *
 * WHY BROWSERSHOT:
 * DomPDF cannot render modern CSS (flexbox, grid, custom fonts, CSS variables).
 * wkhtmltopdf is unmaintained. Browsershot uses real Chrome, so the PDF looks
 * identical to the browser preview — including colors, fonts, icons, and layout.
 *
 * WHY WE USE ResumeTemplateRenderer HERE:
 * Previously this service called `view('resumes.pdf', ...)` directly — a single
 * generic template that ignored the selected template style. Now it delegates to
 * ResumeTemplateRenderer which picks the correct Blade file per template.style.
 *
 * WHAT PROBLEM IT SOLVES:
 * Exported PDF now visually matches the live preview — same colors, fonts,
 * layout, sidebar, icons, profile photo, and template-specific styling.
 */

class ResumePdfService
{
    public function __construct(
        protected ResumeTemplateRenderer $templateRenderer
    ) {}

    /**
     * Render the resume as an HTML string using the correct template.
     *
     * WHY: Previously used a hardcoded `resumes.pdf` view that ignored the
     * selected template. Now delegates to ResumeTemplateRenderer which selects
     * the correct Blade file based on template->style.
     */
    public function renderHtml(Resume $resume): string
    {
        return $this->templateRenderer->renderForPdf($resume);
    }

    /**
     * Generate a real PDF binary string using headless Chrome (Browsershot).
     *
     * noSandbox() is required for Linux/Docker environments where Chrome
     * cannot run with its default sandbox (kernel permission restrictions).
     *
     * If Browsershot fails (e.g. Chrome not installed), falls back to a minimal
     * plain-text PDF so the user always gets something downloadable.
     */
    public function generate(Resume $resume): string
    {
        $html = $this->renderHtml($resume);

        try {
            return Browsershot::html($html)
                ->noSandbox()           // Required for Linux / Docker environments
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()      // Preserve background colors in sidebar templates
                ->waitUntilNetworkIdle() // Wait for Google Fonts to load
                ->timeout(60)
                ->pdf();
        } catch (\Throwable $e) {
            report($e);

            return $this->generateFallbackPdf($resume);
        }
    }

    protected function generateFallbackPdf(Resume $resume): string
    {
        $lines = [
            $resume->title,
            'Generated from selected template: ' . ($resume->template?->name ?? 'Default'),
            '',
        ];

        foreach ($resume->sections as $section) {
            $lines[] = strtoupper(str_replace('_', ' ', $section->section_type));
            $content = $section->content;

            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    if (in_array($key, ['photo', 'phone_country'], true)) {
                        continue;
                    }

                    if (is_scalar($value) && trim((string) $value) !== '') {
                        $lines[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
                    }

                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (is_array($item)) {
                                $parts = array_filter($item, fn($part, $partKey) => !in_array($partKey, ['photo'], true) && is_scalar($part) && trim((string) $part) !== '', ARRAY_FILTER_USE_BOTH);
                                if (!empty($parts)) {
                                    $lines[] = '- ' . implode(' | ', $parts);
                                }
                            } elseif (is_scalar($item) && trim((string) $item) !== '') {
                                $lines[] = '- ' . $item;
                            }
                        }
                    }
                }
            }

            $lines[] = '';
        }

        return $this->minimalPdf(implode("\n", $lines));
    }

    protected function minimalPdf(string $text): string
    {
        $lines = array_slice(preg_split("/\r\n|\n|\r/", $text), 0, 58);
        $streamLines = ['BT', '/F1 10 Tf', '50 790 Td', '14 TL'];

        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $streamLines[] = 'T*';
            }

            $streamLines[] = '(' . $this->escapePdfText((string) $line) . ') Tj';
        }

        $streamLines[] = 'ET';
        $stream = implode("\n", $streamLines);
        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n",
            "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
            "5 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n{$stream}\nendstream\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        return $pdf . "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF\n";
    }

    protected function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
