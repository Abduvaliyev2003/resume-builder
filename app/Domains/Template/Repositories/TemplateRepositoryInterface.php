<?php

namespace App\Domains\Template\Repositories;

use App\Domains\Template\Models\Template;
use Illuminate\Support\Collection;

interface TemplateRepositoryInterface
{
    public function allActive(): Collection;
    public function findById(string $id): ?Template;
}
