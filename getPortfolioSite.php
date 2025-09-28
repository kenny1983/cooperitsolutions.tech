<?php $siteName = $_GET['siteName'];
$siteUrl = $_GET['siteUrl'];

// The path to save/load the site's HTML to/from
$siteHtmlPath = __DIR__ . "/dist/sites/$siteName.html";

// Determine the age of the site's HTML file, if it exists.
// If it doesn't, or if it's older than one week, we need
// to re-scrape the site
$oneWeek = 604800;
$lastModTime = !file_exists($siteHtmlPath) ?
	null : filemtime($siteHtmlPath);

if ($lastModTime !== null && $lastModTime >= time() - $oneWeek) {
	exit;
}

// Get the site's home page's HTML source
// and save it in "/sites/$siteName.html"
$options = [
    'http' => [
        'header' => "User-Agent: Mozilla/5.0\r\n"
    ]
];
$context = stream_context_create($options);
$siteHtml = file_get_contents($siteUrl, false, $context);

$siteHtmlDir = dirname($siteHtmlPath);

if (!is_dir($siteHtmlDir)) {
	mkdir($siteHtmlDir, 0777, true);
}

file_put_contents($siteHtmlPath, $siteHtml);