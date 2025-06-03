<?php
// Check if the request is coming from your domain
$allowedDomain = 'https://8088y.site';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (
    (strpos($referer, $allowedDomain) !== 0) &&
    (strpos($origin, $allowedDomain) !== 0)
) {
    header('HTTP/1.1 403 Forbidden');
    die("Access denied");
}

// Allow cross-origin access
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/dash+xml");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Get stream ID
$get = isset($_GET['get']) ? $_GET['get'] : '';
if (!$get) {
    die("No stream identifier provided");
}

$mpdUrl = 'https://l02.dp.sooka.my/' . $get;

// Set headers for the request
$mpdheads = [
  'http' => [
      'header' => "User-Agent: Mozilla/5.0 (Linux; Android 11; Xperia 5 Build/استخدم هذا الرمز للوصول إلى القنوات. احتفظ به آمنًا ولا تشاركه مع أي شخص; wv) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.7.1675.48 Safari/537.36",
      'header' => "authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE3NDg5MTUxMjksImlzcyI6IlZSIiwiZXhwIjoxNzQ4OTg5ODAwLCJ3bXZlciI6Mywid21pZGZtdCI6ImFzY2lpIiwid21pZHR5cCI6MSwid21rZXl2ZXIiOjMsIndtdG1pZHZlciI6NCwid21pZGxlbiI6NTEyLCJ3bW9waWQiOjMyLCJ3bWlkIjoiMzQzYjNmYmEtYjAxNy00ZTFhLWJlMjQtNmM2MzIxMDI0OTU0IiwiZmlsdGVyIjoiKHR5cGU9PVwidmlkZW9cIiYmRGlzcGxheUhlaWdodDw9NzIwKXx8KHR5cGU9PVwiYXVkaW9cIiYmZm91ckNDIT1cImFjLTNcIil8fCh0eXBlIT1cInZpZGVvXCImJnR5cGUhPVwiYXVkaW9cIikifQ.4XSStLcrR88wH-l-o8W0lik0B4fnvmnPxNdYUICOHYo"
      'follow_location' => 1,
      'timeout' => 5
  ]
];

$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);

if ($res === false) {
    die("Failed to fetch MPD file.");
}

echo $res;
?>
