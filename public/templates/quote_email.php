<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
				<tr>
					<td align="center" valign="top">
						<table border="1" cellpadding="20" cellspacing="0" width="100%" style="background-color: lightskyblue; color: black">
							<tr>
								<td valign="center">
									Contact name
								</td>
								<td valign="center">
									<?php echo $clientName ?>
								</td>
							</tr>
							<tr>
								<td valign="center">
									Company name
								</td>
								<td valign="center">
									<?php echo $companyName ?>
								</td>
							</tr>
							<tr>
								<td valign="center">
									Phone number
								</td>
								<td valign="center">
									<?php echo $countryCode . $phone ?>
								</td>
							</tr>
							<tr>
								<td valign="center">
									Email address
								</td>
								<td valign="center">
									<?php echo $email ?>
								</td>
							</tr>
							<tr>
								<td valign="center">
									Project description
								</td>
								<td valign="center">
									<?php echo $description ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>