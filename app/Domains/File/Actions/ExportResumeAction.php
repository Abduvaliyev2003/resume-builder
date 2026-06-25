<?php

namespace App\Domains\File\Actions;

use App\Domains\File\Models\GeneratedFile;
use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Domains\File\Services\ResumePdfService;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\File\Events\ResumeExportedEvent;
use App\Shared\Enums\FileType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportResumeAction
{
    public function __construct(
        protected FileRepositoryInterface $fileRepository,
        protected ResumeRepositoryInterface $resumeRepository,
        protected ResumePdfService $resumePdfService
    ) {}

    public function execute(string $resumeId, FileType $fileType): GeneratedFile
    {
        $resume = $this->resumeRepository->findById($resumeId);
        if (!$resume) {
            throw new \InvalidArgumentException("Resume not found");
        }

        // Generate content for the file
        $content = $this->generateResumeFileContent($resume, $fileType);

        // Define file path and name
        $token = Str::random(40);
        $fileName = "resumes/{$resume->id}/resume_{$token}." . $fileType->value;

        // Save file to Laravel Storage
        Storage::disk('local')->put($fileName, $content);

        // Save generated file record
        $generatedFile = $this->fileRepository->create([
            'resume_id' => $resumeId,
            'file_type' => $fileType,
            'file_path' => $fileName,
            'download_token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        event(new ResumeExportedEvent($generatedFile));

        return $generatedFile;
    }

    protected function generateResumeFileContent($resume, FileType $fileType): string
    {
        if ($fileType === FileType::PDF) {
            return $this->resumePdfService->generate($resume);
        }

        return $this->generateResumeTextContent($resume);
    }

    protected function generateResumeTextContent($resume): string
    {
        $output = "==================================================\n";
        $output .= "               " . strtoupper($resume->title) . "               \n";
        $output .= "==================================================\n\n";

        foreach ($resume->sections as $section) {
            $output .= "--- " . strtoupper($section->section_type) . " ---\n";
            
            if (is_array($section->content)) {
                $output .= $this->formatSectionContent($section->content);
            } else {
                $output .= $section->content . "\n";
            }
            $output .= "\n";
        }

        $output .= "Generated via AI Resume Builder SaaS\n";
        $output .= "Template Style: " . ($resume->template?->name ?? 'Default') . "\n";
        $output .= "Generated at: " . now()->toDateTimeString() . "\n";

        return $output;
    }

    protected function formatSectionContent(array $content): string
    {
        $output = '';

        foreach ($content as $key => $value) {
            if (in_array($key, ['photo', 'phone_country'], true)) {
                continue;
            }

            if (is_array($value)) {
                if ($key === 'items') {
                    foreach ($value as $index => $item) {
                        if (!is_array($item)) {
                            continue;
                        }

                        $output .= '- Item ' . ($index + 1) . "\n";

                        foreach ($item as $itemKey => $itemValue) {
                            if ($this->isPrintableValue($itemValue)) {
                                $output .= '  ' . ucfirst(str_replace('_', ' ', $itemKey)) . ': ' . $itemValue . "\n";
                            }
                        }
                    }

                    continue;
                }

                if ($key === 'list') {
                    $printable = array_values(array_filter($value, fn($item) => $this->isPrintableValue($item)));
                    if (!empty($printable)) {
                        $output .= '- ' . implode(', ', $printable) . "\n";
                    }

                    continue;
                }

                continue;
            }

            if ($this->isPrintableValue($value)) {
                $output .= '- ' . ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . "\n";
            }
        }

        return $output;
    }

    protected function isPrintableValue(mixed $value): bool
    {
        return is_scalar($value) && trim((string) $value) !== '';
    }

    protected function generatePdfContent(string $text): string
    {
        $lines = [];

        foreach (preg_split("/\r\n|\n|\r/", $text) as $line) {
            $line = trim(preg_replace('/\s+/', ' ', $line));

            if ($line === '') {
                $lines[] = '';
                continue;
            }

            foreach (str_split($line, 92) as $chunk) {
                $lines[] = $chunk;
            }
        }

        $lines = array_slice($lines, 0, 56);
        $streamLines = ['BT', '/F1 10 Tf', '50 790 Td', '14 TL'];

        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $streamLines[] = 'T*';
            }

            $streamLines[] = '(' . $this->escapePdfText($line) . ') Tj';
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

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF\n";

        return $pdf;
    }

    protected function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
