<?php
include "../common/template.php";
include "../common/database.php";
include_once "../common/general_funcs.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../styles/siam.css" type="text/css">
	<title>Clemson University SIAM student chapter</title>
	<script type="text/javascript">
	function calculate_total()
	{
		var inputs = document.getElementsByTagName("input");
		var total = 0;
		for(var i = 0; i < inputs.length; ++i)
		{
			if(inputs[i].name.search(/^offer/) == 0)
			{
				if(inputs[i].value.toString().search(/^[0-9]+$/) == 0) // test if it is a number
				{
					offer_total = parseInt(inputs[i].value) * get_price(inputs[i].name);
					total += offer_total;
					document.getElementById('total_' + inputs[i].name).innerHTML = "$" + parseFloat(offer_total).toFixed(2);
				}
				else if(inputs[i].value.length > 0)
				{
					document.getElementById('total').innerHTML = "$--.--";
					document.getElementById('total_' + inputs[i].name).innerHTML = "$--.--";
					return false;
				}
			}
		}
		// change this to get the actual prices
		document.getElementById('total').innerHTML = "$" + parseFloat(total).toFixed(2);
	}
	
	function get_total(element)
	{
		if((element.value.length == 0) || (element.value.toString().search(/^[0-9]+$/) == 0)) // test if it is a number
		{
			calculate_total();
		}
		else if(element.value.length > 0)
		{
			document.getElementById('total').innerHTML = "$--.--";
			document.getElementById('total_' + element.name).innerHTML = "$--.--";
			return false;
		}
		return true;
	}

	function get_price(offer)
	{
		// get offer_id from offer
		var offer_id = offer.substr(offer.indexOf("_")+1);
		
		// get price string
		var price = parseFloat(document.getElementById('price_' + offer_id).innerHTML);

		return price;
	}
	</script>
</head>
<body style="text-align: center;" onload="javascript:calculate_total();">
<?php print_header(); ?>
<div style="text-align: center;">
	<div style="width: 600px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
$username = $_SERVER['REMOTE_USER'];

// check if a quantity is being changed
$offer_id = get_int_value($_REQUEST, "change");
if($offer_id != 0)
{
	$new_quantity = get_int_value($_REQUEST, "quantity");
	
	if($new_quantity <= 0)
	{
		// delete from cart
		unset($_SESSION["cart"][$offer_id]);
	}
	else
	{
		// change quantity
		$_SESSION["cart"][$offer_id] = $new_quantity;
	}
}
// print out cart contents
$cart = get_cart();

$link = db_connect();

$query = "SELECT offers.offer_id, offers.description, offers.price, io.quantity, items.desc, items.thumbnail FROM offers INNER JOIN items_in_offers AS io ON offers.offer_id = io.offer_id INNER JOIN items ON io.item_id = items.item_id WHERE offers.offer_id IN (" . implode(",",array_keys($cart)) . ") ORDER BY offers.offer_id, io.item_id";

$result = mysql_query($query, $link);

$table_str = "";
$table_data = array();
$cur_offer = 0;
$total_cost = 0;
while($row = mysql_fetch_assoc($result))
{
	if($row["offer_id"] != $cur_offer)
	{
		$cur_offer = $row["offer_id"];
		$qty = $cart[$cur_offer];
		$price = $row["price"];
		$table_data[][0] = sprintf("$row[description] <span style=\"position:relative; float: right;\"><input type=\"text\" id=\"offer_$cur_offer\" name=\"offer_$cur_offer\" value=\"$qty\" style=\"text-align: right;\" onKeyUp=\"javascript:get_total(this);\" onChange=\"javascript:get_total(this);\" size=\"2\"> x \$<span id=\"price_$cur_offer\">%.2f</span></span>", $price);
		$table_data[count($table_data) - 1][1] = sprintf("<span id=\"total_offer_$cur_offer\">\$%.2f</span>" ,$qty * $price);
		$table_data[][0] = "<li><img src=\"/~siam/img/$row[thumbnail]\" alt=\"$row[desc]\"> $row[quantity] $row[desc]</li>\n";
	}
	else
	{
		$table_data[count($table_data) - 1][0] = "<li>$row[quantity] $row[desc]</li>\n";
	}
}

for($i = 0; $i < count($table_data); $i++)
{
	if(count($table_data[$i]) == 2)
	{
		$table_str .= "<tr><td>" . $table_data[$i][0] . "</td><td style=\"text-align: right; padding-left: 15px;\">" . $table_data[$i][1] . "</td></tr>\n";
	}
	else
	{
		$table_str .= "<tr><td><ul>" . $table_data[$i][0] . "</ul></td><td>&nbsp;</td></tr>\n";
	}
}

if($cur_offer != 0)
{
	$table_str = "<table><tr><td style=\"text-align: center; font-weight: bold; font-size: 16pt;\">Offers</td><td  style=\"text-align: center; font-weight: bold; font-size: 16pt;\">Totals</td></tr>\n" . $table_str . sprintf("<tr><td colspan=\"2\">Total</td><td id=\"total\" style=\"text-align: right;\">\$%.2f</td></tr></table>", $total_cost);
}

print($table_str)
?>
	</div>
</div>
<?php print_footer(); ?>
</body>
</html>