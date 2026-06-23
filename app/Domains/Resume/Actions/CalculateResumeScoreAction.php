<?php

namespace App\Domains\Resume\Actions;

use App\Domains\Resume\Models\Resume;

class CalculateResumeScoreAction
{
    public function execute(Resume $resume): int
    {
        $score = 0;
        
        // Reload sections if not loaded
        $sections = $resume->sections;

        foreach ($sections as $section) {
            $content = $section->content;
            
            switch ($section->section_type) {
                case 'contact':
                    if (!empty($content['email']) && !empty($content['phone'])) {
                        $score += 20;
                    }
                    break;
                case 'summary':
                    if (!empty($content['text']) && strlen($content['text']) > 50) {
                        $score += 15;
                    }
                    break;
                case 'experience':
                case 'work':
                    if ($this->hasItems($content)) {
                        $score += 25;
                    }
                    break;
                case 'education':
                    if ($this->hasItems($content)) {
                        $score += 20;
                    }
                    break;
                case 'skills':
                    if (!empty($content['list'] ?? [])) {
                        $score += 20;
                    }
                    break;
            }
        }

        return min($score, 100);
    }

    protected function hasItems(array $content): bool
    {
        if (isset($content['items']) && is_array($content['items'])) {
            return count(array_filter($content['items'])) > 0;
        }

        return count(array_filter($content)) > 0;
    }
}
