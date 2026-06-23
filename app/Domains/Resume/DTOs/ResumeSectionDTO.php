<?php

namespace App\Domains\Resume\DTOs;

use App\Shared\DTOs\BaseDTO;

class ResumeSectionDTO extends BaseDTO
{
    public function __construct(
        public readonly string $section_type,
        public readonly array $content,
        public readonly int $order_index = 0
    ) {}
}
