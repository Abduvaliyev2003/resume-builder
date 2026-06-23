<?php

namespace App\Domains\Resume\Repositories;

use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Models\ResumeSection;
use App\Domains\Resume\Models\ResumeVersion;
use Illuminate\Support\Collection;

class ResumeRepository implements ResumeRepositoryInterface
{
    public function create(array $data): Resume
    {
        return Resume::create($data);
    }

    public function update(string $id, array $data): Resume
    {
        $resume = Resume::findOrFail($id);
        $resume->update($data);
        return $resume;
    }

    public function delete(string $id): bool
    {
        $resume = Resume::find($id);
        if ($resume) {
            return $resume->delete();
        }
        return false;
    }

    public function findById(string $id): ?Resume
    {
        return Resume::with(['sections', 'versions', 'template'])->find($id);
    }

    public function getUserResumes(string $userId): Collection
    {
        return Resume::with(['template'])->where('user_id', $userId)->get();
    }

    public function updateOrCreateSection(string $resumeId, string $type, array $content, int $orderIndex): ResumeSection
    {
        return ResumeSection::updateOrCreate(
            ['resume_id' => $resumeId, 'section_type' => $type],
            ['content' => $content, 'order_index' => $orderIndex]
        );
    }

    public function createVersion(string $resumeId, int $versionNumber, array $resumeData): ResumeVersion
    {
        return ResumeVersion::create([
            'resume_id' => $resumeId,
            'version_number' => $versionNumber,
            'resume_data' => $resumeData,
        ]);
    }
}
