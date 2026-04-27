<?php
$imagePath = 'app/public/ktp/5pRpWjU5WBzygwcQ5gtsPCY0zuzMHAAsrbIDhRmO.png';
$fullPath = __DIR__ . '/' . $imagePath;

echo "Full Path: " . $fullPath . "\n";
echo "File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
echo "Is Readable: " . (is_readable($fullPath) ? 'YES' : 'NO') . "\n";
echo "File Size: " . (file_exists($fullPath) ? filesize($fullPath) . " bytes" : "N/A") . "\n";

// Test URL
$publicPath = 'public/storage/ktp/5pRpWjU5WBzygwcQ5gtsPCY0zuzMHAAsrbIDhRmO.png';
$fullPublicPath = dirname(__DIR__) . '/' . $publicPath;
echo "\nPublic Path: " . $publicPath . "\n";
echo "Full Public Path: " . $fullPublicPath . "\n";
echo "Public File Exists: " . (file_exists($fullPublicPath) ? 'YES' : 'NO') . "\n";
