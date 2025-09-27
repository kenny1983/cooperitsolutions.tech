<?php

require '..\vendor\autoload.php';

use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

extract($_POST);

// Ensure that all necessary form input has been provided in the correct format
if (!isset($clientName) || empty($clientName)) {
	echo 'You must enter a contact name';
} else if (!isset($phone) || empty($phone)) {
	echo 'You must enter a contact number';
} else if (!isset($email) || empty($email)) {
	echo 'You must enter a valid email address';
} else if (!isset($description) || empty($description)) {
	echo 'You must enter a project description';
} else if (!preg_match('/^([a-zA-Z]{3,})|([a-zA-Z]{3,}-[a-zA-Z]{3,})$/', $clientName)) {
	echo 'Contact name must be 3 or more alphanumeric characters only (optionally separated by a hyphen)';
} else if (!preg_match('/\d{1,14}$/', $phone)) {
	echo 'Contact number must be 1 - 14 digits only (no non-numeric characters)';
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	echo 'You must enter a valid email address';
} else if (!preg_match('/^.{25,500}$/', $description)) {
	echo 'Your project description must be 25 - 500 characters long';
} else {
	/*** Verify phone number with Twilio ***/
	// Your Account Sid and Auth Token from twilio.com/user/account
	$sid = '';
	$token = '';

	$client = new Client($sid, $token);

	try {
		$number = $client->lookups
			->phoneNumbers($countryCode . $phone)
			->fetch(array("type" => "carrier"));
	} catch (RestException $e) {
		if ($e->getStatusCode() == 404) {
			echo 'You must enter a valid phone number for ' .
				'your country (no non-numeric characters)';
		} else {
			echo 'An unknown error has occurred while attempting to validate ' .
				'your phone number. Please ensure that you have entered a ' .
				'number of the correct length for your country and try again';
		}

		die();
	}

	$phoneType = $number->carrier['type'];
    $phoneFormat = $number->nationalFormat;

	// All good; send the quote email using PHPMailer
	$mail = new PHPMailer;

	$mail->isSMTP();									// Set mailer to use SMTP
	$mail->Host		  = 'smtp.gmail.com';				// Specify main and backup SMTP servers
	$mail->SMTPAuth   = true;							// Enable SMTP authentication
	$mail->Username   = 'kentcooper83@gmail.com';		// SMTP username
	$mail->Password	  = 'bu!!d0gs';						// SMTP password
	$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
	$mail->Port		  = 587;							// TCP port to connect to

	$mail->setFrom($email);								// Set from address
	$mail->addAddress('getaquote@kentcooper.tech');		// Set recipient address
	$mail->isHTML(true);								// Set email format to HTML

	$mail->Subject = 'Quote Request from ' . (isset($companyName) &&
		!empty($companyName) ? $companyName : $clientName);
	$mail->Body    = renderPhpToHtml('../templates/quote_email.php', $_POST);
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if ($mail->send()) {
		echo 1;
	} else {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
}

function renderPhpToHtml() {
	if (is_array(func_get_arg(1)) && !empty(func_get_arg(1))) {
		extract(func_get_arg(1));
	}

	ob_start();
	require func_get_arg(0);
	return ob_get_clean();
}