<?php

namespace App\Domains\Template\Repositories;

use App\Domains\Template\Models\Template;
use Illuminate\Support\Collection;

class TemplateRepository implements TemplateRepositoryInterface
{
    public function allActive(): Collection
    {
        return Template::where('is_active', true)->get();
    }

    public function findById(string $id): ?Template
    {
        return Template::find($id);
    }
}
