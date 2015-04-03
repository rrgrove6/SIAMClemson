<?php
include_once "../common/template.php";
include_once "picnic_lib.php";

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
<link rel="stylesheet" href="../styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
<script type="text/javascript">
function get_total()
{
    var grad = document.getElementById('grad_student').checked;
    var attend = document.getElementById('attending').value - document.getElementById('under_10').value;
    var bringing = document.getElementById('dish').value;
    var food_discount = 0;
    if(bringing > 0)
    {
        food_discount = <?php print($food_discount);?>;
    }
    var cost = 0;
    var signup_discount = <?php print(eligible_for_signup_discount() ? $signup_discount : 0); ?>;
    
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
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 700px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
$username = get_str_value($_SERVER, "REMOTE_USER");
    
// check if this person has already signed up
if(has_signed_up($username))
{
    // display a message saying that they have already signed up
?>
<p>You are logged in as <span style="font-weight: bold; font-size: 20px; color: #F67733;"><?php print($username); ?></span>. If this is not your username then logout and return to this page.</p>
<p>You have already signed up for the picnic. If you need to change any of the information you submitted, please contact Dr. Rebholz (Martin O-118) or one of the SIAM officers listed below.</p>
<table style="margin: 10px auto; border-collapse: collapse; border-spacing: 0px;">
    <tr>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Name</td>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Office</td>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Email</td>
    </tr>
<?php
    $officers = get_current_officers();
    
    foreach($officers as $officer)
    {
        print <<<END
    <tr>
        <td style="padding-right: 20px;">$officer[first_name] $officer[last_name]</td>
        <td style="padding-right: 20px;">$officer[office_number]</td>
        <td>$officer[username]</td>
    </tr>
END;
    }
?>
</table>
<?php
}
else // they have not signed up yet
{
?>
<?php
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
        $signup_discount = 0;
        if(eligible_for_signup_discount())
        {
            $signup_discount = get_signup_discount();
        }
        
        $food_discount = 0;
        if($dish > 0)
        {
            $food_discount = get_food_discount();
        }
        
        $lawn_game = get_int_value($_REQUEST, "lawn_game");
        $dessert_judge = get_int_value($_REQUEST, "dessert_judge");
        $side_judge = get_int_value($_REQUEST, "side_judge");

        add_picnic_signup($first_name, $last_name, $username, $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge);
        
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
        
        // send the user an email confirming their signup
        $message = <<<EOH
You have successfully signed up for the Spring picnic. If you have any questions or need to change any of the information you submitted when signing up, please contact one of the SIAM officers or Dr. Rebholz.

Mark your calendar for the picnic.
Where: Clemson Outdoor Lab
When: Friday, April 17 from 5:00 PM - 7:30 PM

Directions are available at the following websites.

Google Maps: http://maps.google.com/maps?gl=us&ie=UTF8&hl=en&view=map&cid=9202869781767533565&iwloc=A
Clemson: http://www.clemson.edu/centers-institutes/outdoor-lab/center/directions.html

You can submit your payment of \$$cost.00 to Dr. Rebholz (O-118) or any of the officers listed below as well. If you are paying by check, please make out the check to SIAM.

$officer_list
EOH;

        $message = wordwrap($message,70);
     
        $headers = "From: SIAM <siam@clemson.edu>";

        $output = mail($username . "@clemson.edu", "SIAM " . ucfirst($current_semester) . " Picnic signup confirmation", $message, $headers);
?>
<p>Thank you for signing up for the <?php print(ucfirst($current_semester)); ?> Picnic. You should receive an email shortly confirming your signup. You can submit your payment to Dr. Rebholz (O-118) or any of the SIAM officers listed below.  If you are paying by check, please make out the check to SIAM.</p>
<table style="margin: 10px auto; border-collapse: collapse; border-spacing: 0px;">
    <tr>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Name</td>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Office</td>
        <td style="font-weight: bold; border-bottom: 2px solid black;">Email</td>
    </tr>
<?php
    $officers = get_current_officers();
    
    foreach($officers as $officer)
    {
        print <<<END
    <tr>
        <td style="padding-right: 20px;">$officer[first_name] $officer[last_name]</td>
        <td style="padding-right: 20px;">$officer[office_number]</td>
        <td>$officer[username]</td>
    </tr>
END;
    }
?>
</table>
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
            <li>$<?php print($faculty_staff_price - $food_discount - $signup_discount);?>/person (faculty, staff and family)</li>
            <li>$<?php print($grad_price - $food_discount - $signup_discount);?>/person (graduate students and family)</li>
        </ul>
    </li>
    <li>Otherwise the cost is:
        <ul style="list-style: none;">
            <li>$<?php print($faculty_staff_price - $signup_discount);?>/person (faculty, staff and family)</li>
            <li>$<?php print($grad_price - $signup_discount);?>/person (graduate students and family)</li>
        </ul>
    </li>
    <li>*Children under 10 are free</li>
</ul>
</div>
<form method="POST" action="picnic.php">
<div style="text-align: center;">
<table style="text-align: left; margin-left: auto; margin-right: auto; position: relative;" cellspacing="8">
    <tr>
        <td>First name:</td>
        <td><input type="text" name="first_name" size="25"></td>
    </tr>
    <tr>
        <td>Last name:</td>
        <td><input type="text" name="last_name" size="25"></td>
    </tr>
    <tr>
        <td>Username:</td>
        <td><span style="font-weight: bold; font-size: 20px; color: #F67733;"><?php print($username); ?></span></td>
    </tr>
    <tr>
        <td>Graduate student:</td>
        <td><input type="checkbox" id="grad_student" name="grad_student" value="1" onchange="javascript:get_total();"></td>
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
<?php
    // build the display for what people are bringing
    $bringing_html = "";
    
    $dish_list = get_dishes_being_brought(get_current_semester(), get_current_year());
    
    $cur_cat_id = -1;
    foreach($dish_list as $dish)
    {
        if($dish['id'] != $cur_cat_id)
        {
            if($cur_cat_id != -1)
            {
                $bringing_html .= "</ol>\n";
            }
            $bringing_html .= "<div style=\"font-family: Arial; font-weight: bold; font-size: 15pt;\">$dish[category]</div>\n<ol>\n";
            $cur_cat_id = $dish['id'];
        }
        $bringing_html .= "<li>$dish[dish_desc]</li>\n";
    }
    
    if($cur_cat_id != -1)
    {
        $bringing_html .= "</ol>\n";
    }
    else
    {
        $bringing_html = "<div style=\"text-align: center; margin-top: 20px;\">(nothing yet)</div>";
    }
?>
        <td colspan="2"><div style="position: relative;"><div id="food_panel" style="display: none; position: absolute; top: 0px; z-index: 2; min-width: 200px; background: #FFCC99; border: solid 2px #333333; padding: 8px;"><div style="text-align: right;"><span style="text-decoration: underline; color: #9900FF; cursor: pointer;" onclick="javascript:document.getElementById('food_panel').style.display = 'none';">Close Window</span></div><div style="margin-bottom: 6px;">The following are things that people have signed up to bring already. It is perfectly fine to bring something that someone else is bringing.</div><div style="overflow-y: scroll; max-height: 300px; border: ridge 2px; padding: 4px;"><?php print($bringing_html); ?></div></div>Describe what you are bringing as specifically as possible.<br>(To see what others are bringing click <span style="text-decoration: underline; color: #FF6633; cursor: pointer;" onclick="javascript:document.getElementById('food_panel').style.display = '';">here</span>.)</div></td>
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
        <td colspan="2">* note that you can't be a judge for the same category you are bringing something for</td>
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
</div>
</div>
<?php print_footer(); ?>
</body>
</html>
