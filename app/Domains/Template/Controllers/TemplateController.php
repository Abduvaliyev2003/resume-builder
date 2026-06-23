<?php

namespace App\Domains\Template\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Template\Services\TemplateService;
use App\Domains\Template\Resources\TemplateResource;
use Illuminate\Http\JsonResponse;

class TemplateController extends Controller
{
    public function __construct(
        protected TemplateService $templateService
    ) {}

    public function index(): JsonResponse
    {
        $templates = $this->templateService->getActiveTemplates();

        return response()->json([
            'templates' => TemplateResource::collection($templates),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $template = $this->templateService->getTemplateById($id);

        if (!$template) {
            return response()->json(['message' => 'Template not found.'], 404);
        }

        return response()->json([
            'template' => new TemplateResource($template),
        ]);
    }
}
