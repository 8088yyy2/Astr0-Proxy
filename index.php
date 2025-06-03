<?php
// Allow cross-origin access (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/dash+xml");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Get the 'get' parameter
$get = isset($_GET['get']) ? $_GET['get'] : '';
if (!$get) {
    http_response_code(400);
    die("No stream identifier provided");
}

// Construct the MPD URL
$mpdUrl = 'https://l02.dp.sooka.my/' . $get;

// Set headers for the request
$headers = [
    "User-Agent: Mozilla/5.0 (Linux; Android 11; Xperia 5 Build/WV) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.7.1675.48 Safari/537.36",
    "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE3NDg5MTUxMjksImlzcyI6IlZSIiwiZXhwIjoxNzQ4OTg5ODAwLCJ3bXZlciI6Mywid21pZGZtdCI6ImFzY2lpIiwid21pZHR5cCI6MSwid21rZXl2ZXIiOjMsIndtdG1pZHZlciI6NCwid21pZGxlbiI6NTEyLCJ3bW9waWQiOjMyLCJ3bWlkIjoiMzQzYjNmYmEtYjAxNy00ZTFhLWJlMjQtNmM2MzIxMDI0OTU0IiwiZmlsdGVyIjoiKHR5cGU9PVwidmlkZW9cIiYmRGlzcGxheUhlaWdodDw9NzIwKXx8KHR5cGU9PVwiYXVkaW9cIiYmZm91ckNDIT1cImFjLTNcIil8fCh0eXBlIT1cInZpZGVvXCImJnR5cGUhPVwiYXVkaW9cIikifQ.4XSStLcrR88wH-l-o8W0lik0B4fnvmnPxNdYUICOHYo"
];

// Set up the stream context
$mpdContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $headers),
        'follow_location' => 1,
        'timeout' => 5
    ]
]);

// Fetch the MPD content
$res = @file_get_contents($mpdUrl, false, $mpdContext);

if ($res === false) {
    http_response_code(502);
    die("Failed to fetch MPD file.");
}

echo $res;
?>
