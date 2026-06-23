<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Template\Models\Template;
use App\Shared\Enums\TemplateStyle;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Circular',
                'style' => TemplateStyle::CIRCULAR,
                'description' => 'A creative template featuring a rounded dark-blue accent sidebar, personal details on the left, and skill-dot rating matrices.',
                'structure' => [
                    'font' => 'Inter',
                    'colors' => ['primary' => '#1e3a8a', 'accent' => '#3b82f6'],
                    'layout' => 'left-curved-sidebar',
                    'show_photo' => false,
                    'skill_format' => 'dots'
                ],
            ],
            [
                'name' => 'Professional',
                'style' => TemplateStyle::PROFESSIONAL,
                'description' => 'A sleek executive layout with a prominent dark header banner for name and title, optional headshot, and double-column business sections.',
                'structure' => [
                    'font' => 'Outfit',
                    'colors' => ['primary' => '#1e293b', 'accent' => '#64748b'],
                    'layout' => 'header-banner-split',
                    'show_photo' => true,
                    'skill_format' => 'tags'
                ],
            ],
            [
                'name' => 'Vertical',
                'style' => TemplateStyle::VERTICAL,
                'description' => 'Features a bold vertical accent stripe on the left margin, elegant sidebar navigation, and clean structured employment timelines.',
                'structure' => [
                    'font' => 'Roboto',
                    'colors' => ['primary' => '#991b1b', 'accent' => '#ef4444'],
                    'layout' => 'left-vertical-stripe',
                    'show_photo' => false,
                    'skill_format' => 'list'
                ],
            ],
            [
                'name' => 'Horizontal',
                'style' => TemplateStyle::HORIZONTAL,
                'description' => 'A modern design with an asymmetrical blue header block, integrated profile photo on the left, and a structured summary panel.',
                'structure' => [
                    'font' => 'Inter',
                    'colors' => ['primary' => '#2563eb', 'accent' => '#3b82f6'],
                    'layout' => 'asymmetrical-header',
                    'show_photo' => true,
                    'skill_format' => 'dots'
                ],
            ],
            [
                'name' => 'Elegant',
                'style' => TemplateStyle::ELEGANT,
                'description' => 'A premium template with a crimson-red name banner, a charcoal-grey sidebar for languages and qualities, and serif body text.',
                'structure' => [
                    'font' => 'Merriweather',
                    'colors' => ['primary' => '#881337', 'accent' => '#f43f5e'],
                    'layout' => 'charcoal-sidebar-crimson-header',
                    'show_photo' => false,
                    'skill_format' => 'list'
                ],
            ],
            [
                'name' => 'Modern',
                'style' => TemplateStyle::MODERN,
                'description' => 'Features an earthy terracotta/orange left sidebar containing personal details and hard skills, paired with a spacious right column.',
                'structure' => [
                    'font' => 'Nunito',
                    'colors' => ['primary' => '#7c2d12', 'accent' => '#ea580c'],
                    'layout' => 'terracotta-sidebar',
                    'show_photo' => false,
                    'skill_format' => 'bar-ratings'
                ],
            ],
            [
                'name' => 'Casual',
                'style' => TemplateStyle::CASUAL,
                'description' => 'A relaxed layout featuring a soft golden-brown column background for personal info, paired with professional Sans-serif typography.',
                'structure' => [
                    'font' => 'Roboto',
                    'colors' => ['primary' => '#854d0e', 'accent' => '#eab308'],
                    'layout' => 'golden-sidebar',
                    'show_photo' => false,
                    'skill_format' => 'dots'
                ],
            ],
            [
                'name' => 'Chrono',
                'style' => TemplateStyle::CHRONO,
                'description' => 'A clean, timeline-centric layout featuring clear chronological dividers, blue highlight bullet points, and high readability.',
                'structure' => [
                    'font' => 'Open Sans',
                    'colors' => ['primary' => '#2563eb', 'accent' => '#60a5fa'],
                    'layout' => 'timeline-split',
                    'show_photo' => false,
                    'skill_format' => 'tags'
                ],
            ],
            [
                'name' => 'Luxurious',
                'style' => TemplateStyle::LUXURIOUS,
                'description' => 'A high-end designer template featuring soft rose highlights, a clean profile photo frame on the right side, and spacious serif typography.',
                'structure' => [
                    'font' => 'Playfair Display',
                    'colors' => ['primary' => '#be123c', 'accent' => '#fda4af'],
                    'layout' => 'rose-accents-right-photo',
                    'show_photo' => true,
                    'skill_format' => 'list'
                ],
            ],
        ];

        foreach ($templates as $t) {
            Template::updateOrCreate(
                ['style' => $t['style']],
                [
                    'name' => $t['name'],
                    'description' => $t['description'],
                    'structure' => $t['structure'],
                    'is_active' => true,
                ]
            );
        }
    }
}
