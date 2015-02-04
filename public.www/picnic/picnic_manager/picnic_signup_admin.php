<?php
include_once "../picnic_lib.php";

$current_semester = get_current_semester();
$current_year = get_current_year();

$grad_price = get_grad_student_price();
$faculty_staff_price = get_faculty_staff_price();
$food_discount = get_food_discount();
$signup_discount = get_signup_discount();
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
<title>SIAM Picnic Manager</title>
<script type="text/javascript">
function get_total()
{
	var grad = document.getElementById('grad_student').checked;
	var discount = document.getElementById('discount').checked;
	var attend = document.getElementById('attending').value - document.getElementById('under_10').value;
	var bringing = document.getElementById('dish').value;
	var food_discount = 0;
	if(bringing > 0)
	{
		food_discount = <?php print($food_discount);?>;
	}
	var cost = 0;
	var signup_discount = 0;
	
	if(discount)
	{
		signup_discount = <?php print($signup_discount);?>;
	}
	
	if(grad)
	{
		cost = attend * (<?php print($grad_price);?> - food_discount - signup_discount);
	}
	else
	{
		cost = attend * (<?php print($faculty_staff_price);?> - food_discount - signup_discount);
	}
	
	document.getElementById('total').innerHTML = "$" + cost + ".00";
}
</script>
</head>
<body style="text-align: center;" onload="javascript:get_total();">
<div style="width: 700px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="/~siam/img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Picnic Manager</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 500px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
$username = get_str_value($_REQUEST, "username");
	
// check if this person has already signed up
if(has_signed_up($username))
{
	// display a message saying that they have already signed up
?>
<div>This person has already signed up for the picnic. If you need to change any of the information they submitted, please use the update form from the <a href="picnic_manager.php">main page</a>.</div>
<?php
}
else // they have not signed up yet
{
?>
<?php
	$link = db_connect();
	if(isset($_POST['picnic_form']) && $_POST['picnic_form'] == 'submit_ok')
	{
		// they are submitting the form to signup
        $first_name = get_str_value($_REQUEST, "first_name");
        $last_name = get_str_value($_REQUEST, "last_name");
		$graduate = get_int_value($_REQUEST, "grad_student");
		$num_attending = get_int_value($_REQUEST, "attending");
		$num_under_10 = get_int_value($_REQUEST, "under_10");
		$drink = get_int_value($_REQUEST, "drink");
		$dish = get_int_value($_REQUEST, "dish");
		$dish_desc = get_str_value($_REQUEST, "dish_description");
		$signup_discount = get_int_value($_REQUEST, "discount") ? get_signup_discount() : 0;
        
        $food_discount = 0;
        if($dish > 0)
        {
            $food_discount = get_food_discount();
        }
        
        $lawn_game = get_int_value($_REQUEST, "lawn_game");
        $dessert_judge = get_int_value($_REQUEST, "dessert_judge");
        $side_judge = get_int_value($_REQUEST, "side_judge");
		
		add_picnic_signup_admin($first_name, $last_name, $username, $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge, get_int_value($_REQUEST, "discount"));
		
		$cost = 0;
		$attend = $num_attending - $num_under_10;
		
		if($graduate)
		{
			$cost = $attend * ($grad_price - $food_discount - $signup_discount);
		}
		else
		{
			$cost = $attend * ($faculty_staff_price - $food_discount - $signup_discount);
		}
        
        $officers = get_current_officers();
        
        $officer_list = "";
        
        foreach($officers as $officer)
        {
            $officer_list .= "$officer[first_name] $officer[last_name] $officer[office_number]\n";
        }
		
		// possibly we could add an option to suppress this if needed
		// send the user an email confirming their signup
		$message = <<<EOH
You have successfully signed up for the $current_semester picnic. If you have any questions or need to change any of the information you submitted when signing up, please contact Dr. Rebholz (O-118) or one of the SIAM officers.

Mark your calendar for the picnic.
Where: Clemson Outdoor Labs
When: Friday, April 18 from 5:00 PM - 8:00 PM

Directions are available at the following websites.

Google Maps: http://maps.google.com/maps?gl=us&ie=UTF8&hl=en&view=map&cid=9202869781767533565&iwloc=A
Clemson: http://www.clemson.edu/centers-institutes/outdoor-lab/center/directions.html

You can submit your payment of \$$cost.00 to Dr. Rebholz (O-118) or any of the officers listed below as well. If you are paying by check, please make out the check to SIAM.

$officer_list
EOH;

		$message = wordwrap($message,70);
	 
		$headers = "From: SIAM <siam@clemson.edu>";
		
		// uncomment this to send them an email
		$output = mail($username . "@clemson.edu", "SIAM " . ucfirst($current_semester) . " Picnic signup confirmation", $message, $headers);
?>
<p>You have successfully signed up someone for the <?php print(ucfirst($current_semester)); ?> Picnic. They should receive an email shortly confirming the signup. If they have paid already, mark that they have paid by clicking their username from the <a href="picnic_manager.php">main page</a>.</p>
<?php
	}
	else
	{
		// display the signup form
?>
<span style="font-size: 20pt; color: #FF6633; font-weight: bold; font-family: Arial;">Signup</span>
<div>
<ul style="list-style: none;">
	<li>If you bring a food dish the cost is:
		<ul style="list-style: none;">
			<li>$<?php print($faculty_staff_price - $food_discount);?>/person (faculty, staff and family)</li>
			<li>$<?php print($grad_price - $food_discount);?>/person (graduate students and family)</li>
		</ul>
	</li>
	<li>Otherwise the cost is:
		<ul style="list-style: none;">
			<li>$<?php print($faculty_staff_price);?>/person (faculty, staff and family)</li>
			<li>$<?php print($grad_price);?>/person (graduate students and family)</li>
		</ul>
	</li>
	<li>*Children under 10 are free</li>
	<li>* -$<?php print($signup_discount); ?> for signing up early</li>
</ul>
</div>
<form method="POST" action="picnic_signup_admin.php">
<div style="text-align: center;">
<table style="text-align: left; margin-left: auto; margin-right: auto; position: relative;" cellspacing="8">
	<tr>
		<td>Username of person attending:</td>
		<td><input type="text" id="username" name="username" value="" size="10"></td>
	</tr>
    <tr>
        <td>First name:</td>
        <td><input type="text" name="first_name" size="25"></td>
    </tr>
    <tr>
        <td>Last name:</td>
        <td><input type="text" name="last_name" size="25"></td>
    </tr>
	<tr>
		<td>Graduate student:</td>
		<td><input type="checkbox" id="grad_student" name="grad_student" value="1" onchange="javascript:get_total();"></td>
	</tr>
	<tr>
		<td>Discount:</td>
		<td><input type="checkbox" id="discount" name="discount" value="1" onchange="javascript:get_total();"></td>
	</tr>
	<tr>
		<td>Total number attending:</td>
		<td><input type="text" size="2" id="attending" name="attending" value="1" onchange="javascript:get_total();"></td>
	</tr>
	<tr>
		<td>How many attending are under age 10:</td>
		<td><input type="text" size="2" id="under_10" name="under_10" value="0" onchange="javascript:get_total();"></td>
	</tr>
	<tr>
		<td>I am drinking beer or wine:</td>
		<td>
<?php
		$drink_prefs = get_drink_preferences();
		$drink_ids = array();
		$drink_descriptions = array();
		
		foreach($drink_prefs as $pref)
		{
			$drink_ids[] = $pref["id"];
			$drink_descriptions[] = $pref["description"];
		}
		
		print(get_html_dropdown($drink_ids, $drink_descriptions, "drink", 0));
?>
		</td>
	</tr>
	<tr>
		<td>I am bringing:</td>
		<td>
<?php
		$dish_options = get_dish_options();
		$dish_ids = array();
		$dish_categories = array();

		foreach($dish_options as $option)
		{
			$dish_ids[] = $option["id"];
			$dish_categories[] = $option["category"];
		}
		
		print(get_html_dropdown($dish_ids, $dish_categories, "dish", 0, "id=\"dish\" onchange=\"javascript:get_total();\""));
?>
		</td>
	</tr>
	<tr>
		<td colspan="2">Describe what you are bringing as specifically as possible.</td>
	</tr>
    <tr>
        <td colspan="2"><textarea name="dish_description" style="height: 50px; width: 100%;"></textarea></td>
    </tr>
    <tr>
        <td>I am bringing a SAFE lawn game:</td>
        <td><input type="checkbox" name="lawn_game" value="1"></td>
    </tr>
    <tr>
        <td style="vertical-align: top;">I am willing to be a:</td>
        <td>
            <div><input type="checkbox" name="dessert_judge" id="dessert_judge" value="1"> <label for="dessert_judge">dessert judge</label></div>
            <div><input type="checkbox" name="side_judge" id="side_judge" value="1"> <label for="side_judge">side dish judge</label></div>
        </td>
    </tr>
    <tr>
        <td colspan="2">* note that they can't be a judge for the same category they are bringing something for</td>
    </tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td style="text-align: left;">Total Cost:</td>
		<td style="text-align: right;"><span id="total" style="font-weight: bold; font-size: 15px;">$0.00</span></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;"><input type="submit" value="Submit"><input type="hidden" name="picnic_form" value="submit_ok"></td>
	</tr>
</table>
</div>
</form>
<?php
	}
}
?>
<div style="text-align: left; clear: right;"><a href="picnic_manager.php">Return to Picnic Manager</a></div>
</div>
</div>
</div>
</body>
</html>
