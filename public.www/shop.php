<?php
include "common/template.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<table align="center" style="text-align: center;" cellspacing="0">
	<tr>
		<td style="padding: 10 10 0 10px;"><img src="img/koozie_thumb.jpg"></td>
		<td style="padding: 10 10 0 10px;"><img src="img/car_decal_thumb.png"></td>
		<td style="padding: 10 10 0 10px;"><img src="img/2010_shirt_thumb.png"></td>
	</tr>
	<tr>
		<td style="padding: 10 10 0 10px;"><span style="font-weight: bold; font-size: 16px;">Koozies</span><br>1 for $2 or 2 for $3</td>
		<td style="padding: 10 10 0 10px;"><span style="font-weight: bold; font-size: 16px;">Car Decals [3in by 10in]</span><br>$6</td>
		<td style="padding: 10 10 0 10px;"><span style="font-weight: bold; font-size: 16px;">T-shirts [Small - 4XL]</span><br>$12</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 20px; text-align: center;"><button type="button" onclick="javascript:window.location='order.php';">Place an Order</button></td>
	</tr>
</table>
<?php print_footer(); ?>
</body>
</html>