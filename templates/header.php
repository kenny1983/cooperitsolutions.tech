<?php $pages = [
	'home' => 'home',
	'about' => 'user',
	'resume' => 'file-text',
	'portfolio' => 'code',
	'contact' => 'envelope-o'
]; ?>

<header>
    <nav class="bg-body-tertiary fixed navbar navbar-expand-lg">
		<div class="container-fluid">
			<div class="navbar-brand">
				<h1>
					<span>Kent</span>
					<span class="emphasis">Cooper</span>
				</h1>
				<h3>
					<span class="emphasis">Full Stack</span>
					<span>Web Developer</span>
				</h3>
			</div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<?php foreach ($pages as $page => $icon) { ?>
						<li class="nav-item">
							<a <?= $page !== $currPage ? '' : 'class="emphasis" ' ?>href="/<?= $page ?>.html">
								<i class="fa fa-<?= $icon ?>"></i>
								<span><?= ucwords($page) ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>
    <div class="titles">
        <?= $titles ?? '' ?>
    </div>
</header>