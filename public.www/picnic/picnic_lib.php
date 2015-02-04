<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";

function get_setting($name)
{
    $link = db_connect();
    $query = "SELECT value FROM picnic_settings WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}

function set_setting($name, $value)
{
    $link = db_connect();
    $query = "UPDATE picnic_settings SET value = \"$value\" WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    return True; // we should really be checking to see if this succeeded
}

// helper function for testing integers
function check_integer($str)
{
    if(is_numeric($str) && intval($str) == $str)
    {
        return True;
    }
    else
    {
        return False;
    }
}

function get_current_semester()
{    
    return get_setting("current_semester");
}

function set_current_semester($semester)
{
    if(in_array($semester, array("fall", "spring")))
    {
        return set_setting("current_semester", $semester);
    }

    return False;
}

function get_current_year()
{
    return get_setting("current_year");
}

function set_current_year($year)
{
    if(check_integer($year))
    {
        return set_setting("current_year", $year);
    }

    return False;
}

function get_signup_start()
{    
    return get_setting("signup_start");
}

function set_signup_start($date)
{
    if($timestamp = strtotime($date))
    {
        return set_setting("signup_start", date("n/j/y", $timestamp));
    }

    return False;
}

function get_signup_end()
{
    return get_setting("signup_end");
}

function set_signup_end($date)
{
    if($timestamp = strtotime($date))
    {
        return set_setting("signup_end", date("n/j/y", $timestamp));
    }

    return False;
}

function get_discount_end()
{
    return get_setting("discount_end");
}

function set_discount_end($date)
{
    if($timestamp = strtotime($date))
    {
        return set_setting("discount_end", date("n/j/y", $timestamp));
    }

    return False;
}

function get_grad_student_price()
{
    return get_setting("grad_student_price");
}

function set_grad_student_price($price)
{
    if(is_numeric($price))
    {
        return set_setting("grad_student_price", $price);
    }

    return False;
}

function get_faculty_staff_price()
{
    return get_setting("faculty_staff_price");
}

function set_faculty_staff_price($price)
{
    if(is_numeric($price))
    {
        return set_setting("faculty_staff_price", $price);
    }

    return False;
}

function get_signup_discount()
{
    return get_setting("early_signup_discount");
}

function set_signup_discount($price)
{
    if(is_numeric($price))
    {
        return set_setting("early_signup_discount", $price);
    }

    return False;
}

function get_food_discount()
{
    return get_setting("food_discount");
}

function set_food_discount($price)
{
    if(is_numeric($price))
    {
        return set_setting("food_discount", $price);
    }

    return False;
}

function has_signed_up($username)
{
	$link = db_connect();

	$query = "SELECT COUNT(user_name) AS signed_up FROM picnic_signup WHERE user_name = \"$username\" AND picnic_semester = \"" . get_current_semester() . "\" AND picnic_year = " . get_current_year();
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	if(intval($row['signed_up']) > 0)
	{
		return True;
	}
	else
	{
		return False;
	}
}

function eligible_for_signup_discount()
{
	$link = db_connect();
	
	$signup_start_date = get_signup_start();
	$discount_end_date = get_discount_end();
	
	$query="SELECT (NOW() >= STR_TO_DATE(\"$signup_start_date\", \"%c/%e/%y\")) AND (NOW() < STR_TO_DATE(\"$discount_end_date\", \"%c/%e/%y\")) AS valid";
	$result = mysql_query($query, $link);
	
	$row = mysql_fetch_assoc($result);
	
	return $row["valid"] == 1 ? True : False;
}

function add_picnic_signup($first_name, $last_name, $username, $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge)
{
	$link = db_connect();
	
	$query="INSERT INTO picnic_signup (picnic_semester, picnic_year, first_name, last_name, grad_student, user_name, num_attending, num_under_10, drink, dish, dish_desc, lawn_game, dessert_judge, side_judge, signup, paid, discount) VALUES (\"" . get_current_semester() . "\", " . get_current_year() . ", \"$first_name\", \"$last_name\", $graduate, LOWER(\"$username\"), $num_attending, $num_under_10, $drink, $dish, \"$dish_desc\", $lawn_game, $dessert_judge, $side_judge, NOW(), 0, " . (eligible_for_signup_discount() ? 1 : 0) . ")";
    
	$result = mysql_query($query, $link);
	
	return True; // we really should check to see whether the INSERT worked or not
}

function add_picnic_signup_admin($first_name, $last_name, $username, $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge, $discount)
{
	$link = db_connect();
	
	$query="INSERT INTO picnic_signup (picnic_semester, picnic_year, first_name, last_name, grad_student, user_name, num_attending, num_under_10, drink, dish, dish_desc, lawn_game, dessert_judge, side_judge, signup, paid, discount) VALUES (\"" . get_current_semester() . "\", " . get_current_year() . ", \"$first_name\", \"$last_name\", $graduate, LOWER(\"$username\"), $num_attending, $num_under_10, $drink, $dish, \"$dish_desc\", $lawn_game, $dessert_judge, $side_judge, NOW(), 0, $discount)";
	$result = mysql_query($query, $link);
	
	return True; // we really should check to see whether the INSERT worked or not
}

function update_picnic_signup($semester, $year, $officer_username, $first_name, $last_name, $username, $graduate, $num_attending, $num_under_10, $drink, $dish, $dish_desc, $lawn_game, $dessert_judge, $side_judge, $paid, $discount)
{
	$link = db_connect();
	
	$query="UPDATE picnic_signup SET first_name = \"$first_name\", last_name = \"$last_name\", grad_student = $graduate, num_attending = $num_attending, num_under_10 = $num_under_10, drink = $drink, dish = $dish, lawn_game = $lawn_game, dessert_judge = $dessert_judge, side_judge = $side_judge, dish_desc = \"$dish_desc\", updated_by = \"$officer_username\", paid= $paid, last_updated = NOW(), discount = $discount WHERE picnic_semester = \"$semester\" AND picnic_year = $year AND user_name = \"$username\"";
	
	$result = mysql_query($query, $link);
	
	return True; // we really should check to see whether the UPDATE worked or not
}

function get_signup_details($username, $semester, $year)
{
	$link = db_connect();
	
	$query="Select first_name, last_name, user_name, DATE_FORMAT(signup, '%W, %M %e at %l:%i %p') as signup, grad_student, num_attending, num_under_10, drink, dish, dish_desc, lawn_game, dessert_judge, side_judge, paid, IFNULL(updated_by,'') as updated_by, DATE_FORMAT(last_updated, '%W, %M %e at %l:%i %p') as last_updated, discount FROM picnic_signup WHERE picnic_semester = \"$semester\" AND picnic_year = $year AND user_name = \"$username\"";

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

function get_drink_preferences()
{
	$link = db_connect();

	$query="SELECT id, description FROM picnic_drinks ORDER BY display_order";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_dish_options()
{
	$link = db_connect();

	$query="SELECT id, category FROM picnic_dish ORDER BY display_order";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_current_officers()
{
	$link = db_connect();

	$query="SELECT * FROM current_officers ORDER BY display_order";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_dishes_being_brought($semester, $year)
{
	$link = db_connect();

	$query="SELECT pd.id, pd.category, ps.dish_desc FROM picnic_signup AS ps INNER JOIN picnic_dish AS pd ON ps.dish = pd.id WHERE ps.dish_desc != \"\" AND picnic_semester = \"$semester\" AND picnic_year = $year AND ps.dish != 0 ORDER BY ps.dish, ps.dish_desc";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_side_dish_judges($semester, $year)
{
	$link = db_connect();

	$query="SELECT first_name, last_name, user_name FROM picnic_signup WHERE side_judge = 1 AND picnic_semester = \"$semester\" AND picnic_year = $year ORDER BY last_name, first_name";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_dessert_judges($semester, $year)
{
	$link = db_connect();

	$query="SELECT first_name, last_name, user_name FROM picnic_signup WHERE dessert_judge = 1 AND picnic_semester = \"$semester\" AND picnic_year = $year ORDER BY last_name, first_name";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_list_of_orders($semester, $year)
{
	$link = db_connect();
	
	$grad_price = get_grad_student_price();
	$faculty_staff_price = get_faculty_staff_price();
	$food_discount = get_food_discount();
	$signup_discount = get_signup_discount();
	
	//$query = "Select user_name, updated_by, paid, If(UNIX_TIMESTAMP(last_updated) = 0,'never',DATE_FORMAT(last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(signup, '%c/%e/%y') as signup From picnic_signup WHERE picnic_semester = \"$semester\" AND picnic_year = $year ORDER BY user_name";
	
	$query = "Select first_name, last_name, user_name, updated_by, paid, num_attending, IF(num_under_10 = 0,\"\",num_under_10) AS children_count, IF(dish != 0,1,0) AS dish, discount, IF(grad_student = 1,IF(dish != 0,$grad_price - $food_discount - discount*$signup_discount,$grad_price - discount*$signup_discount),IF(dish != 0,$faculty_staff_price - $food_discount - discount*$signup_discount,$faculty_staff_price - discount*$signup_discount)) * (num_attending - num_under_10) as total_cost, If(UNIX_TIMESTAMP(last_updated) = 0,'never',DATE_FORMAT(last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(signup, '%c/%e/%y') as signup From picnic_signup  WHERE picnic_semester = \"$semester\" AND picnic_year = $year Order by last_name, first_name, user_name";
	
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_attendance_data($semester, $year)
{
	$link = db_connect();
	
	$query = "SELECT Sum(num_attending) AS total_attending, SUM(num_under_10) AS total_under_10 FROM picnic_signup WHERE picnic_semester = \"$semester\" AND picnic_year = $year";
	$result = mysql_query($query, $link);
	
	return mysql_fetch_assoc($result);
}

function get_payment_data($semester, $year)
{
	$link = db_connect();
	
	$grad_price = get_grad_student_price();
	$faculty_staff_price = get_faculty_staff_price();
	$food_discount = get_food_discount();
	$signup_discount = get_signup_discount();
	
	$query = "SELECT IF(paid = 0, \"Unpaid\", \"Paid\") AS paid, SUM(IF(grad_student = 1,IF(dish != 0,$grad_price - $food_discount - discount*$signup_discount,$grad_price - discount*$signup_discount),IF(dish != 0,$faculty_staff_price - $food_discount - discount*$signup_discount,$faculty_staff_price - discount*$signup_discount)) * (num_attending - num_under_10)) AS total FROM picnic_signup WHERE picnic_semester = \"$semester\" AND picnic_year = $year GROUP BY paid";
	$result = mysql_query($query, $link);
	
	if(mysql_num_rows($result))
	{
		return fetch_all_from_result($result);
	}
	else
	{
		return array(array("paid" => "Paid", "total" => 0), array("paid" => "Unpaid", "total" => 0));
	}
}

function get_drinking_data($semester, $year)
{
	$link = db_connect();
	
	$query="SELECT pd.id, pd.description, SUM(ps.num_attending - ps.num_under_10) AS count FROM picnic_signup AS ps INNER JOIN picnic_drinks AS pd ON ps.drink = pd.id WHERE ps.drink != 0 AND picnic_semester = \"$semester\" AND picnic_year = $year GROUP BY ps.drink";
	$result = mysql_query($query, $link);
	
	if(mysql_num_rows($result))
	{
		return fetch_all_from_result($result);
	}
	else
	{
		$drink_prefs = get_drink_preferences();
		for($i = 0; $i < count($drink_prefs); $i++)
		{
			$drink_prefs[$i]["count"] = 0;
		}
		return $drink_prefs;
	}
}

function get_people_needing_rides($semester, $year)
{
	$link = db_connect();
	
	$query="SELECT user_name FROM picnic_signup WHERE ride = 1 AND picnic_semester = \"$semester\" AND picnic_year = $year ORDER BY user_name";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

function get_officer_collection_summary($semester, $year)
{
	$link = db_connect();
	
	$query="SELECT SUM(IF(paid = 1, (num_attending - num_under_10)*(IF(grad_student = 1, " . get_grad_student_price() . ", " . get_faculty_staff_price() . ") - IF(discount = 1, " . get_signup_discount() . ", 0) - IF(dish > 0, " . get_food_discount() . ", 0)), 0)) AS final_price, updated_by AS officer_username FROM picnic_signup WHERE updated_by != \"\" AND picnic_semester = \"$semester\" AND picnic_year = $year GROUP BY officer_username ORDER BY updated_by";
	$result = mysql_query($query, $link);
	
	return fetch_all_from_result($result);
}

?>