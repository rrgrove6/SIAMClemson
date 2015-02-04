<html>
<head>
	<title>Christmas Party Stats</title>
</head>
<body>
<table cellspacing="0px;">
<tr><td style="border-bottom: solid black 1px; border-right: solid black 1px; width: 80px; text-align: center;">Topping</td><td style="border-bottom: solid black 1px; width: 70px; text-align: center;">Number</td></tr>
<?php
include "common/database.php";

$year = 2014;
$link = db_connect();

$query = "SELECT SUM(pi_pep) AS pepperoni, SUM(pi_cheese) AS cheese, SUM(pi_saus) AS sausage, SUM(pi_veg) AS veggie, SUM(ice_caramel) AS caramel, SUM(ice_fudge) AS fudge, SUM(ice_sprinkles) AS sprinkles, SUM(ice_whip) AS whip_cream, SUM(ice_cherries) AS cherries FROM christmas_party WHERE year = $year";

$result = mysql_query($query, $link);

$row = mysql_fetch_assoc($result);

// print out table
print("<tr><td style=\" border-right: solid black 1px;\">pepperoni</td><td style=\"text-align: center;\">$row[pepperoni]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">sausage</td><td style=\"text-align: center;\">$row[sausage]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">cheese</td><td style=\"text-align: center;\">$row[cheese]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">veggie</td><td style=\"text-align: center;\">$row[veggie]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">caramel</td><td style=\"text-align: center;\">$row[caramel]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">fudge</td><td style=\"text-align: center;\">$row[fudge]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">sprinkles</td><td style=\"text-align: center;\">$row[sprinkles]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">whip cream</td><td style=\"text-align: center;\">$row[whip_cream]</td></tr>");	
print("<tr><td style=\" border-right: solid black 1px;\">cherries</td><td style=\"text-align: center;\">$row[cherries]</td></tr>");
?>
</table>
<table style="margin-top: 30px;">
<tr><td style="border-bottom: solid black 1px;">Other Pizza Toppings</td></tr>
<?php
$query = "SELECT DISTINCT(pi_other) AS other FROM christmas_party WHERE pi_other != \"\" AND year = $year";

$result = mysql_query($query, $link);

while($row = mysql_fetch_assoc($result))
{
    print("<tr><td>$row[other]</td></tr>");
}
?>
</table>
<table style="margin-top: 30px;">
<tr><td style="border-bottom: solid black 1px;">Other Ice Cream Toppings</td></tr>
<?php
	$query = "SELECT DISTINCT(ice_other) AS other FROM christmas_party WHERE ice_other != \"\" AND year = $year";
	
	$result = mysql_query($query, $link);
	
	while($row = mysql_fetch_assoc($result))
	{
		print("<tr><td>$row[other]</td></tr>");
	}
?>
</table>
<?php
	$query = "SELECT sum(num_attending) AS attend FROM christmas_party WHERE year = $year";
	
	$result = mysql_query($query, $link);
	
	$row = mysql_fetch_assoc($result);
	
	print("<div style=\"margin-top: 30px;\">Total attending: $row[attend]<div>");
	
	$query = "SELECT cu_login FROM christmas_party WHERE year = $year ORDER BY cu_login";
	
	$result = mysql_query($query, $link);
	print("<div style=\"font-weight: bold; font-size: 25px; margin-top: 20px;\">People who signed up</div><p>");
	while($row = mysql_fetch_assoc($result))
	{
		print("$row[cu_login], ");
	}
	print("</p>");
?>
</body>
</html>
