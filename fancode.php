<?php

// Function to fetch the API key from channel_url
function fetchApiKey($channelUrl) {
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $channelUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP Fetcher',
        'X-Origin: @Livecrichdofficial by @Capitanmatrix'
    ]);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Check if JSON decoding was successful
    if ($data === null) {
        echo 'Error decoding JSON';
        exit;
    }

    // Extract and return the API key (channel_url in this case)
    return isset($data['channel_url']) ? $data['channel_url'] : null;
}

// Fetch the API key using the channel_url (adjust as needed)
$channelUrl = 'https://livecrichdofficial.000webhostapp.com/livecrichd.php?id=24'; // Example URL
$apiKey = fetchApiKey($channelUrl);

// Define your API key based on the fetched channel_url
define('API_KEY', $apiKey);

// Function to validate API key
function validateApiKey($key) {
    return $key === API_KEY;
}

// Check if API key is provided and valid
if (!isset($_GET['api_key']) || !validateApiKey($_GET['api_key'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: Invalid API key']);
    exit;
}

// URL to fetch JSON data
$url = 'https://raw.githubusercontent.com/byte-capsule/FanCode-Hls-Fetcher/main/Fancode_hls_m3u8.Json';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Fetcher',
    'X-Origin: @Livecrichdofficial by @Capitanmatrix'
]);

// Execute cURL session
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check if JSON decoding was successful
if ($data === null) {
    echo 'Error decoding JSON';
    exit;
}

// Prepare an array to hold formatted data
$formattedData = [];

// Iterate through each channel
foreach ($data['channels'] as $channel) {
    $formattedData[] = [
        'event_category' => $channel['event_category'],
        'event_name' => $channel['event_name'],
        'match_id' => $channel['match_id'],
        'match_name' => $channel['match_name'],
        'team_1' => $channel['team_1'],
        'team_1_flag' => $channel['team_1_flag'],
        'team_2' => $channel['team_2'],
        'team_2_flag' => $channel['team_2_flag'],
        'stream_link' => $channel['stream_link']
    ];
}

// Additional information
$additionalInfo = [
    'channel_name' => 'LiveCricHd Fancode Php',
    'channel_link' => 'https://telegram.me/livecrichdofficial',
    'thanks_to' => 'Thanks to byte capsule'
];

// Combine formatted data with additional information
$output = [
    'channel_info' => $additionalInfo,
    'matches' => $formattedData
];

// Output the combined data as JSON
header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT);
?>
