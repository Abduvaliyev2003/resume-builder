<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\User\Models\User;

$user = new User();
echo "Incrementing: " . ($user->getIncrementing() ? 'yes' : 'no') . "\n";
echo "Key type: " . $user->getKeyType() . "\n";

$user->name = 'Test';
$user->email = 'test@example.com';
$user->password = 'password';

try {
    // We won't save to DB yet, just fire validation or simulate saving
    // by triggering events manually or saving
    $user->save();
    echo "Saved user: " . $user->id . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
