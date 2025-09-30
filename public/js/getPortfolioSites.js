// Attempt to load each portfolio site in turn,
// using Promises to properly handle errors
window.onload = () => {
	const sites = [
		{ siteUrl: 'https://www.remaxdoors.com', siteName: 'remaxdoors' },
		{ siteUrl: 'https://enviroline.net.au', siteName: 'enviroline' },
		{ siteUrl: 'https://www.magnattackglobal.com', siteName: 'magnattack' },
		{ siteUrl: 'https://pneutech.com.au', siteName: 'pneutech' },
		{ siteUrl: 'https://www.tkv.com.au', siteName: 'tkvgroup' },
		{ siteUrl: 'https://veridia.com.au', siteName: 'veridia' }
	];

	async function loadSites() {
		for (const site of sites) {
			try {
				// Call getPortfolioSite.php with each site's
				// info and dynamically handle any PHP errors
				const response = await new Promise((resolve, reject) => {
					$.get('/getPortfolioSite.php', site, response => {
						if (!response || response.error) {
							reject(response);
						} else {
							resolve(response);
						}
					}, 'json').fail((_, textStatus, errorThrown) => {
						reject({ error: textStatus || errorThrown });
					});
				});

				// On success, load the saved `/sites/${site.siteName}.html` file
				// into a new iframe parented by the original site loader div's
				// parent (the carousel item)
				const loader = $(`#home-carousel-${site.siteName}-loader`);
				const carouselItem = loader.parent();
				const iframe = document.createElement('iframe');

				iframe.id = loader[0].id.replace('-loader', '-frame');
				iframe.src = `/sites/${site.siteName}.html`;

				iframe.onload = () => {
					// Replace all links with ones that open in a new tab
					$(iframe.contentDocument).find('a').on('click', e => {
						window.open(e.currentTarget.href, '_blank').focus();
						return false;
					});

					// Remove the original site loader div
					loader.remove();

					// Start automatically cycling the carousel
					// once we've reached the last site
					if (site.siteName === 'veridia') {
						// @ts-ignore
						new bootstrap.Carousel('#home-carousel').cycle();
					}
				};

				// Append the iframe to the carousel item
				carouselItem.append(iframe);
			} catch (ex) {
				// Log all PHP errors and their HTTP
				// response headers to the console
				console.error(`Stopping at ${site.siteName}: ${ex.error}.`
					+ `\n\nHTTP headers: ${ex.httpHeaders}`);
				break; // Stop further processing
			}
		}
	}

	loadSites();
};