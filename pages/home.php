<?php $pageTitle = 'Home';
$currPage = "home";

$sites = [
	'carona' => 'https://www.carona.com.au/',
	'enviroline' => '/sites/enviroline.html',
	'magnattack' => '/sites/magnattack.html',
	'pneutech' => '/sites/pneutech.html',
	'tkvgroup' => '/sites/tkvgroup.html',
	'veridia' => '/sites/veridia.html',
];

require "../templates/head.php";
echo '<body id="home-page">';
require "../templates/header.php"; ?>

	<div id="home-carousel" class="carousel slide">
		<div class="carousel-indicators">
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Carona Group"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="1" aria-label="Enviroline Group"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="2" aria-label="Magnattack Global"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="3" aria-label="Newcastle Knights"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="4" aria-label="Pneutech"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="5" aria-label="Tip Top Equipment"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="6" aria-label="TKV Group"></button>
			<button type="button" data-bs-target="#home-carousel" data-bs-slide-to="7" aria-label="Veridia"></button>
		</div>
		<div class="carousel-inner">
		<?php foreach ($sites as $siteName => $siteUrl) { ?>
			<div class="carousel-item<?= $siteName !== 'carona' ? '' : ' active' ?>">
				<div id="<?= "home-carousel-$siteName-loader" ?>">
					<i class="fa fa-spinner fa-spin"></i>
					<span class="ms-3">Loading...</span>
				</div>
			</div>
		<?php } ?>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#home-carousel" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#home-carousel" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
	<script type="text/javascript" src="/js/getPortfolioSites.js"></script>
</body>
</html>