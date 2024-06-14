<?php
// URL to fetch data from
$url = "https://captaintvwc.x10.mx/tataplay/get.php?id=35";

// Fetch data from the URL
$data = file_get_contents($url);

if ($data === false) {
    error_log('[' . date('Y-m-d H:i:s') . '] Error fetching data' . PHP_EOL, 3, 'update.log');
    die('Error fetching data');
}

// Path to the PHP file you want to update
$filePath = __DIR__ . '/35.php';

// Update the PHP file with the fetched data
if (file_put_contents($filePath, $data) === false) {
    error_log('[' . date('Y-m-d H:i:s') . '] Error writing to file' . PHP_EOL, 3, 'update.log');
    die('Error writing to file');
}

// Change directory to the repository
chdir(__DIR__);

// Git add, commit, and push
exec('git add ' . $filePath);
exec('git commit -m "Automated update: ' . date('Y-m-d H:i:s') . '"');
exec('git push origin main');

error_log('[' . date('Y-m-d H:i:s') . '] Data updated and pushed to GitHub successfully' . PHP_EOL, 3, 'update.log');
echo 'Data updated and pushed to GitHub successfully';
?>
