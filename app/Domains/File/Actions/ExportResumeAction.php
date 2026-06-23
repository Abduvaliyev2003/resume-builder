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
}
