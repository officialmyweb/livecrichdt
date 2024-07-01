<?php
// Function to fetch JSON data from URL
function fetch_json_data($url) {
    $json_data = file_get_contents($url);
    if ($json_data === false) {
        die("Error fetching JSON data from URL");
    }
    return json_decode($json_data, true);
}

// Function to transform the manifest URL
function transform_manifest_url($url) {
    $parsed_url = parse_url($url);
    $new_host = str_replace(['bpweb', '.akamaized.net'], ['bpprod', 'catchup.akamaized.net'], $parsed_url['host']);
    return $parsed_url['scheme'] . '://' . $new_host . $parsed_url['path'];
}

// Function to add time parameters to the manifest URL
function add_time_parameters_to_manifest_url($url, $id) {
    if (strpos($url, '.m3u8') !== false) {
        return $url; // If the URL contains .m3u8, do not add time parameters
    }

    $currentTimestamp = time();
    if ($id === '24' || $id === '78') {
        $beginTimestamp = $currentTimestamp - (14 * 60); // 14 minutes before current time
    } else {
        $beginTimestamp = $currentTimestamp - (8 * 60 * 60); // 8 hours before current time
    }
    $endTimestamp = $currentTimestamp + (24 * 60 * 60); // 24 hours from current time

    $begin_date = (new DateTime())->setTimestamp($beginTimestamp)->format('Ymd\THis');
    $end_date = (new DateTime())->setTimestamp($endTimestamp)->format('Ymd\THis');

    return $url . '?begin=' . $begin_date . '&end=' . $end_date;
}

// URL of the JSON file
$json_url = 'https://tplayapi.code-crafters.app/321codecrafters/fetcher.json'; // Replace with your actual URL

// Fetch JSON data
$data = fetch_json_data($json_url);

// Get the ID from the URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Initialize variables to store the found data
$found_data = null;
$channel_name = null;
$manifest_url = null;
$channel_logo_url = null;

// Search for the data corresponding to the provided ID
foreach ($data['data']['channels'] as $channel) {
    if ($channel['id'] == $id) {
        $channel_name = $channel['name']; // Get the channel name
        $manifest_url = $channel['manifest_url']; // Get the manifest URL
        $channel_logo_url = isset($channel['logo_url']) ? $channel['logo_url'] : null; // Get the channel logo URL if exists
        foreach ($channel['clearkeys'] as $clearkey) {
            if ($clearkey['source'] == 'media_segment') {
                $found_data = $clearkey;
                break;
            }
        }
        break;
    }
}

// Output the found data in JSON format
if ($found_data !== null) {
    $base64_keys = $found_data['base64']['keys'][0];
    $keyId = $found_data['base64']['keys'][0]['kid'];
    $key = $found_data['base64']['keys'][0]['k'];
    $hex_parts = explode(":", $found_data['hex']);
    $transformed_manifest_url = transform_manifest_url($manifest_url);
    $transformed_manifest_url_with_time = add_time_parameters_to_manifest_url($transformed_manifest_url, $id);

    header('Content-Type: application/json');
    echo json_encode(array(
        "channel_id" => $id,
        "channel_name" => $channel_name,
        "channel_logo" => $channel_logo_url,
        "channel_url" => $transformed_manifest_url_with_time,
        "base64" => $found_data['base64'],
        "keyid" => $hex_parts[0],
        "key" => $hex_parts[1]
    ), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
} else {
    header('Content-Type: application/json');
    echo json_encode(array(
        "error" => "Data not found for ID: " . $id
    ), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}
?>
