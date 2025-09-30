<?php $pageTitle = "About Me";
$currPage = "about";
$titles = '<h3>OK, so I know what you\'re <span class="emphasis">probably</span> thinking:</h3>
	<h2>Who <span class="emphasis">the heck</span> is Kent Cooper?</h2>';

require __DIR__ . '/../templates/head.php';
echo '<body id="about-page">';
require __DIR__ . '/../templates/header.php'; ?>

	<div class="wave top"></div>
	<div class="pt-3 row">
		<div class="col-md-8 content">
			<h4>A bit <span class="emphasis">about me</span></h4>
			<p>I'm a <?= getAge() ?> year old freelance web developer from Sydney, Australia. I mainly work with <span class="emphasis">C#</span> and <span class="emphasis">ASP.NET Core,</span> but am also adept at using <span class="emphasis">Python, PHP</span> and a host of other web and desktop development technologies.</p>
			<p>My passion for software development began when I was just 15 years old, in a tiny school classroom where the main focus was writing simple console programs in Q-BASIC. Since then, I've self-taught myself <span class="emphasis">Visual Basic 6, VB.NET, HTML, CSS, JavaScript, jQuery and Python.</span> I've also been professionally schooled in <span class="emphasis">C, C++, C#, Java, PHP</span> and most dialects of <span class="emphasis">SQL.</span></p>
			<p>Almost <?= getAge() - 14 ?> years of school, TAFE (akin to community college for those in Europe, Asia and the Americas) and hobby experience culminated in September 2015 when I completed my <span class="emphasis">BCompSc</span> (majoring in <span class="emphasis">Systems Programming</span>) at the <span class="emphasis">University of Western	Sydney</span> (now known as <span class="emphasis">Western Sydney University</span>). This is by far my proudest achievement in life, having scored <span class="emphasis">above 80%</span> in every practical assessment.</p>
			<p>I also split my time between working for the <span class="emphasis">Royal Australian Navy</span> and <span class="emphasis"><a href="http://outputlogic.com.au/" target="_blank">Output Logic</a><sup><i class="fa fa-external-link"></i></sup></span> throughout 2015 and 2016, as well as <span class="emphasis"><a href="https://onewest.com.au/" target="_blank">OneWest Design and Media</a><sup><i class="fa fa-external-link"></i></sup></span> throughout 2017 and 2018. I then returned to the Navy in March 2022 and continued working on the same project that had been put on hold in 2016, until it was completely cancelled in August 2024.</p>
			<p>These positions have broadened my skill set <span class="emphasis">even further</span>, while also allowing me to obtain <span class="emphasis">professional experience</span> in client liaison and developing <span class="emphasis">modern, responsive web apps/sites</span> that always leave my clients <span class="emphasis">highly satisfied</span>.</p>
			<p>I chose this career not for the money, but simply for the joy of creating something beautiful that actually works, and can be used by people of all ages and levels of tech-savviness. So please, take a look at my <a class="emphasis" href="/portfolio.html">portfolio</a>, peruse my <a class="emphasis" href="/resume.html">résumé</a> and if that all piques your interest, <a class="emphasis" href="/contact.html">request a quote</a> from me for your next software development project. I promise you won't regret it!</p>
		</div>
		<div class="col-md-4 d-flex flex-column justify-content-between sidebar">
			<div>
				<div class="polaroid rotate-5">
					<img src="../images/grey.png">
				</div>
				<div class="polaroid rotate-355">
					<img src="../images/grey.png">
				</div>
				<div class="polaroid">
					<p>First job interview (Nov 2016)</p>
					<img alt="Looking fly in a nice suit" src="../images/me-in-suit.png">
				</div>
			</div>
			<div>
				<div class="polaroid rotate-5">
					<img src="../images/grey.png">
				</div>
				<div class="polaroid rotate-355">
					<img src="../images/grey.png">
				</div>
				<div class="polaroid">
					<p>Graduation Day (Sep 2015)</p>
					<img alt="Receiving my degree on graduation day" src="../images/graduation-square.jpg">
				</div>
			</div>
		</div>
	</div>
	<?php require __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>