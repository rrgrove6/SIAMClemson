<?php
include "../common/template.php";
include "../common/database.php";
include "gss_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="../styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_menu(); ?>
<?php
	$link = db_connect();

	$username = $_SERVER['REMOTE_USER'];
	
	// get the current talk id
	$query = "SELECT value FROM gss_settings WHERE property = \"current_talk\"";
	
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	$talk_id = intval($row["value"]);
	
	// get the title of the talk and the date
	$query = "SELECT title, DATE_FORMAT(date, \"%b %e\") AS date FROM gss_talks WHERE talk_id = $talk_id";
	
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	$talk_title = $row["title"];
	$talk_date = $row["date"];
	
	// check if this person has already signed up
	$query = "SELECT COUNT(username) AS signed_up FROM gss_signup WHERE username = \"$username\" AND talk_id = $talk_id";
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	$signed_up = intval($row["signed_up"]);

if($signed_up)
{
	// display a message saying that they have already signed up
?>
<p>You have already signed up for the next GSS talk as <span style="font-weight: bold; font-size: 25px; color: #FF6633;"><?php print($username); ?></span>. If this is not your username, you will need to close your browser and login again. If you cannot attend or need to change any of the information you submitted, please contact Michael (mdowlin@clemson.edu).</p>
<?php
}
else // they have not signed up yet
{
	if(isset($_POST["gss_submit"]) && $_POST["gss_submit"] == "1")
	{
		// they are submitting the form to signup
		$pref = intval($_POST["pizzapref"]);
		
		$query = "INSERT INTO gss_signup (username, talk_id, preference_id, signup_time) VALUES (\"$username\", $talk_id, $pref, NOW())";
		
		$result = mysql_query($query, $link);
?>
<p>Thank you for signing up to attend the upcoming GSS talk held Wednesday in M-102 at 5:00 pm. If you cannot attend or need to change any of the information you submitted, please contact Michael (mdowlin@clemson.edu).</p>
<?php
	}
	else
	{
?>
	<div style="font-size: 22px; font-weight: bold;">Graduate Student Seminar Signup</div>
	<p>This semester the Graduate Student Seminar (GSS) is being sponsored by our SIAM student chapter. GSS is a weekly series of talks. The speakers are graduates students from the Math department who present their research or other areas of interest. All graduate students in the department are encouraged to attend. GSS meets each Wednesday at 5:00 PM in M-102.</p>
	<p>SIAM will be providing pizza, and soft drinks for each of the talks. There is no cost to attend, but to assist us in planning the amount of food to have ready, we ask that you sign up by choosing your pizza preference below. Please sign up before 2:00 PM on the day of the talk.</p>
	<div><span style="font-size: 22px; font-weight: bold; position: relative; float: left;">Next Talk:</span><div style="font-size: 22px; margin-left: 175px;"><?php print("($talk_date) $talk_title");?></div></div>
	<form method="POST" action="signup.php">
		<div style="font-size: 22px; font-weight: bold; vertical-align: top;">Pizza Preference:</div>
		<div style="margin-left: 175px;">
			<ul style="list-style: none; padding-left: 0px; margin-top: 0px;">
				<li><label><input type="radio" name="pizzapref" value="1">Cheese</label></li>
				<li><label><input type="radio" name="pizzapref" value="2">Pepperoni</label></li>
				<li><label><input type="radio" name="pizzapref" value="3" >Sausage</label></li>
				<li><label><input type="radio" name="pizzapref" value="4" checked="checked">Any</label></li>
			</ul>
		</div>
		<div style="text-align: center;"><input type="submit" value="Signup"></div>
		<input type="hidden" name="gss_submit" value="1">
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