<?php
include "../common/template.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
<script type="text/javascript">
function get_total(element)
{
	if((element.value.length == 0) || (element.value.toString().search(/^[0-9]+$/) == 0)) // test if it is a number
	{
		var inputs = document.getElementsByTagName("input");
		var total = 0;
		for(var i = 0; i < inputs.length; ++i)
		{
			if(inputs[i].name.search(/^item/) == 0)
			{
				if(inputs[i].value.toString().search(/^[0-9]+$/) == 0) // test if it is a number
				{
					total += parseInt(inputs[i].value) * get_price(inputs[i].name);
				}
				else if(inputs[i].value.length > 0)
				{
					document.getElementById('total').innerHTML = "$--.--";
					return false;
				}
			}
		}
		// change this to get the actual prices
		document.getElementById('total').innerHTML = "$" + parseFloat(total).toFixed(2);
	}
	else if(element.value.length > 0)
	{
		document.getElementById('total').innerHTML = "$--.--";
		return false;
	}
	return true;
}

function get_price(item)
{
	// get item_id from item
	var item_id = item.substr(item.indexOf("_")+1);
	
	// get price string
	var price = document.getElementById('price_' + item_id).innerHTML.substr(1);
	
	return price;
}
</script>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 600px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
include "../common/database.php";

$username = $_SERVER['REMOTE_USER'];

// check if the order is being submitted
if(isset($_POST['submit_order']) && ($_POST['submit_order'] == 1))
{
	$link = db_connect();
	
	// create order
	$query="Insert Into orders (cu_login, date_ordered) Values (\"$username\", NOW())";
	$result = mysql_query($query, $link);
	
	$query="Select Last_Insert_Id() as order_id"; // this will give us the id generated above
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	$order_id = $row['order_id'];
	
	// place order
	$query = "Select * From items";
	$result = mysql_query($query, $link);
	$item_ids = array();
	while($row = mysql_fetch_assoc($result))
	{
		$item_info[] = $row;
	}
	
	$total = 0;
	$order_str = "";
	
	for($i = 0; $i < count($item_info); $i++)
	{
		$item_id = $item_info[$i]['item_id'];
		$quantity = intval($_POST['item_' . $item_id]);
		if($quantity > 0)
		{
				$query = "Insert Into items_in_orders (order_id, item_id, quantity) Values ($order_id, $item_id, $quantity)";
				$result = mysql_query($query, $link);
				$order_str .= "Item: " . $item_info[$i]['desc'] . "\nPrice: \$" . $item_info[$i]['price'] . "\nQuantity: $quantity\n\n";
				$total += $quantity * $item_info[$i]['price'];
		}
	}
	
	$order_str .= "-------------\nTotal: \$" . number_format($total, 2, '.', ',');
	
	// send confirmation email
    $message = "Your order has been successfully placed. Please review the details below to ensure that everything is correct. If you find a discrepancy, please notify one of the SIAM officers to correct the mistake. You can submit payment to any of the SIAM officers.\nJanine E. Janoski O-6\nTharanga Wickramarachchi E-6\nQi Zheng E-1a\nJames Wilson E-8\n\n" . $order_str;

    $message = wordwrap($message,70);
	 
	 $headers = "From: SIAM Sales <siam@clemson.edu>";

    $output = mail($username . "@clemson.edu", "SIAM order confirmation", $message, $headers);
	 
	 // send email to SIAM representative
	 $rep = "siam@clemson.edu";
	 
	 $output = mail($rep, "A new SIAM order has been placed", $username . " has placed the following order:\n" . $order_str, $headers);
?>
<p>Your order has been succesfully placed, and you will be receiving a confirmation email shortly. You can submit payment to any of the SIAM officers. If you have any questions please contact one of the SIAM officers as well.</p>
<?php
} // end display of order confirmation page
else
{
	$link = db_connect();
	$query="Select * From items Order By display_order";

	$result = mysql_query($query, $link);
	$table_str = "";
	while($row = mysql_fetch_assoc($result))
	{
		$table_str .= "<tr>\n\t<td>$row[desc]</td>\n\t<td id=\"price_$row[item_id]\">\$$row[price]</td>\n\t<td style=\"text-align: right;\"><input type=\"text\" name=\"item_$row[item_id]\" size=\"2\" onKeyUp=\"javascript:get_total(this)\" onChange=\"javascript:get_total(this)\" style=\"text-align: right;\"></td>\n</tr>\n";
	}
?>
<p>Fill out the form below to order your items. Once you are done, click the Place Order button at the bottom and you will receive a confirmation email summarizing your order. You may then submit payment to any of the SIAM officers.</p>
<table align="center" style="margin-bottom: 20px; text-align: center;" cellspacing="0">
<tr>
	<td colspan="2" style="border-right: solid 2px #ff6206; border-bottom: none; border-left: solid 2px #ff6206; border-top: solid 2px #ff6206; padding-top: 5px; padding-left: 5px; padding-right: 5px;"><img src="img/SIAM_design_white.png"></td>
</tr>
<tr>
	<td colspan="2" style="text-align: center; font-size: 20px; border-right: solid 2px #ff6206; border-top: none; border-left: solid 2px #ff6206; border-bottom: solid 1px #ff6206;">T-shirts</td>
</tr>
<tr>
	<td style="width: 300px; border-right: solid 1px #ff6206; border-bottom: none; border-left: solid 2px #ff6206; border-top: solid 1px #ff6206; padding-top: 5px;"><img src="img/koozie_thumb.jpg"></td>
	<td style="width: 300px; border-right: solid 2px #ff6206; border-bottom: none; border-left: solid 1px #ff6206; border-top: solid 1px #ff6206; padding-top: 5px;"><img src="img/car_decal_thumb.png"></td>
</tr>
<tr>
	<td style="text-align: center; font-size: 20px; border-right: solid 1px #ff6206; border-top: none; border-left: solid 2px #ff6206; border-bottom: solid 2px #ff6206;">Koozies</td>
	<td style="text-align: center; font-size: 20px; border-right: solid 2px #ff6206; border-top: none; border-left: solid 1px #ff6206; border-bottom: solid 2px #ff6206;">Car Decals</td>
</tr>
</table>
<form name="order" method="POST" action="order.php">
<table align="center" style="text-align: left;">
<tr>
	<td style="text-align: center; width: 200px; font-weight: bold;">Item Description</td>
	<td style="text-align: center; font-weight: bold;">Price</td>
	<td style="text-align: center; font-weight: bold;">Quantity</td>
</tr>
<?php print($table_str); ?>
<tr>
	<td colspan="3"><hr></td>
</tr>
<tr>
	<td colspan="2">Total</td>
	<td id="total" style="text-align: right;">$0.00</td>
</tr>
<tr>
	<td colspan="2" style="text-align: right;"><input type="hidden" name="submit_order" value="1"><input type="submit" value="Place Order"></td>
</tr>
</table>
</form>
<?php
} // end display of order page
?>
</div>
</div>
<?php print_footer(); ?>
</body>
</html>