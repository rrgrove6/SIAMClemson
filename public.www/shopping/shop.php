<?php
include_once "../common/template.php";
include_once "shopping_lib.php";
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css">
<title>Clemson University SIAM student chapter</title>
<style type="text/css">
.item_header
{
	font-weight: bold;
	font-size: 45px;
}
.item_display
{
	margin-top: 15px;
	margin-bottom: 35px;
}
</style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
    

    <div style="margin-top: 10px; margin-bottom: 15px; text-align: center;">
        <div style="text-align: left;">To place an order click the button below. Note that this will require you to login with your Clemson username and password. If you do not have a Clemson username and password please click <a href="http://people.clemson.edu/~siam/comments.php">here</a> to contact the SIAM president about placing an order.</div>
            <button type="button" onclick="javascript:window.location='order.php';">Place an Order</button>
    </div>
	<div class="item_header">2010-2013 Champion T-shirt</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/orange_long_400.png" alt="2014 Champion orange long sleeved shirt"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/orange_short_600.png" alt="2014 Champion orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/purple_long_400.png" alt="2014 Champion purple long sleeved shirt"></td>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/purple_short_600.png" alt="2014 Champion purple short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$16</td>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">2013 T-shirts</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2013_orange_long_400.png" alt="2013 orange long sleeved shirt"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2013_orange_short_600.png" alt="2013 orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/2013_purple_long_400.png" alt="2013 purple long sleeved shirt"></td>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/2013_purple_short_600.png" alt="2013 purple short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$16</td>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">2012 T-shirts</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2012_orange_long_400.png" alt="2012 orange long sleeved shirt"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2012_orange_short_600.png" alt="2012 orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/2012_purple_long_400.png" alt="2012 purple long sleeved shirt"></td>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/2012_purple_short_600.png" alt="2012 purple short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$16</td>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">2011 T-shirts</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/orange_long_400.png" alt="2011 orange long sleeved shirt"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/orange_short_600.png" alt="2011 orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/purple_long_400.png" alt="2011 purple long sleeved shirt"></td>
			<td style="padding: 40px 10px 0px 10px;"><img src="img/purple_short_600.png" alt="2011 purple short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$16</td>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">2010 T-shirts</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2010_shirt_thumb.png" alt="2010 orange long sleeved shirt"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2010_shirt_short_sleeve.png" alt="2010 orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$16</td>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small - 4XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">2009 T-shirts</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/2009_shirt.png" alt="2009 orange short sleeved shirt"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;">Sizes: Small, Large, XL<br>$12</td>
		</tr>
	</table>
	<div class="item_header">Miscellaneous</div>
	<table class="item_display" align="center" style="text-align: center;" cellspacing="0">
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/koozie_thumb.jpg" alt="orange koozie"></td>
			<td style="padding: 10px 10px 0px 10px;"><img src="img/car_decal_thumb.png" alt="Clemson Mathematical Sciences car decal"></td>
		</tr>
		<tr>
			<td style="padding: 10px 10px 0px 10px;"><span style="font-weight: bold; font-size: 16px;">Koozies</span><br>1 for $2 or 2 for $3</td>
			<td style="padding: 10px 10px 0px 10px;"><span style="font-weight: bold; font-size: 16px;">Car Decals [3in by 10in]</span><br>$6</td>
		</tr>
		<tr>
			<td colspan="3" style="padding-top: 40px; text-align: center;">
				<div style="text-align: left;">To place an order click the button below. Note that this will require you to login with your Clemson username and password. If you do not have a Clemson username and password please click <a href="http://people.clemson.edu/~siam/comments.php">here</a> to contact the SIAM president about placing an order.</div>
				<button type="button" onclick="javascript:window.location='order.php';">Place an Order</button>
			</td>
		</tr>
	</table>


<?php print_footer(); ?>
</body>
</html>
