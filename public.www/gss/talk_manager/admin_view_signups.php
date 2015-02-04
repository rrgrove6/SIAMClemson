<?php
include_once "../../common/template.php";
include_once "../../common/general_funcs.php";
include_once "../gss_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_admin_header(); ?>
<?php
	$submitted_id = get_int_value($_REQUEST, "talk_id", -1);
	if($submitted_id != -1)
	{
		$talk = get_talk_info($submitted_id);
	}
	else
	{
		$talk = get_current_talk();
	}
	
	// get all talk titles and ids
	$talk_data = get_all_talks_list();
	$ids = array();
	$titles = array();
	
	while($row = array_shift($talk_data))
	{
		$ids[] = $row["talk_id"];
		$dates[] = $row["talk_date"];
	}
?>
<div style="margin-top: 25px;">
<form action="admin_view_signups.php" method="POST">
<?php print(get_html_dropdown($ids, $dates, "talk_id", $talk["talk_id"])); ?>
<input type="submit" value="View Signup Details">
</form>
</div>
<p style="color: #FF6633; font-size: 20px; font-weight: bold;">What you should order:</p>
<?php
    $people_count = 0;
    function pizza_pref_greater_than($LHS, $RHS)
    {
        if($LHS[1] == $RHS[1])
        {
            // return based on preference order: pepperoni, sausage, cheese
            if($LHS[0] == "Pepperoni")
            {
                return 1;
            }
            else if($RHS[0] == "Pepperoni")
            {
                return -1;
            }
            
            if($LHS[0] == "Sausage")
            {
                return 1;
            }
            else if($RHS[0] == "Sausage")
            {
                return -1;
            }
        }
        else
        {
            return ($LHS[1] > $RHS[1]) ? 1 : -1;
        }
    }

    $toppings_list = get_signup_topping_summary($talk["talk_id"]);
    $topping_count = array();
    
    while($row = array_shift($toppings_list))
    {
        $people_count += $row["number"];
        $topping_count[$row["description"]] = $row["number"];
    }
	
    $pizza_count = ceil($people_count / 4.0);

    // reallocate any choices to be split evenly amoung the meat options
    $topping_count["Pepperoni"] += $topping_count["Any"] / 2.0;
    $topping_count["Sausage"] += $topping_count["Any"] / 2.0;
    
    $pizza_order["Cheese"] = intval(intval($topping_count["Cheese"]) / 4);
    $pizza_order["Pepperoni"] = intval(intval($topping_count["Pepperoni"]) / 4);
    $pizza_order["Sausage"] = intval(intval($topping_count["Sausage"]) / 4);
    
    $pizzas_ordered_so_far = $pizza_order["Cheese"] + $pizza_order["Pepperoni"] + $pizza_order["Sausage"];
    
    // sort the toppings in order of remaining requests (and use the preference order to break ties)
    $topping_remainders = array();
    
    $topping_remainders[] = array("Cheese", $topping_count["Cheese"] % 4);
    $topping_remainders[] = array("Pepperoni", $topping_count["Pepperoni"] % 4);
    $topping_remainders[] = array("Sausage", $topping_count["Sausage"] % 4);
    
    usort($topping_remainders, "pizza_pref_greater_than");
    
    for($i = 0; $i < ($pizza_count - $pizzas_ordered_so_far); $i++)
    {
        $current_topping = array_pop($topping_remainders);
        $pizza_order[$current_topping[0]] += 1;
    }
    
    print("You should order $pizza_count pizzas.<br>");
    print("<ul>");
    print("\t<li>Cheese: <span style=\"font-weight: bold;\">$pizza_order[Cheese]</span></li>");
    print("\t<li>Pepperoni: <span style=\"font-weight: bold;\">$pizza_order[Pepperoni]</span></li>");
    print("\t<li>Sausage: <span style=\"font-weight: bold;\">$pizza_order[Sausage]</span></li>");
    print("</ul>");
?>
<p style="color: #FF6633; font-size: 20px; font-weight: bold;">Stats:</p>
<p>The following table provides a summary of the people who have signed up to attend the talk on <?php print(date("M j", strtotime($talk["date"])));?>. This includes the preferences of the people who signed up late.</p>
<table cellspacing="0" align="center" style="margin-bottom: 20px;">
	<tr>
		<td style="font-weight: bold; text-align: center; border-bottom: 2px solid black; border-right: 1px solid black; padding-right: 8px;">Topping</td>
		<td style="font-weight: bold; text-align: center; border-bottom: 2px solid black; padding-left: 8px;">Number</td>
	</tr>
<?php
    $toppings_list = get_signup_topping_summary($talk["talk_id"]);

	while($row = array_shift($toppings_list))
	{
		print("<tr><td style=\"border-right: 1px solid black; padding-right: 8px;\">" . $row["description"] . "</td><td style=\"text-align: center; padding-left: 8px;\">" . $row["number"] . "</td></tr>");
	}
?>
</table>
<?php
    print("<div>Total number signed up: $people_count</div>");
?>
<p style="color: #FF6633; font-size: 20px; font-weight: bold;">Signed up on time:</p>
<div>
<?php
    $email_list = get_signup_email_list($talk["talk_id"]);
    
    print(implode(", ", $email_list));
?>
</div>
<p style="color: #FF6633; font-size: 20px; font-weight: bold;">Signed up late:</p>
<div>
<?php
    $email_list = get_late_signup_email_list($talk["talk_id"]);
    
    print(implode(", ", $email_list));
?>
</div>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>