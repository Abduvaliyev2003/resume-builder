<?php

namespace App\Domains\File\Actions;

use App\Domains\File\Models\GeneratedFile;
use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\File\Events\ResumeExportedEvent;
use App\Shared\Enums\FileType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportResumeAction
{
    public function __construct(
        protected FileRepositoryInterface $fileRepository,
        protected ResumeRepositoryInterface $resumeRepository
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
        $text = $this->generateResumeTextContent($resume);

        if ($fileType === FileType::PDF) {
            return $this->generatePdfContent($text);
        }

        return $text;
    }

    protected function generateResumeTextContent($resume): string
    {
        $output = "==================================================\n";
        $output .= "               " . strtoupper($resume->title) . "               \n";
        $output .= "==================================================\n\n";

        foreach ($resume->sections as $section) {
            $output .= "--- " . strtoupper($section->section_type) . " ---\n";
            
            if (is_array($section->content)) {
                foreach ($section->content as $key => $value) {
                    if (is_array($value)) {
                        $output .= "- " . ucfirst($key) . ": " . json_encode($value) . "\n";
                    } else {
                        $output .= "- " . ucfirst($key) . ": " . $value . "\n";
                    }
                }
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
