<html>
<body>
<?php
include "common/database.php";

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "revenue")
{
	$link = db_connect();

	$query="Select sum(it.price * io.quantity) as total From items_in_orders as io Inner Join items as it on io.item_id = it.item_id Inner Join orders as ord on ord.order_id = io.order_id where ord.order_id > 0 AND date_ordered > \"2010-11-01\" AND cancelled = 0";
	$result = mysql_query($query, $link);
	
	$row = mysql_fetch_assoc($result);
	
	print("<p>\$" . $row['total'] . "</p>");
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "totals")
{
	$link = db_connect();

	$query="Select it.desc, sum(io.quantity) as quantity From items_in_orders as io Inner Join items as it on io.item_id = it.item_id Inner Join orders as ord on ord.order_id = io.order_id where ord.order_id > 0 AND date_ordered > \"2010-11-01\" AND cancelled = 0 Group by it.item_id Order by it.display_order";
	$result = mysql_query($query, $link);
	
	print("<table>\n\t<tr><td>Description</td><td>Quantity</td></tr>\n");
	
	while($row = mysql_fetch_assoc($result))
	{
		print("\t<tr><td>$row[desc]</td><td style=\"text-align: center;\">$row[quantity]</td></tr>\n");
	}
	
	print("</table>");
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "shirt_votes")
{
	$link = db_connect();

	//get design stats
	$query="SELECT design_pref, COUNT(design_pref) AS votes FROM shirt_2010_voting GROUP BY design_pref";
	$result = mysql_query($query, $link);
	
	print("<table><tr><td>Design</td><td>Votes</td></tr>");
	while($row = mysql_fetch_assoc($result))
	{
		print("<tr><td>$row[design_pref]</td><td>$row[votes]</td></tr>");
	}
	print("</table>");
	
	//get style stats
	$query="SELECT style_pref, COUNT(style_pref) AS votes FROM shirt_2010_voting GROUP BY style_pref";
	$result = mysql_query($query, $link);
	
	print("<table><tr><td>Style</td><td>Votes</td></tr>");
	while($row = mysql_fetch_assoc($result))
	{
		print("<tr><td>$row[style_pref]</td><td>$row[votes]</td></tr>");
	}
	print("</table>");
	
	//get color combo preferences
	$colors = array("orange_white", "purple_white", "orange_black", "white_black", "blue_white");

	print("<table>\n\t\t<tr>\n\t\t<td>color</td>\n\t\t<td>1st</td>\n\t\t<td>2nd</td>\n\t\t<td>3rd</td>\n\t\t<td>4th</td>\n\t\t<td>5th</td>\n\t</tr>\n");
	foreach($colors as $color)
	{
		print("\t<tr>\n\t\t<td>$color</td>\n");
		for($i = 1; $i <= 5; $i++)
		{
			$query="SELECT COUNT($color) AS votes FROM shirt_2010_voting WHERE $color = $i";
			$result = mysql_query($query, $link);
			$row = mysql_fetch_assoc($result);
			
			print("\t\t<td style=\"text-align: center;\">$row[votes]</td>\n");
		}
		print("\t</tr>\n");
	}
	print("</table>\n");
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "election_results")
{
	$link = db_connect();
    
    $query = "SELECT team_name, vote_total FROM officer_voting_totals ORDER BY team_name";
    
	$result = mysql_query($query, $link);
	
	print("<table><tr><td>Team</td><td>Votes</td></tr>");
	while($row = mysql_fetch_assoc($result))
	{
		print("<tr><td>$row[team_name]</td><td style=\"text-align: center;\">$row[vote_total]</td></tr>");
	}
	print("</table>");
}
?>
</body>
</html>