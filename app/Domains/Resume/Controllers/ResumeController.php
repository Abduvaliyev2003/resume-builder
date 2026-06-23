<?php

namespace App\Domains\Resume\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Resume\Requests\CreateResumeRequest;
use App\Domains\Resume\Requests\UpdateResumeRequest;
use App\Domains\Resume\DTOs\ResumeDTO;
use App\Domains\Resume\Services\ResumeService;
use App\Domains\Resume\Resources\ResumeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ResumeController extends Controller
{
    public function __construct(
        protected ResumeService $resumeService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $resumes = $this->resumeService->getResumesForUser($request->user()->id);

        return response()->json([
            'resumes' => ResumeResource::collection($resumes),
        ]);
    }

    public function store(CreateResumeRequest $request): JsonResponse
    {
        $dto = ResumeDTO::fromArray($request->validated());
        $resume = $this->resumeService->createResume($request->user()->id, $dto);

        // Load relations for response
        $resume->load(['sections', 'template', 'versions']);

        return response()->json([
            'message' => 'Resume created successfully.',
            'resume' => new ResumeResource($resume),
        ], 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            return response()->json(['message' => 'Resume not found.'], 404);
        }

        // Authorize access
        if ($request->user()->cannot('view', $resume)) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        return response()->json([
            'resume' => new ResumeResource($resume),
        ]);
    }

    public function update(string $id, UpdateResumeRequest $request): JsonResponse
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            return response()->json(['message' => 'Resume not found.'], 404);
        }

        // Authorize access
        if ($request->user()->cannot('update', $resume)) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $dto = ResumeDTO::fromArray($request->validated());
        $updatedResume = $this->resumeService->updateResume($id, $dto);

        $updatedResume->load(['sections', 'template', 'versions']);

        return response()->json([
            'message' => 'Resume updated successfully.',
            'resume' => new ResumeResource($updatedResume),
        ]);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            return response()->json(['message' => 'Resume not found.'], 404);
        }

        // Authorize access
        if ($request->user()->cannot('delete', $resume)) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $this->resumeService->deleteResume($id);

        return response()->json([
            'message' => 'Resume deleted successfully.',
        ]);
    }

    public function duplicate(string $id, Request $request): JsonResponse
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            return response()->json(['message' => 'Resume not found.'], 404);
        }

        // Authorize access
        if ($request->user()->cannot('view', $resume)) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $duplicated = $this->resumeService->duplicateResume($id);
        $duplicated->load(['sections', 'template', 'versions']);

        return response()->json([
            'message' => 'Resume duplicated successfully.',
            'resume' => new ResumeResource($duplicated),
        ], 201);
    }
}
