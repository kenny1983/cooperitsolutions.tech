<?php
	$pageTitle = "My Portfolio";
	$currPage = "portfolio";
	$titles = '<h2>Check out these cool <span class="emphasis">apps and sites</span></h2>
		<h3>Every single one is <span class="emphasis">mine, &nbsp;all mine!</span> Stay tuned for more</h3>';

	require __DIR__ . '/../templates/head.php';
	echo '<body id="portfolio-page">';
	require __DIR__ . '/../templates/header.php';
?>
	<div class="wave top"></div>
	<div class="row">
		<div class="col-md-12 content">

		</div>
	</div>
	<?php require __DIR__ . '/../templates/footer.php'; ?>