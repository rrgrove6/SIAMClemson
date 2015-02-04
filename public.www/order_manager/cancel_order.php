<?php
include "../common/database.php";
include "../common/general_funcs.php";

$officer_username = $_SERVER['REMOTE_USER'];

$link = db_connect();

// check if the order is being cancelled (we should check that it has not already been cancelled)
$order_id = get_int_value($_REQUEST, "order_id");
if($order_id > 0)
{
	$total_paid = get_float_value($_REQUEST, "total_paid");
	$delivered = (get_str_value($_POST, "delivered") == "on") ? 1 : 0;
	
	// cancel the order (should we update the last_updated field?)
	$query = "UPDATE shopping_orders SET cancelled = 1, total_paid = $total_paid, delivered = $delivered, filled_by =  '$officer_username', comments = '" . get_str_value($_POST, "comments") . "', date_last_updated = NOW() WHERE order_id = $order_id AND cancelled = 0";
	$result = mysql_query($query, $link);
	  
	// add items in order back into inventory
	$query = "SELECT io.item_id, (oo.quantity * io.quantity) AS quantity FROM shopping_offers_in_orders AS oo INNER JOIN shopping_offers AS o ON o.offer_id = oo.offer_id LEFT JOIN shopping_items_in_offers io ON io.offer_id = oo.offer_id WHERE order_id = $order_id";
	$result = mysql_query($query, $link);
	  
	$items = fetch_all_from_result($result);
	  
	foreach($items as $item)
	{
		$query = "UPDATE shopping_items SET remaining = remaining + $item[quantity] WHERE item_id = $item[item_id]";
		$result = mysql_query($query, $link);
	}
}
?>
<html>
<head><meta http-equiv="REFRESH" content="0;url=http://people.clemson.edu/~siam/order_manager/order_manager.php"></head>
<body>Please wait while you are redirected back to the Order Manager, or you can click <a href="order_manager.php">here</a> to go there directly.</body>
</html>
