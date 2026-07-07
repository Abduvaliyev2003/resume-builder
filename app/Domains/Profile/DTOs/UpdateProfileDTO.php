<?php

namespace App\Domains\Profile\DTOs;

use Illuminate\Http\Request;

class UpdateProfileDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $username,
        public readonly ?string $phone,
        public readonly ?string $dateOfBirth,
        public readonly ?string $gender,
        public readonly ?string $jobTitle,
        public readonly ?string $company,
        public readonly ?string $country,
        public readonly ?string $city,
        public readonly ?string $website,
        public readonly ?string $bio,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name:        $request->input('name'),
            username:    $request->input('username') ?: null,
            phone:       $request->input('phone') ?: null,
            dateOfBirth: $request->input('date_of_birth') ?: null,
            gender:      $request->input('gender') ?: null,
            jobTitle:    $request->input('job_title') ?: null,
            company:     $request->input('company') ?: null,
            country:     $request->input('country') ?: null,
            city:        $request->input('city') ?: null,
            website:     $request->input('website') ?: null,
            bio:         $request->input('bio') ?: null,
        );
    }
}
