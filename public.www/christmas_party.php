<?php
include "common/template.php";
include_once "common/database.php";
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
// update these variables for the current year's info
$year = 2014;
$signup_confirmation_message = "Thank you for signing up to attend the SIAM Christmas party. We look forward to seeing you on Wednesday, Dec. 3rd at 5:00 PM in Martin M101.";
$signup_again_message = "You have already signed up. If you need to change something please contact Ryan Grove via <a href=\"comments.php\">email</a>.";

$username = $_SERVER['REMOTE_USER'];

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
    $output = get_int_val("num_attending") . ", " . get_int_val("pi_pep") . ", " . get_int_val("pi_saus") . ", " . get_int_val("pi_cheese") . ", " . get_int_val("pi_veg") . ", \"" . get_str_val("pi_other") . "\", " . get_int_val("ice_caramel") . ", " . get_int_val("ice_fudge") . ", " . get_int_val("ice_sprinkles") . ", " . get_int_val("ice_whip") . ", " . get_int_val("ice_cherries") . ", \"" . get_str_val("ice_other") . "\"";
    
    return $output;
}

// check if the form is being submitted
if(get_int_val("submit_signup") == 1)
{
    $link = db_connect();
    
    $query = "SELECT count(id) as signed_up from christmas_party where cu_login = \"$username\" AND year = $year";
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    $signed_up = $row["signed_up"];
    
    if($signed_up == "0")
    {
        // insert into database
        $signup_str = get_signup_str();
        $query = "Insert into christmas_party (cu_login, year, num_attending, pi_pep, pi_saus, pi_cheese, pi_veg, pi_other, ice_caramel, ice_fudge, ice_sprinkles, ice_whip, ice_cherries, ice_other) Values (\"$username\", $year, $signup_str)";
    
        $result = mysql_query($query, $link);
        print("<p>$signup_confirmation_message</p>");
    }
    else
    {
        print("<p>$signup_again_message</p>");
    }
} // end display of christmas party confirmation page
else
{
?>
<div style="font-weight: bold; font-size: 50px; text-align: center;">SIAM Christmas Party</div>

<p> Do you want free pizza without having to sit through any sort of talk? Then, come celebrate the end of the semester and the upcoming holidays at the SIAM Christmas Party!! There will be food, fun, and games! SIAM will provide free pizza, hot chocolate, and soda. Attendees are welcome to bring a sweet treat or snack to spread the good cheer even further! Also, feel free to bring any fun table/board games you'd like to play. This is your long-awaited opportunity to wear that favorite Christmas sweater that's been collecting dust in your closet since last December, so don't miss out! </p>

<p>Fill out the form below to let us know how many people are planning to attend with you and what toppings you would prefer on your pizza<!-- and ice cream-->.</p>

<form name="party_signup" method="POST" action="christmas_party.php">
<ol>
    <li>Number attending:<input name="num_attending" size="2" value="1" style="margin-left: 10px;"></li>
    <li>Pizza toppings (you can select more than one):<br>
    <ul style="list-style: none;">
        <li><input name="pi_pep" type="checkbox" value="1">Pepperoni</li>
        <li><input name="pi_saus" type="checkbox" value="1">Sausage</li>
        <li><input name="pi_cheese" type="checkbox" value="1">Cheese</li>
        <li><input name="pi_veg" type="checkbox" value="1">Veggie</li>
        <li>Other: <input name="pi_other" type="text" size="20"></li>
    </ul>
    </li>
    <!--<li>Ice Cream toppings (you can select more than one):<br>
    <ul style="list-style: none;">
        <li><input name="ice_caramel" type="checkbox" value="1">Caramel</li>
        <li><input name="ice_fudge" type="checkbox" value="1">Fudge</li>
        <li><input name="ice_sprinkles" type="checkbox" value="1">Sprinkles</li>
        <li><input name="ice_whip" type="checkbox" value="1">Whipped Cream</li>
        <li><input name="ice_cherries" type="checkbox" value="1">Cherries</li>
        <li>Other: <input name="ice_other" type="text" size="20"></li>-->
    </ul>
    </li>
</ol>
<p><input name="submit_signup" type="hidden" value="1"><input type="submit" value="Sign Up"></p>
</form>
<?php
} // end display of christmas party page
?>
</div>
</div>
<?php
print_footer(); ?>
</body>
</html>