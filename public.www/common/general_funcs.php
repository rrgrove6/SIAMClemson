<?php
session_start(); // we may remove this later on

function get_cart()
{
	if (!array_key_exists("cart", $_SESSION))
	{
		$_SESSION["cart"] = array();
	}
	
	return (array)$_SESSION["cart"];
}


////////////////////////////////////////

function redirect($url)
{
    header("HTTP/1.1 302 Found");
    header ("Location: $url");
    exit;
}

function get_str_value($array, $key, $default = "")
{
    return array_key_exists($key, $array) ? $array["$key"] : $default;
}

function get_int_value($array, $key, $default = 0)
{
    return array_key_exists($key, $array) ? intval($array["$key"]) : intval($default);
}

function get_float_value($array, $key, $default = 0)
{
    return array_key_exists($key, $array) ? floatval($array["$key"]) : floatval($default);
}

function get_html_dropdown($values, $display_text, $name, $selected, $select_options = "")
{
    $output = "<select name=\"$name\"$select_options>";
    
    for($i = 0; $i < count($values); $i++)
    {
        $selected_html = "";
        if($values[$i] == $selected)
        {
            $selected_html = " selected";
        }
        
        $output .= "\t<option value=\"$values[$i]\"$selected_html>$display_text[$i]</option>";
    }
    
    return $output . "</select>";
}

function fetch_all_from_result($result)
{
    $output = array();
    
    while($row = mysql_fetch_assoc($result))
    {
        $output[] = $row;
    }
    
    return $output;
}
?>