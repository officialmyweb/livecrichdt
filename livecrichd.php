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

// Display the fetched data
foreach ($data['channels'] as $channel) {
    echo "Event Category: " . $channel['event_catagory'] . "<br>";
    echo "Event Name: " . $channel['event_name'] . "<br>";
    echo "Match ID: " . $channel['match_id'] . "<br>";
    echo "Match Name: " . $channel['match_name'] . "<br>";
    echo "Team 1: " . $channel['team_1'] . "<br>";
    echo "Team 1 Flag: <img src='" . $channel['team_1_flag'] . "'><br>";
    echo "Team 2: " . $channel['team_2'] . "<br>";
    echo "Team 2 Flag: <img src='" . $channel['team_2_flag'] . "'><br>";
    echo "Stream Link: <a href='" . $channel['stream_link'] . "'>" . $channel['stream_link'] . "</a><br><br>";
}
?>
