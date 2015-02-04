<?php
include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";

function get_current_semester()
{
    $link = db_connect();
    $query = "SELECT value FROM gss_settings WHERE property = \"current_semester\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}

function get_current_year()
{
    $link = db_connect();
    $query = "SELECT value FROM gss_settings WHERE property = \"current_year\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}

function get_current_talk()
{
    $link = db_connect();
	$query = "SELECT talk_id, speaker, title, abstract, DATE_FORMAT(date, \"%b %e\") AS date FROM gss_talks WHERE talk_id = (SELECT value FROM gss_settings WHERE property = \"current_talk\" LIMIT 1)";
	
	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function get_talk_info($talk_id)
{
    $link = db_connect();
	$query = "SELECT talk_id, speaker, title, abstract, DATE_FORMAT(date, \"%m/%d/%Y\") AS date FROM gss_talks WHERE talk_id = $talk_id";
	
	$result = mysql_query($query, $link);
    
	return mysql_fetch_assoc($result);
}

function get_talk_attachments($talk_id)
{
    $link = db_connect();
	$query = "SELECT* FROM gss_talk_attachments WHERE talk_id = $talk_id ORDER BY link_text";
	
	$result = mysql_query($query, $link);
    
	return fetch_all_from_result($result);
}

function get_attachment_info($attachment_id)
{
    $link = db_connect();
	$query = "SELECT* FROM gss_talk_attachments WHERE attachment_id = $attachment_id";
	
	$result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        return mysql_fetch_assoc($result);
    }
    else
    {
        return array();
    }
}

function get_semester_talk_list()
{
	$link = db_connect();
    $query = "SELECT DISTINCT IF(MONTH(date) <= 6, \"Spring\", \"Fall\") AS semester, YEAR(date) AS year FROM gss_talks ORDER BY date DESC";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_talk_list($semester, $year)
{
    $link = db_connect();
    
    if(strtolower($semester) == "spring")
    {
        $month_start = 0;
        $month_end = 6;
    }
    else
    {
        $month_start = 6;
        $month_end = 12;
    }

	$query = "SELECT talk_id, speaker, title, abstract, DATE_FORMAT(date, \"%b %e\") AS talk_date FROM gss_talks WHERE EXTRACT(YEAR FROM date) = $year AND EXTRACT(MONTH FROM date) > $month_start AND EXTRACT(MONTH FROM date) <= $month_end ORDER BY date";
	
	$result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_all_talks_list()
{
    $link = db_connect();

	$query = "SELECT talk_id, speaker, title, abstract, DATE_FORMAT(date, \"%b %e, %Y\") AS talk_date FROM gss_talks ORDER BY date DESC";
	
	$result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_signup_topping_summary($talk_id)
{
    $link = db_connect();
    $query = "SELECT gt.description, SUM(IF(ISNULL(gs.preference_id), 0, 1)) AS number FROM gss_toppings AS gt LEFT JOIN gss_signup AS gs ON gs.preference_id = gt.topping_id AND gs.talk_id = $talk_id GROUP BY gt.topping_id";
	//$query = "SELECT COUNT(gt.topping_id) AS number, gt.description FROM gss_signup AS gs INNER JOIN gss_toppings AS gt ON gs.preference_id = gt.topping_id WHERE gs.talk_id = $talk_id GROUP BY topping_id";
	
	$result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_signup_email_list($talk_id)
{
	$link = db_connect();
	$query = "SELECT * FROM gss_signup WHERE talk_id = $talk_id AND signup_time <= (SELECT ADDTIME(CAST(date AS DATETIME), \"14:00:00\") FROM gss_talks WHERE talk_id = $talk_id LIMIT 1) ORDER BY username";
	
	$result = mysql_query($query, $link);
    
    $output = array();
	while($row = mysql_fetch_assoc($result))
    {
        $output[] = $row["username"] . "@clemson.edu";
    }
    
    return $output;
}

function get_late_signup_email_list($talk_id)
{
	$link = db_connect();
	$query = "SELECT * FROM gss_signup WHERE talk_id = $talk_id AND signup_time > (SELECT ADDTIME(CAST(date AS DATETIME), \"14:00:00\") FROM gss_talks WHERE talk_id = $talk_id LIMIT 1) ORDER BY username";
	
	$result = mysql_query($query, $link);
    
    $output = array();
	while($row = mysql_fetch_assoc($result))
    {
        $output[] = $row["username"] . "@clemson.edu";
    }
    
    return $output;
}

function print_gss_admin_header()
{
    echo <<< EOF
<div style="text-align: center;">
    <a style="margin: 5px;" href="admin_view_signups.php">View signups</a>
    <a style="margin: 5px;" href="admin_manage_talk.php?action=add">Add a new talk</a>
    <a style="margin: 5px;" href="admin_talk_list.php">Talk list</a>
	 <a style="margin: 5px;" href="admin_email.php">Send Email</a>
    <a style="margin: 5px;" href="admin_settings.php">Settings</a>
</div>
EOF;
}

function print_gss_menu()
{
    echo <<< EOF
<div style="text-align: center;">
    <a style="margin: 5px;" href="main.php">About GSS</a>
    <a style="margin: 5px;" href="schedule.php">Schedule</a>
    <a style="margin: 5px;" href="signup.php">Sign Up</a>
</div>
EOF;
}

function set_current_semester($semester)
{
    $link = db_connect();
    $query = "UPDATE gss_settings SET value = \"$semester\" WHERE property = \"current_semester\"";
    
    $result = mysql_query($query, $link);
    
    return 1;
}

function set_current_year($year)
{
    $link = db_connect();
    $query = "UPDATE gss_settings SET value = $year WHERE property = \"current_year\"";
    
    $result = mysql_query($query, $link);
    
    return 1;
}

#======================================
# input: int talk id
# output: the title and date of the talk
function set_current_talk($talk_id)
{
    $link = db_connect();

    if(intval($talk_id) != -1)
    {
        $query = "UPDATE gss_settings SET value = $talk_id WHERE property = \"current_talk\"";
        
        $result = mysql_query($query, $link);
    }
    
    // get the title of the talk and the date
	$query = "SELECT title, DATE_FORMAT(date, \"%b %e\") AS date FROM gss_talks WHERE talk_id = (SELECT value FROM gss_settings WHERE property = \"current_talk\")";
	
	$result = mysql_query($query, $link);
	return mysql_fetch_assoc($result);
}

function add_new_talk($title, $date, $speaker, $abstract)
{
    $link = db_connect();
    $title = mysql_real_escape_string($title);
    $speaker = mysql_real_escape_string($speaker);
    $abstract = mysql_real_escape_string($abstract);
	$query = "INSERT INTO gss_talks (title, date, speaker, abstract) VALUES ('$title', STR_TO_DATE('$date', '%m/%d/%Y'), '$speaker', '$abstract')";
	
	$result = mysql_query($query, $link);
    
    return mysql_insert_id($link);
}

function save_talk($talk_id, $title, $date, $speaker, $abstract)
{
    $link = db_connect();
    $title = mysql_real_escape_string($title);
    $speaker = mysql_real_escape_string($speaker);
    $abstract = mysql_real_escape_string($abstract);
	$query = "UPDATE gss_talks SET title = '$title', date = STR_TO_DATE('$date', '%m/%d/%Y'), speaker = '$speaker', abstract = '$abstract' WHERE talk_id = $talk_id";
	
	$result = mysql_query($query, $link);
    
    return 1;
}

function delete_talk($talk_id)
{
    $link = db_connect();
	$query = "SELECT COUNT(username) AS valid FROM gss_signup WHERE talk_id = $talk_id";
	
	$result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    $valid = $row["valid"];
    
    if($valid == 0)
    {
        $query = "DELETE FROM gss_talks WHERE talk_id = $talk_id LIMIT 1";
        // we need to delete the attachments as well
        mysql_query($query, $link);
        
        return True;
    }
    else
    {
        return False;
    }
}

function delete_attachment($attachment_id)
{
    $link = db_connect();
    
	 $query = "SELECT actual_filename FROM gss_talk_attachments WHERE attachment_id = $attachment_id";
	 
	$result = mysql_query($query, $link);
	
	if(mysql_num_rows($result) == 1)
	{
		$row = mysql_fetch_assoc($result);
		
		unlink("/cifsmounts/EH01/users/siam/public.www/gss/uploaded_attachments/" . $row["actual_filename"]);
		
		$query = "DELETE FROM gss_talk_attachments WHERE attachment_id = $attachment_id";
	
		$result = mysql_query($query, $link);
		
		return True;
	}
	else
	{
		return False;
	}
}

function add_new_attachment($talk_id, $link_text, $filename, $file_data)
{
    $link = db_connect();
    // we need to generate out a unique new filename
    $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $new_filename = "";
    $temp = explode(".", $filename);
    $size = count($temp);
    
    if($size < 2)
    {
        $type = "";
    }
    else
    {
        $type = $temp[$size - 1];
    }
    
    while($new_filename == "")
    {
        for($i = 0; $i < 10; $i++)
        {
            $new_filename .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        $new_filename .= "." . $type;
        
        $query = "SELECT COUNT(actual_filename) AS is_valid FROM gss_talk_attachments WHERE actual_filename = '$new_filename'";
        
        $result = mysql_query($query, $link);
        $row = mysql_fetch_assoc($result);
        
        if($row["is_valid"] > 0)
        {
            $new_filename = "";
        }
    }
    
	$query = "INSERT INTO gss_talk_attachments (talk_id, link_text, display_filename, actual_filename) VALUES ($talk_id, '$link_text', '$filename', '$new_filename')";
	
	$result = mysql_query($query, $link);
    
    // we need to store the file on the disk as well
    $file = fopen("/cifsmounts/EH01/users/siam/public.www/gss/uploaded_attachments/" . $new_filename, "w");
    fwrite($file, $file_data);
    fclose($file);
    
    return 1;
}

?>