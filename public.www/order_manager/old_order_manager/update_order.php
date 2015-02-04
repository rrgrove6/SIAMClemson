<?php
include "../common/database.php";
	$link = db_connect();
	
	$officer_username = $_SERVER['REMOTE_USER'];
	$valid_order = false;

if(isset($_POST['submit_update']) && $_POST['submit_update'] == 1)
{
	$total_paid = 0 + $_POST['total_paid']; // this should force it to a number
	$delivered = ($_POST['delivered'] == "on") ? 1 : 0;
	
	//update order
		$query="Update orders Set total_paid = $total_paid, delivered = $delivered, filled_by =  '$officer_username', comments = '$_POST[comments]', date_last_updated = NOW() Where order_id = " . intval($_REQUEST['id']);
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
	if(isset($_REQUEST['id']))
	{
		// get order
		$query="Select order_id, cu_login, total_paid, delivered, comments, IFNULL(filled_by,'') as filled_by, DATE_FORMAT(date_ordered, '%W, %M %e at %l:%i %p') as date_ordered, DATE_FORMAT(date_last_updated, '%W, %M %e at %l:%i %p') as date_last_updated From orders Where order_id = " . intval($_REQUEST['id']);
		$result = mysql_query($query, $link);
		
		if(mysql_num_rows($result) == 1)
		{
			$valid_order = true;
			$row = mysql_fetch_assoc($result);
			$order_id = $row['order_id'];
			$username = $row['cu_login'];
			$total_paid = $row['total_paid'];
			$delivered = $row['delivered'];
			$comments = $row['comments'];
			$filled_by = $row['filled_by'];
			$date_ordered = $row['date_ordered'];
			$date_last_updated = $row['date_last_updated'];
			
			$query = "SELECT order_items.quantity, items.desc, items.price From items_in_orders as order_items Inner Join items on order_items.item_id = items.item_id Where order_id = $order_id";
			$result = mysql_query($query, $link);
			
			$table_str = "";
			$total = 0;
			
			while($row = mysql_fetch_assoc($result))
			{
				$table_str .= "<tr>\n\t<td>$row[quantity]</td>\n\t<td>$row[desc]</td>\n\t<td style=\"text-align: right;\">\$" . $row['price'] . "</td>\n</tr>\n";
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
</head>
<body style="text-align: center;">
<div style="width: 700px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="../img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Order Manager</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 500px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<form name="update" method="POST" action="update_order.php">
<div style="text-align: left; padding-bottom: 20px;">Order for <span style="font-weight: bold; font-size: 22px; color: #ff6206;"><?php print($username); ?></span> placed on <?php print($date_ordered); ?></div>
<div>
<table>
<tr>
	<td style="text-align: center; font-weight: bold;">Quantity</td>
	<td style="text-align: center; width: 300px; font-weight: bold;">Description</td>
	<td style="text-align: center; font-weight: bold;">Price</td>
</tr>
<?php print($table_str); ?>
</table>
</div>
<div align="left" style="margin-top: 15px; margin-bottom: 10px;"><span style="font-weight: bold;">Comments:</span><br><textarea rows="5" cols="40" name="comments"><?php print($comments); ?></textarea>
<table align="right">
	<tr>
		<td>Total Paid:</td>
		<td style="text-align: right;">$<input type="text" style="text-align: right;" name="total_paid" value="<?php print($total_paid); ?>" size="2"></td>
	</tr>
	<tr>
		<td>Delivered:</td>
		<td style="text-align: right;"><input type="checkbox" name="delivered" <?php print($delivered ? "checked" : "");?>></td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;"><input type="hidden" name="submit_update" value="1"><input type="hidden" name="id" value="<?php print(isset($_REQUEST['id']) ? $_REQUEST['id'] : 0); ?>"><input type="submit" value="Update Order"></td>
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