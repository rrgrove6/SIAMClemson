<!doctype html>
<html>
<head>
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
<title>SIAM Picnic Manager</title>
</head>
<body style="text-align: center;">
<div style="width: 800px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="/~siam/img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Picnic Manager</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<p>Click on the person you want to update. The username listed below is the one they used when signing up for the picnic. Instructions for updating a person are available <a href="instructions.html">here</a>.</p>
<p>If a person pays you, but did not signup online, you should fill out the form for them <a href="picnic_signup_admin.php">here</a>, and then their name will show up in the list of people who have signed up.</p>
<table style="margin-top: 20px;">
	<tr>
        <td style="font-weight: bold; width: 50px;">Paid</td>
        <td style="font-weight: bold; width: 200px;">Name</td>
        <td style="text-align: center; font-weight: bold; width: 125px;">Username</td>
        <td style="text-align: center; font-weight: bold; width: 100px;">Signup Date</td>
        <td style="text-align: center; font-weight: bold; width: 85px;">Updated by</td>
        <td style="text-align: right; font-weight: bold; width: 140px;">Last Updated on</td>
</tr>
<?php
include_once "../picnic_lib.php";

function get_check($paid)
{
	if($paid == "1")
	{
		return "<img src=\"/~siam/img/check.png\">";
	}
	else
	{
		return "";
	}
}

	$orders_list = get_list_of_orders(get_current_semester(), get_current_year());
	
	foreach($orders_list as $order)
	{
		print("<tr><td style=\"height: 25px;\">" . get_check($order["paid"]) . "</td><td>$order[first_name] $order[last_name]</td><td style=\"padding-left: 40px;\"><a href=\"update_person.php?id=$order[user_name]\">$order[user_name]</a></td><td style=\"padding-right: 25px; text-align: right;\">$order[signup]</td><td style=\"padding-left: 20px;\">$order[updated_by]</td><td style=\"padding-left: 20px; text-align: right;\">$order[date_last_updated]</td></tr>");
	}
?>
</table>
</div>
</div>
</div>
</body>
</html>