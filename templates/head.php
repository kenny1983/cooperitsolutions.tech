<?php function getAge() {
	$birthDate = '1983-09-03';
	$adjust = (date('md') >= date('md', strtotime($birthDate))) ? 0 : 1;
	$years = date('Y') - date('Y', strtotime($birthDate));
	return $years - $adjust;
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=VT323" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/css/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
	<title>Kent Cooper - Full Stack Web Developer - <?php echo $pageTitle ?></title>
</head>