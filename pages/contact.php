<?php
	$pageTitle = "Contact Me or Request a Quote";
	$currPage = "contact";
	$titles = '<h2>Enter your details to <span class="emphasis">request a quote</span></h2>
		<h3>Or even just to <span class="emphasis">say hello!</span> I promise I won\'t bite (very hard)</h3>';

	require "../templates/head.php";
	echo '<body id="contact-page">';
	require "../templates/header.php";
?>
	<div class="wave top"></div>
	<div class="row">
		<div class="col-md-7 content">
			<form id="contact-form" method="post">
				<div>
					<label for="client-name">Contact name<span class="emphasis">*</span></label>
					<input id="client-name" name="clientName" class="form-control" type="text" placeholder="The name of the person in charge of your project">
					<label id="client-name-error" class="bottom-label emphasis"></label>
				</div>
				<div>
					<label for="company-name">Company name</label>
					<input id="company-name" name="companyName" class="form-control" type="text" placeholder="Your company name (if applicable)">
					<label id="company-name-error" class="bottom-label emphasis"></label>
				</div>
				<div class="form-group row">
					<label for="phone">Contact number<span class="emphasis">*</span></label>
					<br/>
					<div class="col-md-3">
						<label class="col-md-7 col-form-label">Country Code</label>
						<div class="col-md-4">
							<div class="dropdown">
								<button id="country-codes" class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">+61
									<span class="caret"></span>
								</button>
								<ul id="countries" class="dropdown-menu"></ul>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<label class="col-md-2 col-form-label" style="padding-left: 10px">Number</label>
						<div class="col-md-10">
							<input id="phone" name="phone" class="form-control" type="text" placeholder="The best number to contact the above person on (inc. area code)" style="margin-left: -11px; width: calc(100% + 11px)">
						</div>
					</div>
					<label id="phone-error" class="bottom-label emphasis"></label>
				</div>
				<div>
					<label for="email">Email address<span class="emphasis">*</span></label>
					<input id="email" name="email" class="form-control" type="email" placeholder="Must be a valid email that is regularly checked, as this will be the primary method of contact used">
					<label id="email-error" class="bottom-label emphasis"></label>
				</div>
				<div>
					<label for="description">Briefly describe your project requirements (25 - 500 characters)<span class="emphasis">*</span></label>
					<textarea id="description" name="description" class="form-control" rows="7" maxlength="500"></textarea>
					<label id="description-error" class="bottom-label emphasis"></label>
					<label class="bottom-label">(<span class="emphasis">*</span> = required field)</label>
				</div>
				<div>
					<button id="submit" class="btn btn-primary">Submit <i class="fa fa-chevron-right"></i></button>
					<i id="loading" class="fa fa-refresh fa-spin fa-2x fa-fw"></i>
				</div>
			</form>
		</div>
		<div class="col-md-5 sidebar">
			<iframe id="map" width="100%" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3317.2045940959797!2d150.91447911520729!3d-33.75537858068764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x91a849814631689a!2sCooper+I.T+Solutions!5e0!3m2!1sen!2sus!4v1483941365769" allowfullscreen></iframe>
			<div class="small">
				<br/>
				<p><b>Kent Cooper (trading as Cooper I.T Solutions)</b></p>
				<p>15 Stephen Street,<br/>
					Blacktown NSW 2148</br>
					Australia</p>
				<p>Email: <a href="mailto:getaquote@kentcooper.tech" style="color: white"><span class="emphasis">getaquote@kentcooper.tech</span></a></p>
			</div>
		</div>
	</div>
	<?php require "../templates/footer.php"; ?>
	<script type="text/javascript">
		var map = $('#map');
		map.height(map.width() - 25);

		var form = $('#contact-form');
		var clientName = $(form[0][0]);
		var countryCodes = $(form[0][2]);
		var phone = $(form[0][3]);
		var email = $(form[0][4]);
		var description = $(form[0][5]);

		var clientNameIsValid = true;
		var phoneIsValid = true;
		var emailIsValid = true;
		var descriptionIsValid = true;

		var selectedCountryCode = '+61';

		var validateAny = function(elem, errorElem, regex, elemErrorEmpty, elemErrorRegex) {
			var value = elem.val().trim();
			elem.val(value);

			if (value === "") {
				errorElem.text(elemErrorEmpty).show();
			} else if (!regex.test(value)) {
				errorElem.text(elemErrorRegex).show();
			} else {
				errorElem.text('');
				elem.css('background-color', 'white');
				return true;
			}

			elem.css('background-color', 'pink');
			return false;
		};

		var validateClientName = function() {
			return validateAny(clientName, $('#client-name-error'), /^([a-zA-Z]{3,})|([a-zA-Z]{3,}-[a-zA-Z]{3,})$/,
				'You must enter a contact name', 'Contact name must be 3 or more alphanumeric characters only (' +
				'optionally separated by a hyphen)');
		};

		var validatePhone = function() {
			return validateAny(phone, $('#phone-error'), /\d{1,14}$/, 'You must enter a contact number',
				'Contact number must be 1 - 14 digits only (no non-numeric characters');
		};

		var validateEmail = function() {
			return validateAny(email, $('#email-error'),
				/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
				'You must enter a valid email address', 'You must enter a valid email address');
		};

		var validateDescription = function() {
			return validateAny(description, $('#description-error'), /^.{25,500}$/, 'You ' +
				'must enter 25 - 500 characters', 'You must enter 25 - 500 characters');
		};

		clientName.blur(function() {
			clientNameIsValid = validateClientName();
		});

		clientName.keyup(function() {
			if (!clientNameIsValid) {
				clientNameIsValid = validateClientName();
			}
		});

		$.getJSON('../js/countries.json', function(countries) {
			countryCodes.one('click', function() {
				$(countries).each(function (idx, elem) {
					var countryLabel = elem.name + ' (' + elem.dialCode + ')';
					$('#countries').append('<li data-value="' + elem.dialCode +
						'"><a href="#">' + countryLabel + '</a></li>', '');
				});

				$('#countries li').click(function() {
					selectedCountryCode = $(this).data('value');
					countryCodes.html(selectedCountryCode +
						' <span class="caret"></span>');
				});
			});
		});

		phone.blur(function() {
			phoneIsValid = validatePhone();
		});

		phone.keyup(function() {
			if (!phoneIsValid) {
				phoneIsValid = validatePhone();
			}
		});

		email.blur(function() {
			emailIsValid = validateEmail();
		});

		email.keyup(function() {
			if (!emailIsValid) {
				emailIsValid = validateEmail();
			}
		});

		description.blur(function() {
			descriptionIsValid = validateDescription();
		});

		description.keypress(function(e) {
			e.preventDefault();

			if (description.val().trim().length > 499) {
				$('#description-error').text('You may only enter up to 500 characters').show();
			} else {
				description.val(description.val() + e.key);
				if (!descriptionIsValid) {
					descriptionIsValid = validateDescription();
				}
			}
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			clientNameIsValid = validateClientName();
			phoneIsValid = validatePhone();
			emailIsValid = validateEmail();
			descriptionIsValid = validateDescription();

			if (!clientNameIsValid) {
				clientName.focus();
			} else if (!phoneIsValid) {
				phone.focus();
			} else if (!emailIsValid) {
				email.focus();
			} else if (!descriptionIsValid) {
				description.focus();
			} else {
				var data = form.serialize() + '&countryCode=%2B' +
					selectedCountryCode.substring(1);

				$('#loading').css('display', 'inline-block');
				$.post('quote.php', data, function(result) {
					$('#loading').css('display', 'none');

					if (result === '1') {
						alert("Thanks for your request! I'll get back to you ASAP.");
					} else {
						alert(result);
					}
				}).fail(function() {
					$('#loading').hide();
					alert('An unknown server error has occurred. Please try again.');
				});
			}
		});
	</script>
</body>
</html>