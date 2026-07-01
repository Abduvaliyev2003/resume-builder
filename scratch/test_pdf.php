<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\File\Actions\ExportResumeAction;
use App\Shared\Enums\FileType;

try {
    $resume = \App\Domains\Resume\Models\Resume::first();
    if (!$resume) {
        echo "No resume found in database!\n";
        exit(1);
    }
    echo "Found resume ID: " . $resume->id . " Title: " . $resume->title . "\n";
    
    $action = $app->make(ExportResumeAction::class);
    $generatedFile = $action->execute($resume->id, FileType::PDF);
    
    echo "Successfully generated! Path: " . $generatedFile->file_path . "\n";
} catch (\Throwable $e) {
    echo "ERROR OCCURRED:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
