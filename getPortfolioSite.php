<?php $siteName = $_GET['siteName'];
$siteUrl = $_GET['siteUrl'];

// The path to save/load the site's HTML to/from
$siteHtmlPath = __DIR__ . "/sites/$siteName.html";

// Determine the age of the site's HTML file, if it exists.
// If it doesn't, or if it's older than one week, we need
// to re-scrape the site
$oneWeek = 604800;
$lastModTime = !file_exists($siteHtmlPath) ?
	null : filemtime($siteHtmlPath);

if ($lastModTime !== null && $lastModTime >= time() - $oneWeek) {
	exit;
}

// Get the site's home page's HTML source using a browser-like context with
// file_get_contents() and save successful responses in "/sites/$siteName.html"
$siteHtml = @file_get_contents($siteUrl, false, stream_context_create([
    'http' => [
        'method' => "GET",
        'header' =>
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140 Safari/537.36\r\n" .
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
            "Accept-Language: en-AU,en;q=0.9\r\n" .
            "Connection: keep-alive\r\n",
        'ignore_errors' => true, // so we can capture even non-200 responses
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
    ]
]));

// Ensure that the response is always a valid JSON object
header('Content-Type: application/json');

if ($siteHtml !== false) {
    $siteHtmlDir = dirname($siteHtmlPath);

	if (!is_dir($siteHtmlDir)) {
		mkdir($siteHtmlDir, 0777, true);
	}

	if (@file_put_contents($siteHtmlPath, $siteHtml) !== false) {
		// Send back a JSON response with success: true and exit
		echo json_encode([
			'success' => true
		]);
		exit;
	}
}

// For any error caused by file_get/put_contents(),
// send back a JSON response with error and header info
$response = json_encode([
	'error' => print_r(error_get_last(), true),
	'httpHeaders' => $http_response_header ?? 'None'
]);
exit;