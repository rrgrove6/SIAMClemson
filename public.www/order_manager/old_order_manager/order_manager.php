<html>
<head>
<link rel="stylesheet" href="../styles/siam.css" type="text/css"/>
<title>SIAM Order Manager</title>
</head>
<body style="text-align: center;">
<div style="width: 700px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="../img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Order Manager</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 525px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<p>Click on the order you want to update. The login listed below is the one they used when placing the order. Instructions for updating an order are available <a href="instructions.html">here</a>.</p>
<div>
	<form action="order_manager.php" method="POST">
		View: 
		<select name="display">
			<option value="unfilled"<?php print((isset($_POST['display']) && $_POST['display'] == "unfilled") ? " selected" : "");?>>Unfilled Orders</option>
			<option value="filled"<?php print((isset($_POST['display']) && $_POST['display'] == "filled") ? " selected" : "");?>>Filled Orders</option>
			<option value="cancelled"<?php print((isset($_POST['display']) && $_POST['display'] == "cancelled") ? " selected" : "");?>>Cancelled Orders</option>
			<option value="all"<?php print((isset($_POST['display']) && $_POST['display'] == "all") ? " selected" : "");?>>All Orders</option>
		</select>
		<input type="submit" value="View">
	</form>
</div>
<table style="margin-top: 20px;">
	<tr><td style="font-weight: bold; width: 50px;">Filled</td><td style="text-align: center; font-weight: bold; width: 125px;">Customer Userid</td><td style="text-align: center; font-weight: bold; width: 100px;">Date Ordered</td><td style="text-align: center; font-weight: bold; width: 85px;">Updated by</td><td style="text-align: center; font-weight: bold; width: 140px;">Last Updated on</td></tr>
<?php
include "../common/database.php";

function get_check($delivered)
{
	if($delivered == "1")
	{
		return "<img src=\"../img/check.png\">";
	}
	else
	{
		return "";
	}
}

$link = db_connect();

if(isset($_POST['display']) && $_POST['display'] == "all")
{
	// list all orders
	$query="Select order_id, cu_login, filled_by, delivered, If(UNIX_TIMESTAMP(date_last_updated) = 0,'never',DATE_FORMAT(date_last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(date_ordered, '%c/%e/%y') as date_ordered From orders Order by cu_login";
}
else if(isset($_POST['display']) && $_POST['display'] == "cancelled")
{
	// list cancelled orders
	$query="Select order_id, cu_login, filled_by, delivered, If(UNIX_TIMESTAMP(date_last_updated) = 0,'never',DATE_FORMAT(date_last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(date_ordered, '%c/%e/%y') as date_ordered From orders WHERE cancelled = 1 Order by cu_login";
}
else if(isset($_POST['display']) && $_POST['display'] == "filled")
{
	// list filled orders
	$query="Select order_id, cu_login, filled_by, delivered, If(UNIX_TIMESTAMP(date_last_updated) = 0,'never',DATE_FORMAT(date_last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(date_ordered, '%c/%e/%y') as date_ordered From orders WHERE delivered = 1 AND cancelled = 0 Order by cu_login";
}
else
{
	// list unfilled orders
	$query="Select order_id, cu_login, filled_by, delivered, If(UNIX_TIMESTAMP(date_last_updated) = 0,'never',DATE_FORMAT(date_last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(date_ordered, '%c/%e/%y') as date_ordered From orders WHERE delivered = 0 AND cancelled = 0 Order by cu_login";
}

$result = mysql_query($query, $link);

while($row = mysql_fetch_assoc($result))
{
	print("<tr>\n\t<td style=\"height: 25px;\">" . get_check($row['delivered']) . "</td>\n\t<td style=\"padding-left: 40px;\"><a href=\"update_order.php?id=$row[order_id]\">$row[cu_login]</a></td>\n\t<td style=\"padding-right: 25px; text-align: right;\">$row[date_ordered]</td>\n\t<td style=\"padding-left: 20px;\">$row[filled_by]</td>\n\t<td style=\"padding-left: 20px; text-align: right;\">$row[date_last_updated]</td>\n</tr>\n");
}
?>
</table>
</div>
</div>
</body>
</html>