<?php

namespace App\Domains\Template\Services;

use App\Domains\Template\Repositories\TemplateRepositoryInterface;
use Illuminate\Support\Collection;
use App\Domains\Template\Models\Template;

class TemplateService
{
    public function __construct(
        protected TemplateRepositoryInterface $templateRepository
    ) {}

    public function getActiveTemplates(): Collection
    {
        return $this->templateRepository->allActive();
    }

    public function getTemplateById(string $id): ?Template
    {
        return $this->templateRepository->findById($id);
    }
}
