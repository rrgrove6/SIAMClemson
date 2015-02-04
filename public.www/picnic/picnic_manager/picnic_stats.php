<?php
include_once "../picnic_lib.php";
?>
<!doctype html>
<html>
<head>
	<title>Picnic Stats</title>
</head>
<body>
<?php
if(get_str_value($_REQUEST, "func") == "picnic_totals")
{
?>
<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">Number Attending:</div>
<?php
	$attendance_data = get_attendance_data(get_current_semester(), get_current_year());
	
	printf("Total: %s<br>\nUnder ten: %s<br><br>", $attendance_data["total_attending"], $attendance_data["total_under_10"]);
?>
<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">Payment:</div>
<?php
	$payment_data = get_payment_data(get_current_semester(), get_current_year());
	
	foreach($payment_data as $payment)
	{
		printf("%s: \$%s.00<br>", $payment["paid"], $payment["total"]);
	}
?>
<br>
<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">Officer Collection Totals:</div>
<?php
	$collection_data = get_officer_collection_summary(get_current_semester(), get_current_year());
	
	foreach($collection_data as $collection)
	{
		printf("%s: \$%s.00<br>", $collection["officer_username"], $collection["final_price"]);
	}
?>
<br>
<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">Dishes:</div>
<?php
	$dish_list = get_dishes_being_brought(get_current_semester(), get_current_year());
	
	$cur_cat_id = -1;
	foreach($dish_list as $dish)
	{
		if($dish['id'] != $cur_cat_id)
		{
			if($cur_cat_id != -1)
			{
				print("</ol>\n");
			}
			print("<div style=\"font-family: Arial; font-weight: bold; font-size: 15pt;\">$dish[category]</div>\n<ol>");
			$cur_cat_id = $dish['id'];
		}
		printf("<li>%s</li>", $dish['dish_desc']);
	}
	
	if($cur_cat_id != -1)
	{
		print("</ol>");
	}
	else
	{
		print("no one has signed up to bring any dishes yet");
	}
?>
<br>
<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">Judges:</div>
<div style=\"font-family: Arial; font-weight: bold; font-size: 15pt;\">Side Dishes</div>
<?php
	$judge_list = get_side_dish_judges(get_current_semester(), get_current_year());
	
    $judge_html = "";
    
    foreach($judge_list as $judge)
    {
        $judge_html .= "\t<li>$judge[first_name] $judge[last_name] ($judge[user_name])</li>\n";
    }
    
	if(count($judge_list) > 0)
	{
		print("<ul>\n\t$judge_html\n</ul>");
	}
	else
	{
		print("no one has signed up to be a side dish judge yet");
	}
?>
<div style=\"font-family: Arial; font-weight: bold; font-size: 15pt;\">Desserts</div>
<?php
	$judge_list = get_dessert_judges(get_current_semester(), get_current_year());
	
    $judge_html = "";
    
    foreach($judge_list as $judge)
    {
        $judge_html .= "\t<li>$judge[first_name] $judge[last_name] ($judge[user_name])</li>\n";
    }
    
	if(count($judge_list) > 0)
	{
		print("<ul>\n\t$judge_html\n</ul>");
	}
	else
	{
		print("no one has signed up to be a side dish judge yet");
	}
?>
<br>
<!--<div style="font-family: Arial; font-weight: bold; font-size: 15pt;">People needing rides:</div>-->
<?php
	$ride_list = get_people_needing_rides(get_current_semester(), get_current_year());
	
	foreach($ride_list as $ride)
	{
		printf("<div>$ride[user_name]</div>");
	}
}
if(get_str_value($_REQUEST, "func") == "picnic_report")
{
    $cur_semester = ucfirst(get_current_semester());
    $cur_year = get_current_year();
?>
<div style="text-align: center; font-size: 25px; font-weight: bold;"><?php print("$cur_semester $cur_year"); ?> Picnic Signup Report</div>
<table style="margin: 10px auto 20px auto;">
	<tr>
        <td style="font-weight: bold; width: 50px; text-align: center;">Paid</td>
        <td style="text-align: center; font-weight: bold; min-width: 200px;">Name</td>
        <td style="text-align: center; font-weight: bold;">Username</td>
        <td style="text-align: center; font-weight: bold; width: 100px;">Total Attending</td>
        <td style="text-align: center; font-weight: bold; width: 100px;">Children</td>
        <td style="text-align: center; font-weight: bold; width: 30px;">Food</td>
        <td style="text-align: center; font-weight: bold; width: 100px;">Total Cost</td>
        <td style="text-align: center; font-weight: bold; width: 80px;">Discount</td>
    </tr>
<?php

function get_check($paid)
{
	if($paid == "1")
	{
		return "<img src=\"/~siam/img/check.png\">";
	}
	else
	{
		return "<div style=\"border: solid 2px black; height: 25px; width: 25px; margin-left: auto; margin-right: auto;\">&nbsp;</div>";
	}
}
	
	$orders_list = get_list_of_orders(get_current_semester(), get_current_year());
	
	foreach($orders_list as $order)
	{
        if($order["num_attending"] != 0)
        {
            print("<tr style=\"padding-bottom: 1px;\"><td style=\"height: 25px; text-align: center;\">" . get_check($order["paid"]) . "</td><td style=\"padding-left: 40px;\">$order[last_name], $order[first_name]</td><td style=\"padding-left: 20px;\">$order[user_name]</td><td style=\"text-align: center;\">$order[num_attending]</td><td style=\"text-align: center;\">$order[children_count]</td><td style=\"text-align: center;\">" .get_check($order["dish"]) . "</td><td style=\"padding-right: 25px; text-align: right;\">\$$order[total_cost].00</td><td style=\"text-align: center;\">" .get_check($order["discount"]) . "</td></tr><tr><td style=\"border-bottom: solid black 1px; height: 6px; font-size: 6px;\" colspan=\"8\">&nbsp;</td></tr>\n");
        }
	}
?>
</table>
<?php
}
?>
</body>
</html>