<?php
include_once "shopping_lib.php";

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "revenue")
{	
	print("<p>\$" . get_sales_revenue() . "</p>");
	
	$totals = get_collection_totals();
	
	print("<table>\n\t<tr><td>Username</td><td>Collected</td></tr>\n");
	
	foreach($totals as $total)
	{
		$username = strlen($total["filled_by"]) > 0 ? $total["filled_by"] : "uncollected";
		print("<tr><td>$username</td><td>\$$total[total]</td></tr>");
	}
	
	print("</table>");
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "volume")
{
    // get total of items (not orders)
    $totals = get_sales_volume();
	
	print("<table>\n\t<tr><td>Description</td><td>Number Sold</td></tr>\n");
	
	foreach($totals as $item)
	{
		print("\t<tr><td>$item[desc]</td><td style=\"text-align: center;\">$item[number_sold]</td></tr>\n");
	}
	
	print("</table>");
}
?>