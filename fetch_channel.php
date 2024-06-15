<?php

// URL of the JSON API
$json_url = 'https://captaintvwc.x10.mx/tataplay/tatajson.php';

// Fetch JSON data
$json_data = file_get_contents($json_url);

// Decode JSON data into associative array
$data = json_decode($json_data, true);

// Check if id parameter is set in the URL
if (isset($_GET['id'])) {
    $requested_id = $_GET['id'];
    
    // Search for the channel with the requested ID
    $channel_found = false;
    foreach ($data['data']['channels'] as $channel) {
        if ($channel['id'] == $requested_id) {
            // Display channel information
            $channel_found = true;
            echo '<h1>Channel Details</h1>';
            echo '<p>ID: ' . $channel['id'] . '</p>';
            echo '<p>Name: ' . $channel['name'] . '</p>';
            echo '<p>Genres: ' . implode(', ', $channel['genres']) . '</p>';
            echo '<p>Languages: ' . implode(', ', $channel['languages']) . '</p>';
            echo '<p><img src="' . $channel['logo_url'] . '" alt="Channel Logo"></p>';
            // Display more details as needed
            break; // Exit loop once channel is found
        }
    }
    
    // If no channel is found with the requested ID
    if (!$channel_found) {
        echo '<p>No channel found with ID: ' . $requested_id . '</p>';
    }
} else {
    // Handle case where no id parameter is provided
    echo '<p>No ID parameter provided.</p>';
}

?>
