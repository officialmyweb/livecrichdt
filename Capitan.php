<?php

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
        'Event Category' => $channel['event_catagory'],
        'Event Name' => $channel['event_name'],
        'Match ID' => $channel['match_id'],
        'Match Name' => $channel['match_name'],
        'Team 1 Name' => $channel['team_1'],
        'Team 1 Flag (as an image)' => $channel['team_1_flag'],
        'Team 2 Name' => $channel['team_2'],
        'Team 2 Flag (as an image)' => $channel['team_2_flag'],
        'Stream Link' => $channel['stream_link']
    ];
}

// Output the formatted data as JSON
header('Content-Type: application/json');
echo json_encode($formattedData, JSON_PRETTY_PRINT);
?>
