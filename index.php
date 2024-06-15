<?php

// URL of the JSON data
$url = "https://tplayapi.code-crafters.app/321codecrafters/fetcher.json";

// Fetch the JSON data
$jsonData = file_get_contents($url);

// Decode the JSON data
$data = json_decode($jsonData, true);

// Initialize a variable to store the hex value
$hexValue = null;

// Loop through the data to find the entry with id "35"
foreach ($data as $entry) {
    if ($entry['id'] == '35') {
        // Loop through the clearkeys to find the media_segment source
        foreach ($entry['clearkeys'] as $clearkey) {
            if ($clearkey['source'] == 'media_segment') {
                // Store the hex value
                $hexValue = $clearkey['hex'];
                break 2; // Exit both loops once the value is found
            }
        }
    }
}

// Output the hex value
if ($hexValue) {
    echo "Hex value from media segment: " . $hexValue;
} else {
    echo "Hex value not found.";
}

?>
