<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $monthsMap = [
            'jan' => '01', 'january' => '01',
            'feb' => '02', 'february' => '02',
            'mar' => '03', 'march' => '03',
            'apr' => '04', 'april' => '04',
            'may' => '05',
            'jun' => '06', 'june' => '06',
            'jul' => '07', 'july' => '07',
            'aug' => '08', 'august' => '08',
            'sep' => '09', 'september' => '09',
            'oct' => '10', 'october' => '10',
            'nov' => '11', 'november' => '11',
            'dec' => '12', 'december' => '12'
        ];

        // Retrieve all resume sections
        $sections = DB::table('resume_sections')
            ->whereIn('section_type', ['experience', 'education'])
            ->get();

        foreach ($sections as $section) {
            $content = json_decode($section->content, true);
            if (!$content || empty($content['items'])) {
                continue;
            }

            $modified = false;
            foreach ($content['items'] as &$item) {
                if ($section->section_type === 'experience') {
                    // Skip if start_date is already set
                    if (isset($item['start_date']) && !empty($item['start_date'])) {
                        continue;
                    }

                    $duration = trim($item['duration'] ?? '');
                    if (empty($duration)) {
                        continue;
                    }

                    $startDate = '';
                    $endDate = null;
                    $isPresent = false;

                    // Parse duration like: "Jan 2022 - Present" or "Jan 2022 - Dec 2023" or "2020 - 2022"
                    $parts = explode('-', $duration);
                    if (count($parts) === 2) {
                        $startPart = trim($parts[0]);
                        $endPart = trim($parts[1]);

                        // Parse start date
                        $startDate = $this->parseDateString($startPart, $monthsMap);

                        // Parse end date
                        if (preg_match('/(present|current|now)/i', $endPart)) {
                            $isPresent = true;
                        } else {
                            $endDate = $this->parseDateString($endPart, $monthsMap) ?: null;
                        }
                    } else {
                        // Fallback: single year or single date string
                        $startDate = $this->parseDateString($duration, $monthsMap);
                    }

                    // Assign structured fields
                    $item['start_date'] = $startDate;
                    $item['end_date'] = $endDate;
                    $item['is_present'] = $isPresent;

                    // Populate builder helper fields
                    $startParts = explode('-', $startDate);
                    $item['start_year'] = $startParts[0] ?? '';
                    $item['start_month'] = isset($startParts[1]) ? (string)(int)$startParts[1] : '';

                    if ($endDate) {
                        $endParts = explode('-', $endDate);
                        $item['end_year'] = $endParts[0] ?? '';
                        $item['end_month'] = isset($endParts[1]) ? (string)(int)$endParts[1] : '';
                    } else {
                        $item['end_year'] = '';
                        $item['end_month'] = '';
                    }

                    $modified = true;
                } elseif ($section->section_type === 'education') {
                    // Skip if start_date or end_date is already set
                    if (isset($item['start_date']) && !empty($item['start_date'])) {
                        continue;
                    }

                    $yearVal = trim($item['year'] ?? '');
                    if (empty($yearVal)) {
                        continue;
                    }

                    $startDate = '';
                    $endDate = null;
                    $isPresent = false;

                    $parts = explode('-', $yearVal);
                    if (count($parts) === 2) {
                        $startPart = trim($parts[0]);
                        $endPart = trim($parts[1]);

                        $startDate = $this->parseDateString($startPart, $monthsMap);
                        if (preg_match('/(present|current|now)/i', $endPart)) {
                            $isPresent = true;
                        } else {
                            $endDate = $this->parseDateString($endPart, $monthsMap) ?: null;
                        }
                    } else {
                        // Single graduation year like "2024"
                        $endDate = $this->parseDateString($yearVal, $monthsMap) ?: null;
                    }

                    $item['start_date'] = $startDate;
                    $item['end_date'] = $endDate;
                    $item['is_present'] = $isPresent;

                    // Populate builder helper fields
                    if ($startDate) {
                        $startParts = explode('-', $startDate);
                        $item['start_year'] = $startParts[0] ?? '';
                        $item['start_month'] = isset($startParts[1]) ? (string)(int)$startParts[1] : '';
                    } else {
                        $item['start_year'] = '';
                        $item['start_month'] = '';
                    }

                    if ($endDate) {
                        $endParts = explode('-', $endDate);
                        $item['end_year'] = $endParts[0] ?? '';
                        $item['end_month'] = isset($endParts[1]) ? (string)(int)$endParts[1] : '';
                    } else {
                        $item['end_year'] = '';
                        $item['end_month'] = '';
                    }

                    $modified = true;
                }
            }

            if ($modified) {
                DB::table('resume_sections')
                    ->where('id', $section->id)
                    ->update(['content' => json_encode($content)]);
            }
        }
    }

    /**
     * Parse date string into YYYY-MM format.
     */
    private function parseDateString(string $str, array $monthsMap): string
    {
        $str = strtolower(trim($str));

        // Format: "jan 2024" or "january 2024"
        if (preg_match('/([a-z]+)\s+(\d{4})/', $str, $matches)) {
            $monthWord = $matches[1];
            $year = $matches[2];
            $monthNum = $monthsMap[$monthWord] ?? '01';
            return "{$year}-{$monthNum}";
        }

        // Format: "2024"
        if (preg_match('/^\d{4}$/', $str)) {
            return "{$str}-01";
        }

        // Format: "01/2024" or "01-2024"
        if (preg_match('/(\d{1,2})[\/\-](\d{4})/', $str, $matches)) {
            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $year = $matches[2];
            return "{$year}-{$month}";
        }

        return $str; // Return as-is if parsing fails
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback is necessary as data expansion is backward compatible
    }
};
