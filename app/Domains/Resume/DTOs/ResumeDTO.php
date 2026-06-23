<?php

namespace App\Domains\Resume\DTOs;

use App\Shared\DTOs\BaseDTO;

class ResumeDTO extends BaseDTO
{
    /**
     * @param ResumeSectionDTO[] $sections
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $template_id = null,
        public readonly array $sections = []
    ) {}

    public static function fromArray(array $data): static
    {
        $sections = array_map(function ($section) {
            return $section instanceof ResumeSectionDTO ? $section : ResumeSectionDTO::fromArray($section);
        }, $data['sections'] ?? []);

        return new static(
            title: $data['title'],
            template_id: $data['template_id'] ?? null,
            sections: $sections
        );
    }
}
