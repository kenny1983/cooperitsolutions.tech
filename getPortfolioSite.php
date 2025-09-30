<?php $siteName = $_GET['siteName'];
$siteUrl = $_GET['siteUrl'];

// Ensure that $siteUrl is a valid URL
if ($siteUrl && @filter_var($siteUrl, FILTER_VALIDATE_URL)) {
    // The path to save/load the site's HTML to/from
	$siteHtmlPath = __DIR__ . "/sites/$siteName.html";

	// Determine the age of the site's HTML file, if it exists.
	// If it doesn't, or if it's older than one week, we need
	// to re-scrape the site
	$oneWeek = 604800;
	$lastModTime = !file_exists($siteHtmlPath) ?
		null : filemtime($siteHtmlPath);

	// Ensure that the response is always a valid JSON object
	header('Content-Type: application/json');

	if ($lastModTime !== null && $lastModTime >= time() - $oneWeek) {
		// Send back a JSON response with success: true and exit
		echo json_encode([
			'success' => true
		]);
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

 	if ($siteHtml !== false) {
		// Proxy any internal resources hosted on the site through proxy.php
		preg_match_all('/<\w+\s+[^>]*(src|href)="(.*?)"/i',
			$siteHtml, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$url = $match[2];

			// Ignore absolute URLs pointing to external sites
			$urlIsAbsolute = strpos($url, 'http') === 0;

			if ($urlIsAbsolute && strpos($url, parse_url(
				$siteUrl, PHP_URL_HOST)) === false) {
				continue;
			}

			// Convert relative URLs to absolute
			if (!$urlIsAbsolute) {
				$url = rtrim($siteUrl, '/') . '/' . ltrim($url, '/');
			}

			// Determine file extension and local storage path
			$ext = pathinfo(parse_url($url,
				PHP_URL_PATH), PATHINFO_EXTENSION);
			$ext = $ext ?: 'misc';

			// Download the resource's content
			$resourceContent = file_get_contents($url);

			// If it's a CSS file, process URLs inside it
			if ($ext === 'css') {
				$resourceContent = preg_replace_callback('/url\(("|\')(.*)\1\)/',
					function ($matches) use ($siteUrl, $siteName, $resourcePath) {
						$path = $matches[2];

						// Return the original url() function call if the
						// path is an absolute URL or an SVG data URL
						if (strpos($path, 'http') === 0 || strpos($path, 'data:image/svg')) {
							return $matches[0];
						}

						$resourceUrl = rtrim($siteUrl, '/') . '/' . ltrim($path, '/');

						$subExt = pathinfo(parse_url($resourceUrl,
							PHP_URL_PATH), PATHINFO_EXTENSION);
						$subExt = $subExt ?: 'misc';

						// $subResourcePath = __DIR__ . "/sites/$siteName/$subExt";
						// $subResourceName = basename(parse_url($resourceUrl, PHP_URL_PATH));
						// $localSubResourcePath = "$subResourcePath/$subResourceName";

						// if (!is_dir($subResourcePath)) {
						// 	mkdir($subResourcePath, 0777, true);
						// }

						// $subResourceContent = file_get_contents($resourceUrl);
						// file_put_contents($localSubResourcePath, $subResourceContent);

						return "url('/sites/$siteName/$subExt/$subResourceName')";
					}, $resourceContent);
			}

			// Replace the original resource reference with the new proxied URL
			$siteHtml = str_replace($url, "/sites/$siteName/$ext/$resourceName", $siteHtml);
		}

		// Finally, save the resulting HTML in "/sites/$siteName.html"
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
}

// For any PHP error thrown anywhere in this file,
// send back a JSON response with error and header info
$response = json_encode([
	'error' => print_r(error_get_last(), true),
	'httpHeaders' => $http_response_header ?? 'None'
]);