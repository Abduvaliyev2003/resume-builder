<?php

namespace App\Domains\Resume\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'template_id' => ['nullable', 'uuid', 'exists:templates,id'],
            'sections' => ['nullable', 'array'],
            'sections.*.section_type' => ['required', 'string'],
            'sections.*.content' => ['required', 'array'],
            'sections.*.order_index' => ['nullable', 'integer'],
        ];
    }

    /**
     * Deep section content validation.
     *
     * WHY THIS EXISTS:
     * Raw Laravel array validation cannot easily perform complex conditional validation
     * on polymorphic JSON columns (like the `content` array of `sections`).
     * Using the `withValidator` after-hook allows us to run custom logic on specific
     * section types:
     * - 'contact': Email/phone formatting.
     * - 'summary': Max length of 600 chars (prevents template layout breaking).
     * - 'skills': Enforce unique tags.
     * - 'experience'/'education': Chronological validation (end_date >= start_date).
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sections = $this->input('sections', []);
            if (!is_array($sections)) {
                return;
            }

            foreach ($sections as $index => $section) {
                $type = $section['section_type'] ?? '';
                $content = $section['content'] ?? [];

                if (!is_array($content)) {
                    continue;
                }

                if ($type === 'contact') {
                    if (!empty($content['email']) && !filter_var($content['email'], FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add("sections.{$index}.content.email", 'The contact email must be a valid email address.');
                    }
                    if (!empty($content['phone']) && !preg_match('/^\+?[0-9\s\-()]{7,20}$/', $content['phone'])) {
                        $validator->errors()->add("sections.{$index}.content.phone", 'The contact phone number format is invalid.');
                    }
                }

                if ($type === 'summary') {
                    if (!empty($content['text']) && mb_strlen($content['text']) > 600) {
                        $validator->errors()->add("sections.{$index}.content.text", 'The profile summary must not exceed 600 characters.');
                    }
                }

                if ($type === 'skills') {
                    if (!empty($content['list']) && is_array($content['list'])) {
                        $trimmed = array_filter(array_map('trim', $content['list']));
                        if (count($trimmed) !== count(array_unique($trimmed))) {
                            $validator->errors()->add("sections.{$index}.content.list", 'The skills list must contain unique items.');
                        }
                    }
                }

                if ($type === 'experience' || $type === 'education') {
                    $items = $content['items'] ?? [];
                    if (is_array($items)) {
                        foreach ($items as $itemIndex => $item) {
                            $startDate = $item['start_date'] ?? null;
                            $endDate = $item['end_date'] ?? null;
                            $isPresent = $item['is_present'] ?? false;

                            if ($startDate && $endDate && !$isPresent) {
                                if (strcmp($startDate, $endDate) > 0) {
                                    $fieldName = $type === 'experience' ? 'job' : 'study period';
                                    $validator->errors()->add(
                                        "sections.{$index}.content.items.{$itemIndex}.end_date",
                                        "The end date cannot be earlier than the start date for this {$fieldName}."
                                    );
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
