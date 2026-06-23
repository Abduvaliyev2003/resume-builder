<?php

namespace App\Domains\File\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Resume\Services\ResumeService;
use App\Domains\File\Services\FileService;
use App\Domains\File\Requests\ExportResumeRequest;
use App\Domains\File\Resources\GeneratedFileResource;
use App\Shared\Enums\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function __construct(
        protected ResumeService $resumeService,
        protected FileService $fileService
    ) {}

    public function export(string $id, ExportResumeRequest $request): JsonResponse
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            return response()->json(['message' => 'Resume not found.'], 404);
        }

        if ($request->user()->cannot('view', $resume)) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $fileType = FileType::from($request->validated('file_type'));
        $file = $this->fileService->exportResume($id, $fileType);

        return response()->json([
            'message' => 'File exported successfully.',
            'file' => new GeneratedFileResource($file),
        ]);
    }

    public function download(string $token): BinaryFileResponse|JsonResponse
    {
        $file = $this->fileService->findFileByToken($token);

        if (!$file) {
            return response()->json(['message' => 'Download link is invalid.'], 404);
        }

        if ($file->expires_at && $file->expires_at->isPast()) {
            return response()->json(['message' => 'Download link has expired.'], 410);
        }

        $absolutePath = Storage::disk('local')->path($file->file_path);

        if (!Storage::disk('local')->exists($file->file_path)) {
            return response()->json(['message' => 'File does not exist on disk.'], 404);
        }

        $downloadName = 'resume_' . $file->id . '.' . $file->file_type->value;

        return response()->download($absolutePath, $downloadName);
    }
}
