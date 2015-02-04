<?php
include_once "../picnic_lib.php";

$officer_username = get_str_value($_SERVER, "REMOTE_USER");

$grad_price = get_grad_student_price();
$faculty_staff_price = get_faculty_staff_price();
$food_discount = get_food_discount();
$signup_discount = get_signup_discount();

if(get_int_value($_POST, "submit_update") == 1)
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
	$paid = get_int_value($_REQUEST, "paid");
	$discount = get_int_value($_REQUEST, "discount");
    
    $lawn_game = get_int_value($_REQUEST, "lawn_game");
    $dessert_judge = get_int_value($_REQUEST, "dessert_judge");
    $side_judge = get_int_value($_REQUEST, "side_judge");
	
	//update signup
	update_picnic_signup(get_current_semester(), get_current_year(), $officer_username, $first_name, $last_name, get_str_value($_REQUEST, "id"), $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge, $paid, $discount);

	// send back redirect to picnic_manager page
	redirect("http://people.clemson.edu/~siam/picnic/picnic_manager/picnic_manager.php");
}
else //display the update form
{
	$username = get_str_value($_REQUEST, "id");
	if($username != "")
	{
		// get signup details
		$signup_details = get_signup_details($username, get_current_semester(), get_current_year());
		
		if(count($signup_details) > 0)
		{
            $first_name = $signup_details["first_name"];
            $last_name = $signup_details["last_name"];
			$username = $signup_details["user_name"];
			$signup = $signup_details["signup"];
			$grad = intval($signup_details["grad_student"]);
			$num_attending = intval($signup_details["num_attending"]);
			$num_under_10 = intval($signup_details["num_under_10"]);
			$drink = intval($signup_details["drink"]);
			$dish = intval($signup_details["dish"]);
			$dish_desc = $signup_details["dish_desc"];
			$lawn_game = intval($signup_details["lawn_game"]);
            $dessert_judge = intval($signup_details["dessert_judge"]);
            $side_judge = intval($signup_details["side_judge"]);
			$paid = intval($signup_details["paid"]);
			$updated_by = $signup_details["updated_by"];
			$last_updated = $signup_details["last_updated"];
			$discount = intval($signup_details["discount"]);
		}
	}
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
<form name="update" method="POST" action="update_person.php">
<div style="text-align: left; padding-bottom: 20px;">Picnic signup for <span style="font-weight: bold; font-size: 22px; color: #ff6206;"><?php print($username); ?></span> placed on <?php print($signup); ?></div>
<div>
<div style="text-align: center;">
<table style="text-align: left; margin-left: auto; margin-right: auto; position: relative;" cellspacing="8">
    <tr>
        <td>First name:</td>
        <td><input type="text" name="first_name" size="25" value="<?php print($first_name); ?>"></td>
    </tr>
    <tr>
        <td>Last name:</td>
        <td><input type="text" name="last_name" size="25" value="<?php print($last_name); ?>"></td>
    </tr>
	<tr>
		<td>Graduate student:</td>
		<td><input type="checkbox" id="grad_student" name="grad_student" value="1" onchange="javascript:get_total();" <?php print(($grad == 1) ? "checked" : ""); ?>></td>
	</tr>
	<tr>
		<td>Discount:</td>
		<td><input type="checkbox" id="discount" name="discount" value="1" onchange="javascript:get_total();" <?php print(($discount == 1) ? "checked" : ""); ?>></td>
	</tr>
	<tr>
		<td>Total number attending:</td>
		<td><input type="text" size="2" id="attending" name="attending" value="<?php print($num_attending); ?>" onchange="javascript:get_total();"></td>
	</tr>
	<tr>
		<td>How many attending are under age 10:</td>
		<td><input type="text" size="2" id="under_10" name="under_10" value="<?php print($num_under_10); ?>" onchange="javascript:get_total();"></td>
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
		
		print(get_html_dropdown($dish_ids, $dish_categories, "dish", $dish, "id=\"dish\" onchange=\"javascript:get_total();\""));
?>
		</td>
	</tr>
	<tr>
		<td colspan="2">Describe what you are bringing as specifically as possible.</td>
	</tr>
	<tr>
		<td colspan="2"><textarea name="dish_description" rows="3" cols="42"><?php print($dish_desc); ?></textarea></td>
	</tr>
    <tr>
        <td>I am bringing a SAFE lawn game:</td>
        <td><input type="checkbox" name="lawn_game" value="1" <?php print(($lawn_game == 1) ? "checked" : ""); ?>></td>
    </tr>
    <tr>
        <td style="vertical-align: top;">I am willing to be a:</td>
        <td>
            <div><input type="checkbox" name="dessert_judge" id="dessert_judge" value="1" <?php print(($dessert_judge == 1) ? "checked" : ""); ?>> <label for="dessert_judge">dessert judge</label></div>
            <div><input type="checkbox" name="side_judge" id="side_judge" value="1" <?php print(($side_judge == 1) ? "checked" : ""); ?>> <label for="side_judge">side dish judge</label></div>
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
		<td style="text-align: left;"><input type="submit" value="Update"><input type="hidden" name="id" value="<?php print(isset($_REQUEST['id']) ? $_REQUEST['id'] : 0); ?>"><input type="hidden" name="submit_update" value="1"></td>
		<td style="text-align: right;">Paid: <input type="checkbox" name="paid" value="1" <?php print(($paid == 1) ? "checked" : ""); ?>></td>
	</tr>
</table>
</div>
</div>
<div style="text-align: left; clear: right;"><a href="picnic_manager.php">Return to Picnic Manager</a></div>
<?php
if($updated_by != "")
{
	print("<div style=\"text-align: right; padding-top: 10px; color: #6666cc\">This order was last updated by $updated_by on $last_updated</div>");
}
?>
</form>
</div>
</div>
</div>
</body>
</html>
<?php
} //end display update form
?>