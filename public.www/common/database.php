<?php
function db_connect()
{
	$server = "bark.clemson.edu";
	$user_name = "viqyykz";
	$password = "vu800n";
	$db_name = "COES0975_SIAM_CLEMSON_CHAPT";
	
	$link=mysql_connect($server, $user_name, $password);
	mysql_select_db($db_name, $link);
	return $link;
}
?>