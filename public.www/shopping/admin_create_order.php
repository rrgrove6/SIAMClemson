<?php
include_once "../common/template.php";
include_once "shopping_lib.php";
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
function get_total(element)
{
	var quantities = $(".qty");
	var total = 0;
	var offer_ids = [];

	for(var i = 0; i < quantities.length; i++)
	{
		var value = parseInt($(quantities[i]).val());
		if($(quantities[i]).val().search(/^[0-9]+$/) == 0 && value > 0) // test if it is a number
		{
			// get offer_id from offer string name
			var offer_id = $(quantities[i]).attr("name").substr($(quantities[i]).attr("name").indexOf("_") + 1);

			total += parseInt($(quantities[i]).val()) * get_price(offer_id);
			offer_ids.push(offer_id);
		}
	}

	$("#total").html("$" + parseFloat(total).toFixed(2));
	$("#offer_ids").val(offer_ids.join(","));
}

function get_price(offer_id)
{
	// get price string and remove the $
	var price = document.getElementById('price_' + offer_id).innerHTML.substr(1);
	
	return price;
}
</script>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 1000px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php

$officer_username = $_SERVER['REMOTE_USER'];
$username = get_str_value($_REQUEST, "username");

// check if the order is being submitted
if(get_int_value($_POST, "submit_order", -1) == 1)
{
	$order_id = create_new_order($username);
	$offer_ids = trim(get_str_value($_POST, "offer_ids", ""));
	if(strlen($offer_ids) > 0)
	{
		$offer_ids = explode(",", $offer_ids);
	}
	else
	{
		$offer_ids = array();
	}

	// add offers to orders
	$total = 0;
	$order_str = "";
	$problem_str = "\n\n";
	
	foreach($offer_ids as $offer_id)
	{
		$quantity = get_int_value($_POST, "offer_" . $offer_id);

		if($quantity > 0)
		{
			// try to add offer to order
			$added_cost = add_offer_to_order($order_id, $offer_id, $quantity);
			$offer_details = get_offer_details($offer_id);

			if($added_cost == -1)
			{
				// we couldn't fill the order
				$problem_str .= "Unfortunately, we were unable to fulfill your order for the following offer.\n\nItem: $offer_details[description]\nPrice: \$$offer_details[price]\nQuantity: $quantity\n\nPlease contact one of the SIAM officers to make arrangements regarding this issue.\n\n";
			}
			else
			{
				// add the offer to the order invoice
				$order_str .= "Item: " . $offer_details["description"] . "\nPrice: \$" . $offer_details["price"] . "\nQuantity: $quantity\n\n";
				$total += $added_cost;
			}
		}
	}
	
	$order_str .= "-------------\nTotal: \$" . number_format($total, 2, '.', ',') . $problem_str;





	
	// send confirmation email
    $message = "Your order has been successfully placed. Please review the details below to ensure that everything is correct. If you find a discrepancy, please notify one of the SIAM officers to correct the mistake. You can submit payment to any of the SIAM officers.\n";
    
    $officers = get_current_officers();
    
    foreach($officers as $officer)
    {
        $message .= "$officer[first_name] $officer[last_name] $officer[office_number]\n";
    }
    
    $message .= "\n" . $order_str;

	$message = wordwrap($message,70);
	 
	$headers = "From: SIAM Sales <siam@clemson.edu>";

	//$output = mail($username . "@clemson.edu", "SIAM order confirmation", $message, $headers);
	 
	 // send email to SIAM representative
	$rep = "siam@clemson.edu";
	 
	 // uncomment the line below once the initial rush dies down
	//$output = mail($rep, "A new SIAM order has been placed", $username . " has placed the following order:\n" . $order_str, $headers);
?>
<p>Your order has been succesfully placed, and you will be receiving a confirmation email shortly. You can submit payment to any of the SIAM officers. If you have any questions please contact one of the SIAM officers as well.</p>
<?php
} // end display of order confirmation page
else
{
	$groups = get_available_items();

	$table_str = "";
	foreach($groups as $group)
	{
		$table_str .= "<tr>\n\t<td colspan=\"4\" style=\"font-weight: bold; font-size: 30px; padding-top: 15px;\">$group[group_description]</td>\n</tr>\n";
		$table_str .= "<tr><td style=\"text-align: center;\" rowspan=\"" . (count($group["offers"]) + 1) . "\"><img src=\"img/$group[group_image]\"></td><td>Description</td><td>Price</td><td>Quantity</td></tr>";


		foreach($group["offers"] as $offer)
		{
			$table_str .= "<tr><td>$offer[offer_description]</td><td id=\"price_$offer[offer_id]\" style=\"text-align: right;\">\$$offer[offer_price]</td><td style=\"text-align: center;\"><input class=\"qty\" name=\"offer_$offer[offer_id]\" size=\"2\" onKeyUp=\"javascript:get_total(this);\" onChange=\"javascript:get_total(this);\" style=\"text-align: right;\"></td></tr>";
		}
	}
?>
<p>Fill out the form below to order your items. Once you are done, click the Place Order button at the bottom and you will receive a confirmation email summarizing your order. You may then submit payment to any of the SIAM officers.</p>
<form name="order" method="POST" action="admin_create_order.php">
<div>Username of person ordering: <input name="username" type="text" style="width: 200px;"></div>
<table align="center" style="text-align: left;">
<?php print($table_str); ?>
<tr>
	<td colspan="4"><hr></td>
</tr>
<tr>
	<td colspan="3" style="font-weight: bold; font-size: 20px;">Total</td>
	<td id="total" style="text-align: right;">$0.00</td>
</tr>
<tr>
	<td colspan="4" style="text-align: center;">
		<input type="hidden" name="submit_order" value="1">
		<input type="submit" value="Place Order">
		<input type="hidden" name="offer_ids" id="offer_ids">
	</td>
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
