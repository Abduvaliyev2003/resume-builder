<?php

namespace App\Domains\AI\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Resume\Services\ResumeService;
use App\Domains\AI\Actions\RunGrammarCheckAction;
use App\Domains\AI\Actions\RunATSAnalysisAction;
use App\Domains\AI\Actions\DetectMissingSectionsAction;
use App\Domains\AI\Actions\AnalyzeJobMatchAction;
use App\Domains\AI\Repositories\AIRepositoryInterface;
use App\Domains\AI\Requests\CheckGrammarRequest;
use App\Domains\AI\Requests\AnalyzeJobMatchRequest;
use App\Domains\AI\Resources\AIReviewResource;
use App\Domains\AI\Resources\JobTargetResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function __construct(
        protected ResumeService $resumeService,
        protected AIRepositoryInterface $aiRepository
    ) {}

    protected function getAuthorizedResume(string $id, Request $request)
    {
        $resume = $this->resumeService->getResume($id);

        if (!$resume) {
            abort(404, 'Resume not found.');
        }

        if ($request->user()->cannot('view', $resume)) {
            abort(403, 'This action is unauthorized.');
        }

        return $resume;
    }

    public function grammarCheck(string $id, CheckGrammarRequest $request, RunGrammarCheckAction $action): JsonResponse
    {
        $this->getAuthorizedResume($id, $request);

        $review = $action->execute($id, $request->validated('text'));

        return response()->json([
            'message' => 'Grammar check completed.',
            'review' => new AIReviewResource($review),
        ]);
    }

    public function atsAnalyze(string $id, Request $request, RunATSAnalysisAction $action): JsonResponse
    {
        $this->getAuthorizedResume($id, $request);

        $review = $action->execute($id);

        return response()->json([
            'message' => 'ATS analysis completed.',
            'review' => new AIReviewResource($review),
        ]);
    }

    public function missingSections(string $id, Request $request, DetectMissingSectionsAction $action): JsonResponse
    {
        $this->getAuthorizedResume($id, $request);

        $review = $action->execute($id);

        return response()->json([
            'message' => 'Missing sections analysis completed.',
            'review' => new AIReviewResource($review),
        ]);
    }

    public function jobMatch(string $id, AnalyzeJobMatchRequest $request, AnalyzeJobMatchAction $action): JsonResponse
    {
        $this->getAuthorizedResume($id, $request);

        $target = $action->execute(
            $id,
            $request->validated('job_title'),
            $request->validated('job_description')
        );

        return response()->json([
            'message' => 'Job match analysis completed.',
            'target' => new JobTargetResource($target),
        ]);
    }

    public function history(string $id, Request $request): JsonResponse
    {
        $this->getAuthorizedResume($id, $request);

        $reviews = $this->aiRepository->getReviewsForResume($id);

        return response()->json([
            'reviews' => AIReviewResource::collection($reviews),
        ]);
    }
}
