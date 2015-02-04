<?php
include "common/template.php";
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 600px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
include "common/database.php";

$username = $_SERVER['REMOTE_USER'];
$cur_year = 2014;
$event_date_str = "Friday, August 22nd at 3:30 PM in Martin M101";
$request_signup_by_str = "Wednesday, August 20th";

function get_int_val($name)
{
	if(isset($_REQUEST[$name]))
	{
		return intval($_REQUEST[$name]);
	}
	else
	{
		return 0;
	}
}

function get_str_val($name)
{
	if(isset($_REQUEST[$name]))
	{
		return mysql_real_escape_string($_REQUEST[$name]);
	}
	else
	{
		return "";
	}
}

function get_signup_str()
{
	$output = get_int_val("num_attending") . ", " . get_int_val("ice_chocolate") . ", " . get_int_val("ice_caramel") . ", " . get_int_val("ice_fudge") . ", " . get_int_val("ice_whip") . ", " . get_int_val("ice_cherries") . ", " . get_int_val("ice_nuts") . ", " . get_int_val("ice_oreos") . ", " . get_int_val("ice_sprinkles") . ", \"" . get_str_val("ice_other") . "\"";
	
	return $output;
}

// check if the form is being submitted
if(get_int_val("submit_signup"))
{
	$link = db_connect();
	
	$query = "SELECT count(id) as signed_up from ice_cream where cu_login = \"$username\" and year = $cur_year";
	$result = mysql_query($query, $link);
	
	$row = mysql_fetch_assoc($result);
	$signed_up = $row["signed_up"];
	
	if($signed_up == "0")
	{
	// insert into database
	$signup_str = get_signup_str();
	$query = "Insert into ice_cream (year, cu_login, num_attending, ice_chocolate, ice_caramel, ice_fudge, ice_whip, ice_cherries, ice_nuts, ice_oreos, ice_sprinkles, ice_other, signup_time) Values ($cur_year, \"$username\", $signup_str, NOW())";
	
	$result = mysql_query($query, $link);
?>
<p>Thank you for signing up to attend the SIAM ice cream social. We look forward to seeing you on <?php print($event_date_str); ?>.</p>
<?php
	}
	else
	{
?>
<p>You have already signed up. If you need to change something please contact Michael Dowling via <a href="comments.php">email</a>.</p>
<?php
	}
} // end display of ice cream confirmation page
else
{
?>
<div style="text-align: center; font-size: 22px; font-weight: bold; text-decoration: underline; font-family: Arial; margin-top: 20px;">SIAM ice cream social</div>
<p>SIAM is pleased to announce that we will be hosting an ice cream social this <?php print($event_date_str); ?>. You are welcome to bring your spouses, boyfriends/girlfriends etc. We hope this will provide an opportunity for those who are new to our department (first year grad students) to get better acquainted with the other grad students. We will have ice cream, toppings, and soft drinks. We will also have graduate students talk about some exciting upcoming activities such as intramural sports, picnics, group football tickets, and more!  There is no charge, but we ask you to sign up by this  <?php print($request_signup_by_str); ?>.</p>
<p>Fill out the form below to let us know how many people are planning to attend with you and what toppings you would prefer on your ice cream.</p>

<form name="ice_cream" method="POST" action="ice_cream.php">
<ol>
	<li>Total number attending:<input name="num_attending" size="2" value="1" style="margin-left: 10px;"></li>
	<li>Ice Cream toppings (you can select more than one):<br>
	<ul style="list-style: none;">
        <li><input name="ice_chocolate" type="checkbox" value="1">Chocolate Sauce</li>
        <li><input name="ice_caramel" type="checkbox" value="1">Caramel Sauce</li>
        <li><input name="ice_fudge" type="checkbox" value="1">Fudge</li>
        <li><input name="ice_whip" type="checkbox" value="1">Whipped Cream</li>
        <li><input name="ice_cherries" type="checkbox" value="1">Cherries</li>
        <li><input name="ice_nuts" type="checkbox" value="1">Peanuts</li>
        <li><input name="ice_oreos" type="checkbox" value="1">Oreos</li>
        <li><input name="ice_sprinkles" type="checkbox" value="1">Sprinkles</li>
        <li>Other: <input name="ice_other" type="text" size="40"></li>
	</ul>
	</li>
</ol>
<p style="text-align: center;"><input name="submit_signup" type="hidden" value="1"><input type="submit" value="Sign Up"></p>
</form>
<?php
} // end display of ice cream page
?>
</div>
</div>
<?php print_footer(); ?>
</body>
</html>