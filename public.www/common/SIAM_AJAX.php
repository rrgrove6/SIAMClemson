<?php
include "database.php";

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "get_awards")
{
	get_awards();
	exit;
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "get_award_years")
{
	get_award_years();
	exit;
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "get_officers")
{
	get_officers();
	exit;
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "get_officer_years")
{
	get_officer_years();
	exit;
}

if(isset($_REQUEST['func']) && $_REQUEST['func'] == "send_email")
{
	send_email();
	exit;
}

//==============================
//  Method implementation
//==============================

function get_awards()
{
	$link = db_connect();
	$output = "";
	$query = "Select ar.first_name, ar.last_name, a.desc From award_recipients As ar Inner Join awards As a On ar.award_id = a.award_id Where ar.year = $_REQUEST[year] Order By ar.last_name, ar.first_name, a.desc"; // TODO: this is unsafe we should be using a query builder
	
	$result = mysql_query($query, $link);
	
	while ($row = mysql_fetch_assoc($result))
	{
		 $output .= implode(",", $row) . ";";
	}
	$output = substr_replace($output ,"",-1); // we remove the trailing semicolon
	
	print $output;
}

function get_award_years()
{
	$link = db_connect();
	$output = "";
	$query = "Select Distinct year From award_recipients Order By year";
	
	$result = mysql_query($query, $link);
	
	while ($row = mysql_fetch_assoc($result))
	{
		 $output .= $row['year'] . ",";
	}
	$output = substr_replace($output ,"",-1); // we remove the trailing comma
	
	print $output;
}

function get_officers()
{
	$link = db_connect();
	$output = "";
	$query = "Select o.desc, oc.first_name, oc.last_name From officer_crews As oc Inner Join offices As o On oc.office_id = o.office_id Where oc.year = $_REQUEST[year] Order By oc.office_id"; // TODO: this is unsafe we should be using a query builder
	
	$result = mysql_query($query, $link);
	
	while ($row = mysql_fetch_assoc($result))
	{
		 $output .= implode(",", $row) . ";";
	}
	$output = substr_replace($output ,"",-1); // we remove the trailing semicolon
	
	print $output;
}

function get_officer_years()
{
	$link = db_connect();
	$output = "";
	$query = "Select Distinct year From officer_crews Order By year";
	
	$result = mysql_query($query, $link);
	
	while ($row = mysql_fetch_assoc($result))
	{
		 $output .= $row['year'] . ",";
	}
	$output = substr_replace($output ,"",-1); // we remove the trailing comma
	
	print $output;
}

function send_email()
{
    // we are storing this in the database now and should just pull it out dynamically instead of hard coding it in
	$recipients = array("pres" => "adevrie@g.clemson.edu", "fac" => "rebholz@clemson.edu", "web" => "rgrove@g.clemson.edu");
	$email_to = array_key_exists($_REQUEST['email_to'], $recipients) ? $recipients[$_REQUEST['email_to']] : "adevrie@clemson.edu";
	
    $message = $_REQUEST['msg'] . "\n\n$_REQUEST[name]\n$_REQUEST[email_from]";

    $message = wordwrap($message,70);
	 
	 $headers = "From: $_REQUEST[name] <$_REQUEST[email_from]>";

    $output = mail($email_to,"Comment from SIAM website", $message, $headers);
	 
	 if($_REQUEST['bcc'] == "1")
	 {
		mail($_REQUEST["email_from"],"Comment from SIAM website", $message, $headers);
	 }
	 
	 if($output)
	 {
		print("1");
	 }
	 else
	 {
		print("0");
	 }
}
?>