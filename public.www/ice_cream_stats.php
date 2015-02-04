<!doctype html>
<html>
<head>
	<title>Ice Cream Stats</title>
</head>
<body>
<table cellspacing="0px;">
<tr><td style="border-bottom: solid black 1px; border-right: solid black 1px; width: 80px; text-align: center;">Topping</td><td style="border-bottom: solid black 1px; width: 70px; text-align: center;">Number</td></tr>
<?php
include "common/database.php";

$cur_year = 2014;
$link = db_connect();

$query = "SELECT SUM(ice_chocolate) AS chocolate, SUM(ice_caramel) AS caramel, SUM(ice_fudge) AS fudge, SUM(ice_whip) AS whip_cream, SUM(ice_cherries) AS cherries, SUM(ice_nuts) AS peanuts, SUM(ice_oreos) AS oreos, SUM(ice_sprinkles) AS sprinkles FROM ice_cream WHERE year = $cur_year";

$result = mysql_query($query, $link);

$row = mysql_fetch_assoc($result);

// print out table
print("<tr><td style=\" border-right: solid black 1px;\">chocolate</td><td style=\"text-align: center;\">$row[chocolate]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">caramel</td><td style=\"text-align: center;\">$row[caramel]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">fudge</td><td style=\"text-align: center;\">$row[fudge]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">whip cream</td><td style=\"text-align: center;\">$row[whip_cream]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">cherries</td><td style=\"text-align: center;\">$row[cherries]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">peanuts</td><td style=\"text-align: center;\">$row[peanuts]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">oreos</td><td style=\"text-align: center;\">$row[oreos]</td></tr>");
print("<tr><td style=\" border-right: solid black 1px;\">sprinkles</td><td style=\"text-align: center;\">$row[sprinkles]</td></tr>");
?>
</table>
<table style="margin-top: 30px;">
<tr><td style="border-bottom: solid black 1px;">Other Ice Cream Toppings</td></tr>
<?php
$query = "SELECT DISTINCT(ice_other) AS other FROM `ice_cream` WHERE ice_other != \"\" AND year = $cur_year";

$result = mysql_query($query, $link);

while($row = mysql_fetch_assoc($result))
{
    print("<tr><td>$row[other]</td></tr>");
}
?>
</table>
<?php
$query = "SELECT SUM(num_attending) AS attend FROM ice_cream WHERE year = $cur_year";

$result = mysql_query($query, $link);

$row = mysql_fetch_assoc($result);

print("<div style=\"margin-top: 30px;\">Total attending: $row[attend]</div>");
?>
<div style="margin-top: 30px; border-bottom: solid black 1px; text-align: center; width: 100px;">People</div>
<?php
$query = "SELECT cu_login FROM ice_cream WHERE year = $cur_year ORDER BY cu_login";

$result = mysql_query($query, $link);

while($row = mysql_fetch_assoc($result))
{
    print("<div>$row[cu_login]<div>");
}
?>
</body>
</html>