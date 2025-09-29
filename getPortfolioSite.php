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
// $options = [
// 	'http' => [
// 		'header' => 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
// 		'header' => 'accept-language: en-AU,en;q=0.9',
// 		'header' => 'cache-control: no-cache',
// 		'header' => 'pys_session_limit=true; pys_start_session=true; pys_first_visit=true; pysTrafficSource=direct; pys_landing_page=https://remaxdoors.com/carona-doors-is-now-remax-doors/; pys_utm_source=carona; pys_utm_medium=redirect; last_pysTrafficSource=direct; last_pys_landing_page=https://remaxdoors.com/carona-doors-is-now-remax-doors/; last_pys_utm_source=carona; last_pys_utm_medium=redirect; cf_clearance=UexDrE4LLGTTQb87yH4XbyXIOZ7hxqDWQWtNCikuyIk-1759037432-1.2.1.1-b.YB3h8Au0rcr_SdOEyXobfuc5Rjtg97t2uFpFDOGd3NuQJ5EVgP6iApE.06pMZwHutyp6mgSVj83.xaVrQq8A2b07NXLGEbG7GF_V178xPjSbPIgeELE_FBVbgPkck6hvRml6RNhIyXGT4BO3Wxsbn8MWIZ0TEm7Qq5LaxDlewwcKy2w9o91CrXLivmY6pGC.48_pUoedgOnpSwysu48iF77Xszv5OXfCRJGsGodP4; remax_region_code=NSW',
// 		'header' => 'pragma: no-cache',
// 		'header' => 'priority: u=0, i',
// 		'header' => 'sec-ch-ua: "Chromium";v="140", "Not=A?Brand";v="24", "Brave";v="140"',
// 		'header' => 'sec-ch-ua-mobile: ?0',
// 		'header' => 'sec-ch-ua-platform: "macOS"',
// 		'header' => 'sec-fetch-dest: document',
// 		'header' => 'sec-fetch-mode: navigate',
// 		'header' => 'sec-fetch-site: none',
// 		'header' => 'sec-fetch-user: ?1',
// 		'header' => 'sec-gpc: 1',
// 		'header' => 'upgrade-insecure-requests: 1',
// 		'header' => 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36'
// 	]
// ];
// $context = stream_context_create($options);
$siteHtml = file_get_contents($siteUrl); //, false, $context);
$siteHtmlDir = dirname($siteHtmlPath);

if (!is_dir($siteHtmlDir)) {
	mkdir($siteHtmlDir, 0777, true);
}

file_put_contents($siteHtmlPath, $siteHtml);