window.onload = () => {
	[
		'carona',
		'enviroline',
		'magnattack',
		'pneutech',
		'tkvgroup',
		'veridia'
	].forEach(siteName => {
		const loader = $(`#home-carousel-${siteName}-loader`);
		const carouselItem = loader.parent();
		const iframe = document.createElement('iframe');

		iframe.id = loader[0].id.replace('-loader', '-frame');
		iframe.src = `/sites/${siteName}.html`;

		iframe.onload = () => {
			$(iframe.contentDocument).find('a').on('click', e => {
				window.open(e.currentTarget.href, '_blank').focus();
				return false;
			});

			loader.remove();

			if (siteName === 'veridia') {
				// @ts-ignore
				new bootstrap.Carousel('#home-carousel').cycle();
			}
		};

		carouselItem.append(iframe);
	});
};