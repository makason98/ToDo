<?php

/**
 * One-time deployment helper. Access via /fix.php after uploading the project.
 *
 * What it does:
 *   1. Generates APP_KEY in .env if it's empty
 *   2. Runs `php artisan migrate --force` (no-op if database_export.sql was already imported)
 *   3. Creates the public/storage symlink
 *   4. Caches config / routes / views for production
 *   5. Tries to chmod storage/ and bootstrap/cache/ to writable
 *
 * SECURITY: DELETE THIS FILE after a successful run. Anyone who hits /fix.php can re-trigger it.
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(300);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function step(string $title, callable $callback): void
{
    echo "<section><h3>{$title}</h3>";
    try {
        $output = $callback();
        if ($output !== null && $output !== '') {
            echo "<pre>" . htmlspecialchars((string) $output) . "</pre>";
        }
        echo "<p class='ok'>OK</p>";
    } catch (\Throwable $e) {
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<p class='err'>FAILED</p>";
    }
    echo "</section>";
}

function run_artisan(string $command, array $params = []): string
{
    $output = new \Symfony\Component\Console\Output\BufferedOutput();
    \Illuminate\Support\Facades\Artisan::call($command, $params, $output);
    return $output->fetch();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Deploy fix</title>
    <style>
        body { font-family: -apple-system, system-ui, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; color: #1f2937; }
        h1 { color: #ef4444; }
        section { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem 1.25rem; margin-bottom: 1rem; background: #f9fafb; }
        section h3 { margin: 0 0 0.5rem; font-size: 1rem; }
        pre { background: #111827; color: #d1d5db; padding: 0.75rem; border-radius: 0.375rem; overflow-x: auto; font-size: 0.825rem; white-space: pre-wrap; }
        .ok { color: #059669; font-weight: 600; margin: 0.5rem 0 0; }
        .err { color: #dc2626; font-weight: 600; margin: 0.5rem 0 0; }
        .warn { background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; font-weight: 600; }
    </style>
</head>
<body>
    <h1>ToDo &mdash; deploy fix</h1>
    <p>Running one-time setup tasks&hellip;</p>

<?php

step('1. APP_KEY check', function () {
    $envPath = __DIR__ . '/../.env';
    if (! file_exists($envPath)) {
        throw new \RuntimeException('.env file not found. Copy .env.example or .env.production to .env first.');
    }
    $env = file_get_contents($envPath);
    if (preg_match('/^APP_KEY=base64:.+/m', $env)) {
        return 'APP_KEY already set, nothing to do.';
    }
    return run_artisan('key:generate', ['--force' => true]);
});

step('2. Run migrations', function () {
    return run_artisan('migrate', ['--force' => true]);
});

step('3. Create public/storage symlink', function () {
    $link = __DIR__ . '/storage';
    if (is_link($link) || file_exists($link)) {
        return 'Symlink (or directory) already exists at public/storage.';
    }
    return run_artisan('storage:link');
});

step('4. Cache config', function () {
    return run_artisan('config:cache');
});

step('5. Cache routes', function () {
    return run_artisan('route:cache');
});

step('6. Cache views', function () {
    return run_artisan('view:cache');
});

step('7. Set storage / bootstrap-cache writable (best effort)', function () {
    $dirs = [
        __DIR__ . '/../storage',
        __DIR__ . '/../bootstrap/cache',
    ];
    $report = [];
    foreach ($dirs as $dir) {
        $rel = str_replace(realpath(__DIR__ . '/..') . '/', '', realpath($dir) ?: $dir);
        $rdi = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        @chmod($dir, 0775);
        $count = 0;
        foreach ($rdi as $item) {
            @chmod($item->getPathname(), $item->isDir() ? 0775 : 0664);
            $count++;
        }
        $report[] = "{$rel}: chmodded {$count} entries";
    }
    return implode("\n", $report);
});

?>

    <div class="warn">
        Done. <strong>Delete <code>public/fix.php</code> from your server now</strong> &mdash; this script runs setup commands without authentication and should not stay reachable.
    </div>
</body>
</html>
