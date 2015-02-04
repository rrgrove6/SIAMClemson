<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";

function get_setting($name)
{
    $link = db_connect();
    $query = "SELECT value FROM shirt_settings WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}

function get_current_contest()
{    
    return get_setting("current_contest");
}

function get_designs($contest_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shirt_designs WHERE contest_id = $contest_id";

	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_colors($contest_id)
{
	$link = db_connect();
	$query = "SELECT * FROM shirt_colors WHERE contest_id = $contest_id";

	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_design_vote($contest_id, $username)
{
	$link = db_connect();
	$query = "SELECT IFNULL(SUM(design_pref), -1) AS design_pref FROM shirt_votes WHERE contest_id = $contest_id AND username = \"$username\"";
	
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	return $row["design_pref"];
}

function get_color_prefs($contest_id, $username)
{
	$link = db_connect();
	$query = "SELECT sc.color_id, IFNULL(sp.pref_order, 0) AS pref_order FROM shirt_colors AS sc LEFT JOIN shirt_prefs AS sp ON sp.color_id = sc.color_id AND sp.contest_id = $contest_id AND sp.username = \"$username\"";
	
	$result = mysql_query($query, $link);
	
	$colors = fetch_all_from_result($result);
	
	$output = array();
	
	foreach($colors as $color)
	{
		$output[$color["color_id"]] = $color["pref_order"];
	}
	
	return $output;
}

function add_new_vote($contest_id, $username, $design_pref, $color_prefs)
{
	$link = db_connect();
	$query = "INSERT INTO shirt_votes (contest_id, username, design_pref, date_voted) VALUES ($contest_id, \"$username\", $design_pref, NOW())";
	
	$result = mysql_query($query, $link);
	
	// add in the new color prefs
	foreach($color_prefs as $color_id => $pref_order)
	{
		$query = "INSERT INTO shirt_prefs (contest_id, username, color_id, pref_order) VALUES ($contest_id, \"$username\", $color_id, $pref_order)";
		$result = mysql_query($query, $link);
	}

	return True; // we should really be checking to see if the INSERTs succeeded
}

function update_vote($contest_id, $username, $design_pref, $color_prefs)
{
	$link = db_connect();
	$query = "UPDATE shirt_votes SET design_pref = $design_pref WHERE contest_id = $contest_id AND username = \"$username\"";
	$result = mysql_query($query, $link);
	
	// delete the existing color prefs
	$query = "DELETE FROM shirt_prefs WHERE contest_id = $contest_id AND username = \"$username\"";
	$result = mysql_query($query, $link);
	
	// add in the new color prefs
	foreach($color_prefs as $color_id => $pref_order)
	{
		$query = "INSERT INTO shirt_prefs (contest_id, username, color_id, pref_order) VALUES ($contest_id, \"$username\", $color_id, $pref_order)";
		$result = mysql_query($query, $link);
	}
	
	return True; // we should really be checking to see if these commands succeeded
}

function get_voting_data($contest_id)
{
	$link = db_connect();
	$query = "SELECT sd.display_description, COUNT(sv.design_pref) AS votes FROM shirt_designs AS sd LEFT JOIN shirt_votes AS sv ON sv.design_pref = sd.design_id WHERE sd.contest_id = $contest_id GROUP BY sd.design_id ORDER BY votes DESC";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_color_prefs_point_system($contest_id)
{
	$link = db_connect();
	$query = "SELECT sc.color_id, shirt_color, text_color, SUM(IF(pref_order > 0, (SELECT COUNT(color_id) FROM shirt_colors WHERE contest_id = $contest_id) - pref_order, pref_order)) AS points FROM shirt_prefs AS sp LEFT JOIN shirt_colors AS sc ON sc.color_id = sp.color_id WHERE sp.contest_id = $contest_id GROUP BY color_id ORDER BY points DESC";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_color_prefs_data($contest_id)
{
    $link = db_connect();
    $num_colors = count(get_colors($contest_id));

    $query = "SELECT ";
    
    $pieces = array();

    for($i = 1; $i <= $num_colors; $i++)
    {
        $pieces[] = " IFNULL((SELECT sp.color_id FROM shirt_prefs AS sp WHERE sp.pref_order = $i AND sp.username = v.username AND sp.contest_id = v.contest_id), -1) AS pref_$i";
    }

    $query .= implode(",\n", $pieces) . "\n";

    $query .= "FROM shirt_votes AS v WHERE v.contest_id = $contest_id";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_color($color_id)
{
    $link = db_connect();
    $query = "SELECT shirt_color, text_color FROM shirt_colors WHERE color_id = $color_id";
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        return mysql_fetch_assoc($result);
    }
    
    return array("shirt_color" => "", "text_color" => "");
}


// replacement for json_encode not being available in PHP 5.1.6

function __json_encode( $data )
{           
    if( is_array($data) || is_object($data) )
    {
        $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
       
        if( $islist )
        {
            $json = '[' . implode(',', array_map('__json_encode', $data) ) . ']';
        }
        else
        {
            $items = Array();
            foreach( $data as $key => $value ) {
                $items[] = __json_encode("$key") . ':' . __json_encode($value);
            }
            $json = '{' . implode(',', $items) . '}';
        }
    }
    elseif( is_string($data) )
    {
        # Escape non-printable or Non-ASCII characters.
        # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
        $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
        $json    = '';
        $len    = strlen($string);
        # Convert UTF-8 to Hexadecimal Codepoints.
        for( $i = 0; $i < $len; $i++ )
        {
           
            $char = $string[$i];
            $c1 = ord($char);
           
            # Single byte;
            if( $c1 <128 )
            {
                $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                continue;
            }
           
            # Double byte
            $c2 = ord($string[++$i]);
            if ( ($c1 & 32) === 0 )
            {
                $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                continue;
            }
           
            # Triple
            $c3 = ord($string[++$i]);
            if( ($c1 & 16) === 0 )
            {
                $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                continue;
            }
               
            # Quadruple
            $c4 = ord($string[++$i]);
            if( ($c1 & 8 ) === 0 )
            {
                $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
           
                $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
                $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
                $json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
            }
        }
    }
    else
    {
        # int, floats, bools, null
        $json = strtolower(var_export( $data, true ));
    }
    return $json;
} 
?>