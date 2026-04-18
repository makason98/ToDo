<?php
// TEMP: delete this file after debugging

echo "<h3>Server Debug</h3>";

// Check PHP version
echo "PHP: " . phpversion() . "<br>";

// Check .env exists
$root = dirname(__DIR__);
echo ".env exists: " . (file_exists($root . '/.env') ? 'YES' : 'NO') . "<br>";

// Check storage writable
echo "storage writable: " . (is_writable($root . '/storage') ? 'YES' : 'NO') . "<br>";
echo "storage/logs writable: " . (is_writable($root . '/storage/logs') ? 'YES' : 'NO') . "<br>";
echo "storage/framework writable: " . (is_writable($root . '/storage/framework') ? 'YES' : 'NO') . "<br>";
echo "bootstrap/cache writable: " . (is_writable($root . '/bootstrap/cache') ? 'YES' : 'NO') . "<br>";

// Check vendor exists
echo "vendor/autoload.php exists: " . (file_exists($root . '/vendor/autoload.php') ? 'YES' : 'NO') . "<br>";

// Check key directories
echo "storage/framework/sessions exists: " . (is_dir($root . '/storage/framework/sessions') ? 'YES' : 'NO') . "<br>";
echo "storage/framework/views exists: " . (is_dir($root . '/storage/framework/views') ? 'YES' : 'NO') . "<br>";
echo "storage/framework/cache exists: " . (is_dir($root . '/storage/framework/cache') ? 'YES' : 'NO') . "<br>";
