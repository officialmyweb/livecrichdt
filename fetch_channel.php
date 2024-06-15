<?php
// Define the URL
$url = 'https://captaintvwc.x10.mx/tataplay/get.php?id=35';

// Fetch the JSON data
$json_data = file_get_contents($url);

// Check if data fetching was successful
if ($json_data === false) {
    die('Error fetching the data.');
}

// Decode the JSON data into a PHP associative array
$data = json_decode($json_data, true);

// Check if JSON decoding was successful
if ($data === null) {
    die('Error decoding JSON data.');
}

// Display the channel data
echo '<h1>Channel Details</h1>';
echo '<p>ID: ' . htmlspecialchars($data['channel_id']) . '</p>';
echo '<p>Name: ' . htmlspecialchars($data['channel_name']) . '</p>';
echo '<p>Manifest URL: <a href="' . htmlspecialchars($data['channel__url']) . '">' . htmlspecialchars($data['channel__url']) . '</a></p>';
echo '<h2>Base64 Keys</h2>';

foreach ($data['base64']['keys'] as $key) {
    echo '<p>Key Type: ' . htmlspecialchars($key['kty']) . '</p>';
    echo '<p>Key: ' . htmlspecialchars($key['k']) . '</p>';
    echo '<p>Key ID: ' . htmlspecialchars($key['kid']) . '</p>';
}

echo '<p>Key ID: ' . htmlspecialchars($data['keyid']) . '</p>';
echo '<p>Key: ' . htmlspecialchars($data['key']) . '</p>';
?>
