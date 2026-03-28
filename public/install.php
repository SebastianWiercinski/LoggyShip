<?php
/**
 * LoggyShip Web Installer
 *
 * Run once via browser: https://your-domain.com/install.php
 * This script will self-delete after successful execution.
 */

// Prevent caching
header('Cache-Control: no-store');
header('Content-Type: text/html; charset=utf-8');

echo "<pre style='font-family:monospace;font-size:14px;max-width:800px;margin:40px auto;'>\n";
echo "🚀 LoggyShip Web Installer\n";
echo str_repeat('=', 40) . "\n\n";

$errors = [];

// 1. Check if .env exists
echo "1. Checking .env file... ";
if (file_exists(__DIR__ . '/../.env')) {
    echo "✅ Found\n";
} else {
    // Try to copy from .env.production
    if (file_exists(__DIR__ . '/../.env.production')) {
        copy(__DIR__ . '/../.env.production', __DIR__ . '/../.env');
        echo "✅ Created from .env.production\n";
    } else {
        echo "❌ Missing! Creating default...\n";
        $errors[] = '.env not found and no .env.production template';
    }
}

// 2. Create storage directories
echo "2. Creating storage directories... ";
$dirs = [
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/app',
    __DIR__ . '/../bootstrap/cache',
    __DIR__ . '/../database',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}
echo "✅ Done\n";

// 3. Check write permissions
echo "3. Checking write permissions...\n";
$checkDirs = ['storage', 'storage/logs', 'storage/framework', 'bootstrap/cache', 'database'];
foreach ($checkDirs as $d) {
    $path = __DIR__ . '/../' . $d;
    $writable = is_writable($path);
    echo "   $d: " . ($writable ? "✅ Writable" : "❌ Not writable") . "\n";
    if (!$writable) {
        @chmod($path, 0775);
        if (is_writable($path)) {
            echo "   → Fixed with chmod\n";
        } else {
            $errors[] = "$d is not writable";
        }
    }
}

// 4. Create SQLite database
echo "4. Creating SQLite database... ";
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    @touch($dbPath);
    @chmod($dbPath, 0664);
    echo "✅ Created\n";
} else {
    echo "✅ Already exists\n";
}

// 5. Update .env with correct APP_URL
echo "5. Updating APP_URL in .env... ";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $appUrl = $protocol . '://' . $host;
    $env = preg_replace('/^APP_URL=.*/m', 'APP_URL=' . $appUrl, $env);
    file_put_contents($envPath, $env);
    echo "✅ Set to $appUrl\n";
}

// 6. Run artisan commands via PHP
echo "6. Running migrations...\n";

$artisan = __DIR__ . '/../artisan';
if (!file_exists($artisan)) {
    echo "   ❌ artisan file not found!\n";
    $errors[] = 'artisan not found';
} else {
    // Run migrate
    $output = [];
    $code = 0;
    exec('cd ' . escapeshellarg(dirname($artisan)) . ' && php artisan migrate --force 2>&1', $output, $code);
    foreach ($output as $line) {
        echo "   $line\n";
    }
    if ($code !== 0) {
        $errors[] = 'Migration failed';
    } else {
        echo "   ✅ Migrations complete\n";
    }

    // Config cache
    echo "7. Caching config...\n";
    $output = [];
    exec('cd ' . escapeshellarg(dirname($artisan)) . ' && php artisan config:cache 2>&1', $output, $code);
    foreach ($output as $line) {
        echo "   $line\n";
    }

    // Route cache
    echo "8. Caching routes...\n";
    $output = [];
    exec('cd ' . escapeshellarg(dirname($artisan)) . ' && php artisan route:cache 2>&1', $output, $code);
    foreach ($output as $line) {
        echo "   $line\n";
    }

    // View cache
    echo "9. Caching views...\n";
    $output = [];
    exec('cd ' . escapeshellarg(dirname($artisan)) . ' && php artisan view:cache 2>&1', $output, $code);
    foreach ($output as $line) {
        echo "   $line\n";
    }

    // Storage link
    echo "10. Creating storage link...\n";
    $output = [];
    exec('cd ' . escapeshellarg(dirname($artisan)) . ' && php artisan storage:link 2>&1', $output, $code);
    foreach ($output as $line) {
        echo "   $line\n";
    }
}

echo "\n" . str_repeat('=', 40) . "\n";

if (empty($errors)) {
    echo "✅ Installation complete!\n\n";

    // Self-delete for security
    echo "🗑️  Deleting installer script for security... ";
    if (@unlink(__FILE__)) {
        echo "Done\n";
    } else {
        echo "⚠️  Could not self-delete. Please manually delete:\n";
        echo "   public/install.php\n";
    }

    echo "\n👉 Visit your site: " . ($appUrl ?? '') . "\n";
    echo "   Setup wizard will guide you through configuration.\n";
} else {
    echo "⚠️  Completed with errors:\n";
    foreach ($errors as $e) {
        echo "   ❌ $e\n";
    }
    echo "\n   Fix the issues and reload this page.\n";
    echo "   This installer will NOT self-delete until all errors are resolved.\n";
}

echo "</pre>\n";
