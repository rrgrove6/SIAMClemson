<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";

function get_available_items()
{
	$link = db_connect();
	$query = "SELECT dg.group_id, group_description, group_image, o.offer_id, og.link_description AS offer_description, o.price AS offer_price, i.item_id, i.desc AS item_description, io.quantity FROM shopping_offers_in_groups AS og LEFT JOIN shopping_display_groups AS dg ON dg.group_id = og.group_id LEFT JOIN shopping_offers AS o ON o.offer_id = og.offer_id LEFT JOIN shopping_items_in_offers AS io ON io.offer_id = og.offer_id LEFT JOIN shopping_items AS i ON i.item_id = io.item_id WHERE i.remaining > 0 ORDER BY dg.display_order, og.offer_display_order, i.desc";

	$result = mysql_query($query, $link);
	
	$items = fetch_all_from_result($result);

	$groups = array();

	$cur_group_id = -1;
	$cur_offer_id = -1;

	for($i = 0; $i < count($items); $i++)
	{
		$cur_item = $items[$i];

		if($cur_item["group_id"] != $cur_group_id)
		{
			$cur_group_id = $cur_item["group_id"];
			$cur_offer_id = -1;
			$groups[] = array("group_id" => $cur_group_id, "group_description" => $cur_item["group_description"], "group_image" => $cur_item["group_image"], "offers" => array());
		}
		
		if($cur_item["offer_id"] != $cur_offer_id)
		{
			$cur_offer_id = $cur_item["offer_id"];
			$groups[count($groups) - 1]["offers"][] = array("offer_id" => $cur_offer_id, "offer_description" => $cur_item["offer_description"], "items" => array(), "offer_price" => $cur_item["offer_price"]);
		}

		$offers = $groups[count($groups) - 1]["offers"];
		$groups[count($groups) - 1]["offers"][count($offers) - 1]["items"][] = array("item_id" => $cur_item["item_id"], "item_description" => $cur_item["item_description"], "quantity" => $cur_item["quantity"]);
	}

	return $groups;
}

function get_items()
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_items";
    
    $result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function create_new_order($username)
{
	$link = db_connect();
	// create order
	$query="INSERT INTO shopping_orders(cu_login, date_ordered) VALUES(\"$username\", NOW())";
	$result = mysql_query($query, $link);
	
	$query="SELECT LAST_INSERT_ID() AS order_id"; // this will give us the id generated above
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	return $row["order_id"];
}

function get_items_in_offer($offer_id)
{
	$link = db_connect();
    $query = "SELECT i.item_id, i.desc, io.quantity FROM shopping_items_in_offers AS io LEFT JOIN shopping_items AS i ON i.item_id = io.item_id WHERE offer_id = $offer_id";
    $result = mysql_query($query, $link);
    return fetch_all_from_result($result);
}

function add_offer_to_order($order_id, $offer_id, $quantity)
{
	$total = -1;
	$link = db_connect();
	$query = "SELECT SUM(IF($quantity * io.quantity <= i.remaining, 0, 1)) AS errors FROM shopping_items_in_offers AS io LEFT JOIN shopping_items AS i ON i.item_id = io.item_id WHERE offer_id = $offer_id GROUP BY offer_id";

	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	if($row["errors"] == 0)
	{
		// add the offer to the order
		$query = "INSERT INTO shopping_offers_in_orders(order_id, offer_id, quantity) VALUES($order_id, $offer_id, $quantity)";
		$result = mysql_query($query, $link);

		// remove the items from inventory
		$items = get_items_in_offer($offer_id);

		foreach($items as $item)
		{
			$query = "UPDATE shopping_items SET remaining = remaining - ($quantity * $item[quantity]) WHERE item_id = $item[item_id]";
			$result = mysql_query($query, $link);
		}

		// get the cost of buying this offer
		$query = "SELECT ($quantity * price) AS total FROM shopping_offers WHERE offer_id = $offer_id";
		$result = mysql_query($query, $link);
		$row = mysql_fetch_assoc($result);
		$total = $row["total"];
	}
	
	return $total;
}

function get_offer_details($offer_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_offers WHERE offer_id = $offer_id";

	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function get_sales_volume()
{
	$link = db_connect();
    $query = "SELECT SUM(oo.quantity * io.quantity) AS number_sold, i.desc FROM shopping_offers_in_orders AS oo LEFT JOIN shopping_offers AS o ON o.offer_id = oo.offer_id LEFT JOIN shopping_items_in_offers AS io ON io.offer_id = o.offer_id LEFT JOIN shopping_items AS i ON i.item_id = io.item_id LEFT JOIN shopping_orders AS ord ON ord.order_id = oo.order_id WHERE ord.cancelled = 0 AND date_ordered >= STR_TO_DATE(\"9-1-2014\", \"%c-%e-%Y\") GROUP BY i.item_id ORDER BY i.desc";
    
    $result = mysql_query($query, $link);
    return fetch_all_from_result($result);
}

function get_sales_revenue()
{
	$link = db_connect();
    $sale_start_date = "9-1-2014";
    $query = "SELECT SUM(oo.quantity * o.price) AS total FROM shopping_offers_in_orders AS oo LEFT JOIN shopping_offers AS o ON o.offer_id = oo.offer_id LEFT JOIN shopping_orders AS ord ON ord.order_id = oo.order_id WHERE ord.cancelled = 0 AND date_ordered >= STR_TO_DATE(\"$sale_start_date\", \"%c-%e-%Y\")";
    
    $result = mysql_query($query, $link);
    $row = mysql_fetch_assoc($result);
    return $row["total"];
}

function get_collection_totals()
{
	$link = db_connect();
    $sale_start_date = "9-1-2014";
	$query = "SELECT ord.filled_by, SUM(oo.quantity * o.price) AS total FROM shopping_offers_in_orders AS oo LEFT JOIN shopping_offers AS o ON o.offer_id = oo.offer_id LEFT JOIN shopping_orders AS ord ON ord.order_id = oo.order_id WHERE ord.cancelled = 0 AND date_ordered >= STR_TO_DATE(\"$sale_start_date\", \"%c-%e-%Y\") GROUP BY ord.filled_by";
    
	$result = mysql_query($query, $link);
	return fetch_all_from_result($result);
}

function get_shopping_items()
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_items";

	$result = mysql_query($query, $link);
	return fetch_all_from_result($result);
}

function get_item_info($item_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_items WHERE item_id = $item_id";

	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function add_item($item_description, $item_remaining)
{
	$link = db_connect();
	$query = "INSERT INTO shopping_items (`desc`, remaining) VALUES(\"$item_description\", $item_remaining)";

	$result = mysql_query($query, $link);
	return True; // we should really be checking to see if this succeeded
}

function update_item($item_id, $item_description, $item_remaining)
{
	$link = db_connect();
	$query = "UPDATE shopping_items SET `desc` = \"$item_description\", remaining = $item_remaining WHERE item_id = $item_id";

	$result = mysql_query($query, $link);
	return True; // we should really be checking to see if this succeeded
}

function get_shopping_offers()
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_offers";

	$result = mysql_query($query, $link);
	return fetch_all_from_result($result);
}

function get_offer_info($offer_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_offers WHERE offer_id = $offer_id";

	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function add_offer($offer_description, $offer_keywords, $offer_price)
{
	$link = db_connect();
	$query = "INSERT INTO shopping_offers (description, keywords, price) VALUES(\"$offer_description\", \"$offer_keywords\", $offer_price)";

	$result = mysql_query($query, $link);
	return mysql_insert_id(); // we should really be checking to see if this succeeded
}

function update_offer($offer_id, $offer_description, $offer_keywords, $offer_price)
{
	$link = db_connect();
	$query = "UPDATE shopping_offers SET description = \"$offer_description\", keywords = \"$offer_keywords\", price = $offer_price WHERE offer_id = $offer_id";
    
	$result = mysql_query($query, $link);
	return True; // we should really be checking to see if this succeeded
}

function add_item_to_offer($offer_id, $item_id, $item_quantity)
{
	$link = db_connect();
	$query = "INSERT INTO shopping_items_in_offers (offer_id, item_id, quantity) VALUES($offer_id, $item_id, $item_quantity)";

	$result = mysql_query($query, $link);
    return True; // we should really be checking to see if this succeeded
}

function get_shopping_groups()
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_display_groups ORDER BY display_order";

	$result = mysql_query($query, $link);
	return fetch_all_from_result($result);
}

function get_group_info($group_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_display_groups WHERE group_id = $group_id";

	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function add_group($group_description, $group_image, $display_order)
{
	$link = db_connect();
	$query = "INSERT INTO shopping_display_groups (group_description, group_image, display_order) VALUES(\"$group_description\", \"$group_image\", $display_order)";

	$result = mysql_query($query, $link);
	return mysql_insert_id(); // we should really be checking to see if this succeeded
}

function update_group($group_id, $group_description, $group_image, $display_order)
{
	$link = db_connect();
	$query = "UPDATE shopping_display_groups SET group_description = \"$group_description\", group_image = \"$group_image\", display_order = $display_order WHERE group_id = $group_id";
    
	$result = mysql_query($query, $link);
	return True; // we should really be checking to see if this succeeded
}

function get_offers()
{
	$link = db_connect();
	$query = "SELECT * FROM shopping_offers";
    
    $result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_offers_in_group($group_id)
{
	$link = db_connect();
    $query = "SELECT o.offer_id, o.description, og.link_description, og.offer_display_order FROM shopping_offers_in_groups AS og LEFT JOIN shopping_offers AS o ON o.offer_id = og.offer_id WHERE group_id = $group_id ORDER BY offer_display_order";
    $result = mysql_query($query, $link);
    return fetch_all_from_result($result);
}

function add_offer_to_group($group_id, $offer_id, $link_description, $offer_display_order)
{
	$link = db_connect();
	$query = "INSERT INTO shopping_offers_in_groups (group_id, offer_id, link_description, offer_display_order) VALUES($group_id, $offer_id, \"$link_description\", $offer_display_order)";

	$result = mysql_query($query, $link);
    return True; // we should really be checking to see if this succeeded
}

function get_current_officers()
{
	$link = db_connect();

	$query="SELECT * FROM current_officers ORDER BY display_order";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}
?>
