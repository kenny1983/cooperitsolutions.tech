window.onload = () => {
	[
		{ siteUrl: 'https://www.carona.com.au/', siteName: 'carona' },
		{ siteUrl: 'https://enviroline.net.au', siteName: 'enviroline' },
		{ siteUrl: 'https://www.magnattackglobal.com', siteName: 'magnattack' },
		{ siteUrl: 'https://pneutech.com.au', siteName: 'pneutech' },
		{ siteUrl: 'https://www.tkv.com.au/', siteName: 'tkvgroup' },
		{ siteUrl: 'https://veridia.com.au/', siteName: 'veridia' }
	].forEach(site => {
		$.get(site.siteUrl, () => {
			const loader = $(`#home-carousel-${site.siteName}-loader`);
			const carouselItem = loader.parent();
			const iframe = document.createElement('iframe');

			iframe.id = loader[0].id.replace('-loader', '-frame');
			iframe.src = site.siteName === 'carona' ? site.
				siteUrl : `/sites/${site.siteName}.html`;

			iframe.onload = () => {
				$(iframe.contentDocument).find('a').on('click', e => {
					window.open(e.currentTarget.href, '_blank').focus();
					return false;
				});

				loader.remove();

				if (site.siteName === 'veridia') {
					// @ts-ignore
					new bootstrap.Carousel('#home-carousel').cycle();
				}
			};

			carouselItem.append(iframe);
		});
	});
};