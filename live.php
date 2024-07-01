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

// Initialize an array to store all channels
$all_channels = [];

// Process each channel
foreach ($data['data']['channels'] as $channel) {
    $id = $channel['id'];
    $channel_name = $channel['name'];
    $channel_logo_url = isset($channel['logo_url']) ? $channel['logo_url'] : null;
    $manifest_url = $channel['manifest_url'];
    $license_url = isset($channel['license_url']) ? $channel['license_url'] : null; // Added license_url

    // Additional attributes
    $genres = isset($channel['genres']) ? $channel['genres'] : [];
    $languages = isset($channel['languages']) ? $channel['languages'] : [];
    $manifest_headers = isset($channel['manifest_headers']) ? $channel['manifest_headers'] : [];
    $manifest_type = isset($channel['manifest_type']) ? $channel['manifest_type'] : null;
    $is_drm_protected = isset($channel['is_drm_protected']) ? $channel['is_drm_protected'] : false;
    $is_kid_in_manifest = isset($channel['is_kid_in_manifest']) ? $channel['is_kid_in_manifest'] : false;
    $is_hmac_required = isset($channel['is_hmac_required']) ? $channel['is_hmac_required'] : false;
    $is_clearkeys_extracted = isset($channel['is_clearkeys_extracted']) ? $channel['is_clearkeys_extracted'] : false;
    $clearkeys = isset($channel['clearkeys']) ? $channel['clearkeys'] : [];

    // Transform manifest URL and add time parameters
    $transformed_manifest_url = transform_manifest_url($manifest_url);
    $transformed_manifest_url_with_time = add_time_parameters_to_manifest_url($transformed_manifest_url, $id);

    // Prepare channel data
    $channel_data = [
        "channel_id" => $id,
        "channel_name" => $channel_name,
        "channel_logo" => $channel_logo_url,
        "channel_url" => $transformed_manifest_url_with_time,
        "license_url" => $license_url,
        "genres" => $genres,
        "languages" => $languages,
        "manifest_headers" => $manifest_headers,
        "manifest_type" => $manifest_type,
        "is_drm_protected" => $is_drm_protected,
        "is_kid_in_manifest" => $is_kid_in_manifest,
        "is_hmac_required" => $is_hmac_required,
        "is_clearkeys_extracted" => $is_clearkeys_extracted,
        "clearkeys" => $clearkeys
    ];

    // Add channel data to the array
    $all_channels[] = $channel_data;
}

// Output all channels data in JSON format
header('Content-Type: application/json');
echo json_encode($all_channels, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
