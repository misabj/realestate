<?php
/**
 * PRIVREMENI DEPLOY SKRIPT
 * OBRIŠI OVAJ FAJL ODMAH NAKON KORIŠĆENJA!
 */
$SECRET = 'pcn2026deploy'; // PROMENI OVO pre uploada

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    die('Forbidden');
}

$action = $_GET['action'] ?? 'status';

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

header('Content-Type: text/plain; charset=UTF-8');

$commands = match($action) {
    'cache'  => [
        'config:clear',
        'config:cache',
        'route:clear',
        'route:cache',
        'view:clear',
        'optimize',
    ],
    'migrate' => ['migrate', '--force'],
    'link'    => ['storage:link'],
    'clear'   => [
        'optimize:clear',
    ],
    default => ['--version'],
};

if ($action === 'migrate') {
    $status = $kernel->call('migrate', ['--force' => true]);
    echo $kernel->output();
} elseif ($action === 'link') {
    $status = $kernel->call('storage:link');
    echo $kernel->output();
} else {
    foreach ($commands as $cmd) {
        echo "Running: php artisan $cmd\n";
        $kernel->call($cmd);
        echo $kernel->output();
        echo "---\n";
    }
}

$kernel->terminate(
    Illuminate\Http\Request::capture(),
    new Illuminate\Http\Response()
);

echo "\nDone. OBRISI deploy.php ODMAH!\n";
