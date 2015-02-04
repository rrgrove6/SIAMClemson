<?php
include "../common/database.php";
include "../common/general_funcs.php";

$link = db_connect();

$officer_username = $_SERVER['REMOTE_USER'];
$valid_order = false;

if(get_int_value($_POST, "submit_update", -1) == 1)
{
	$total_paid = 0 + $_POST['total_paid']; // this should force it to a number
	$delivered = (get_str_value($_POST, "delivered") == "on") ? 1 : 0;
	
	//update order
	$query="UPDATE shopping_orders SET total_paid = $total_paid, delivered = $delivered, filled_by =  '$officer_username', comments = '" . get_str_value($_POST, "comments") . "', date_last_updated = NOW() WHERE order_id = " . get_int_value($_REQUEST, "order_id");
	$result = mysql_query($query, $link);	
	
	// send back redirect to order_manager page
?>
<html>
<head><meta http-equiv="REFRESH" content="0;url=http://people.clemson.edu/~siam/order_manager/order_manager.php"></head>
<body>Please wait while you are redirected back to the Order Manager, or you can click <a href="order_manager.php">here</a> to go there directly.</body>
</html>
<?php
}
else //display the update form
{
	$order_id = get_int_value($_REQUEST, "order_id");
	if($order_id > 0)
	{
		// get order
		$query="SELECT order_id, cu_login, total_paid, delivered, comments, IFNULL(filled_by,'') AS filled_by, DATE_FORMAT(date_ordered, '%W, %M %e at %l:%i %p') AS date_ordered, DATE_FORMAT(date_last_updated, '%W, %M %e at %l:%i %p') AS date_last_updated FROM shopping_orders WHERE order_id = $order_id";
		$result = mysql_query($query, $link);
		
		if(mysql_num_rows($result) == 1)
		{
			$valid_order = true;
			$row = mysql_fetch_assoc($result);
			$username = $row['cu_login'];
			$total_paid = $row['total_paid'];
			$delivered = $row['delivered'];
			$comments = $row['comments'];
			$filled_by = $row['filled_by'];
			$date_ordered = $row['date_ordered'];
			$date_last_updated = $row['date_last_updated'];
			
			$query = "SELECT oo.quantity, o.description, o.price FROM shopping_offers_in_orders AS oo INNER JOIN shopping_offers AS o ON o.offer_id = oo.offer_id WHERE order_id = $order_id";
			$result = mysql_query($query, $link);
			
			$table_str = "";
			$total = 0;
			
			while($row = mysql_fetch_assoc($result))
			{
				$table_str .= "<tr>\n\t<td>$row[quantity]</td>\n\t<td style=\"padding-right: 20px;\">$row[description]</td>\n\t<td style=\"text-align: right;\">\$" . $row['price'] . "</td>\n</tr>\n";
				$total += $row['quantity'] * $row['price'];
			}
			
			$table_str .= "<tr>\n\t<td colspan=\"3\"><hr></td>\n</tr>\n<tr>\n\t<td colspan=\"2\" style=\"font-weight: bold;\">Total</td>\n\t<td style=\"text-align: right;\">\$" . number_format($total, 2, '.', ',') . "</td>\n</tr>\n";
		}
	}
?>
<html>
<head>
<link rel="stylesheet" href="../styles/siam.css" type="text/css"/>
<title>SIAM Order Manager</title>
<script type="text/javascript">
function update_order()
{
	document.getElementById("update").action = "update_order.php";
}

function cancel_order()
{
	document.getElementById("update").action = "cancel_order.php";
}
</script>
</head>
<body style="text-align: center;">
<div style="width: 700px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="../img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Order Manager</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 500px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<form name="update" id="update" method="POST" action="update_order.php">
<div style="text-align: left; padding-bottom: 20px;">Order for <span style="font-weight: bold; font-size: 22px; color: #ff6206;"><?php print($username); ?></span> placed on <?php print($date_ordered); ?></div>
<div>
<table>
<tr>
	<td style="text-align: left; font-weight: bold; width: 100px;">Quantity</td>
	<td style="text-align: left; min-width: 350px; font-weight: bold;">Description</td>
	<td style="text-align: right; font-weight: bold; width: 50px;">Price</td>
</tr>
<?php print($table_str); ?>
</table>
</div>
<div align="left" style="margin-top: 15px; margin-bottom: 10px;"><span style="font-weight: bold;">Comments:</span><br><textarea style="width: 350px; height: 100px;" name="comments"><?php print($comments); ?></textarea>
<table align="right" style="width: 140px;">
	<tr>
		<td>Total Paid:</td>
		<td style="text-align: right;">$<input type="text" style="text-align: right;" name="total_paid" value="<?php print($total_paid); ?>" size="2"></td>
	</tr>
	<tr>
		<td>Delivered:</td>
		<td style="text-align: right;"><input type="checkbox" name="delivered" <?php print($delivered ? "checked" : "");?>></td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px; text-align: right;">
			<input type="hidden" name="submit_update" value="1">
			<input type="hidden" name="order_id" value="<?php print(get_int_value($_REQUEST, "order_id")); ?>">
			<input type="submit" value="Update Order" onclick="javascript:update_order();"><br>
			<input type="submit" value="Cancel Order" onclick="javascript:cancel_order();">
		</td>
	</tr>
</table>
</div>
<div style="text-align: left; clear: right;"><a href="order_manager.php">Return to Order Manager</a></div>
<?php
if($filled_by != "")
{
	print("<div style=\"text-align: right; padding-top: 10px; color: #6666cc\">This order was last updated by $filled_by on $date_last_updated</div>");
}
?>
</form>
</div>
</div>
</body>
</html>
<?php
} //end display update form
?>
