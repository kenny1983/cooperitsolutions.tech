<?php $url = $_GET['url'] ?? '';

// Ensure that $url is a valid URL
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit('Invalid URL');
}

// Remove the URL's query string (if any)
if (strpos($url, '?') !== false) {
    $url = preg_replace('/\?.*/', '', $url);
}

// Add the necessary headers to the response
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header("Connection: keep-alive");

$fileSize = get_headers($url, 1)['Content-Length'];

if (isset($fileSize) && is_numeric($fileSize)) {
    header("Content-Length: $fileSize");
}

// Determine the MIME type of the file and add the
// corresponding 'Content-Type' header to the response
$mimeType = 'text/html';
$fileExt = pathinfo($url, PATHINFO_EXTENSION);

switch ($fileExt) {
	case 'css':
		header('Content-Type: text/css');

		// Convert relative URLs in CSS files to absolute URLs via this proxy script
		$urlDomainPos = strpos($url, '/', 8); // Find the domain root

		if ($urlDomainPos !== false) {
			$urlDomain = substr($url, 0, $urlDomainPos); // Extract the base domain

			$parsedCss = preg_replace_callback(
				'/url\((["\']?)(\/?[^)]+?)\1\)/',
				function ($matches) use ($urlDomain, $url) {
					$path = $matches[2];

					// If the path starts with '/', it's absolute
					if (strpos($path, '/') === 0) {
						return "url('/proxy.php?url=$urlDomain$path')";
					}

					// Otherwise, it's a relative path, so resolve it against the CSS file’s base
					return "url('/proxy.php?url=" . dirname($url) . "/$path')";
				},
				file_get_contents($url)
			);

			echo $parsedCss;
			exit;
		}

		break;
	case 'js':
		$mimeType = 'text/javascript';
		break;
	case 'ttf':
	case 'woff':
	case 'woff2':
		$mimeType = "font/$fileExt";
		break;
	case 'jpg':
	case 'jpeg':
		$mimeType = 'image/jpeg';
		break;
	case 'png':
		$mimeType = 'image/png';
		break;
	case 'svg':
		$mimeType = 'image/svg+xml';
		break;
}

header("Content-Type: $mimeType");

// Stream non-CSS files instead of loading them all at once
$fp = fopen($url, 'rb');

if ($fp) {
	while (!feof($fp)) {
		if (ob_get_length()) {
			ob_end_clean();
		}

		echo fread($fp, 1024); // Send 1KB chunks
		flush();
		sleep(5);
	}

	fclose($fp);
}

file_put_contents($logFile, "Completed: $url\n", FILE_APPEND);