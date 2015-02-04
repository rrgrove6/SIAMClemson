<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";


function get_setting($name)
{
    $link = db_connect();
    $query = "SELECT value FROM jeopardy_settings WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}


function set_setting($name, $value)
{
    $link = db_connect();
    $query = "UPDATE jeopardy_settings SET value = \"$value\" WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    return True; // we should really be checking to see if this succeeded
}


function get_question_status()
{    
    return get_setting("question_status");
}


function set_question_status($value)
{
    return set_setting("question_status", $value);
}


function get_current_category()
{
    return get_setting("current_category");
}


function set_current_category($value)
{
    return set_setting("current_category", $value);
}


function get_current_round()
{
    return get_setting("current_round");
}


function set_current_round($value)
{
    return set_setting("current_round", $value);
}


function get_category_title($category_id)
{
    $link = db_connect();
    $query = "SELECT title FROM jeopardy_categories WHERE category_id = $category_id";
    
    $result = mysql_query($query, $link);
    
	if(mysql_num_rows($result) == 1)
	{
		$category = mysql_fetch_assoc($result);
        return $category["title"];
	}
	else
	{
		return "";
	}
}


function get_current_category_title()
{
    return get_category_title(get_current_category());
}


function get_questions($category_id)
{
    // get list of questions for this category
    $link = db_connect();
    $query = "SELECT question_id, question_value, question_statement FROM jeopardy_questions WHERE category_id = $category_id";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_current_questions()
{
    return get_questions(get_current_category());
}


function get_questions_and_responses($category_id, $team_id)
{
    // get list of questions for this category
    $link = db_connect();
    $query = "SELECT IFNULL(jr.answer, \"\") AS response, jq.question_id, (IFNULL(jr.wager, 0) + jq.question_value) AS question_value, question_statement FROM jeopardy_questions AS jq LEFT JOIN jeopardy_responses AS jr ON jr.question_id = jq.question_id AND team_id = $team_id WHERE category_id = $category_id ORDER BY jq.question_value";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_team_id($username)
{
    // we don't bother checking if the username is empty since this could be caused by not logging in
    if($username == "")
    {
        return 0;
    }

    $link = db_connect();
    $query = "SELECT team_id FROM jeopardy_teams WHERE username = UPPER(\"$username\")";
    
    $result = mysql_query($query, $link);
    
	if(mysql_num_rows($result) == 1)
	{
		$team = mysql_fetch_assoc($result);
        return $team["team_id"];
	}
	else
	{
		return 0;
	}
}


function get_teams()
{
    $link = db_connect();
    $query = "SELECT team_id, team_name, UPPER(username) AS username FROM jeopardy_teams";
    
    $result = mysql_query($query, $link);
    return fetch_all_from_result($result);
}


function update_team($team_id, $username)
{
    $link = db_connect();
    $query = "UPDATE jeopardy_teams SET username = UPPER(\"$username\") WHERE team_id = $team_id";
    
    $result = mysql_query($query, $link);
}


function is_valid($username)
{
    if(get_team_id($username) == 0)
    {
        return False;
    }
    else
    {
        return True;
    }
}


function store_response($question_id, $team_id, $answer, $wager = 0)
{
    $link = db_connect();
    
    // check for responses already (if so send to update)
    $query = "SELECT response_id FROM jeopardy_responses WHERE team_id = $team_id AND question_id = $question_id";
    
    $result = mysql_query($query, $link);
    
	if(mysql_num_rows($result) == 1)
	{
        // they have already answered this question
		$response = mysql_fetch_assoc($result);
        update_response($response["response_id"], $question_id, $team_id, $answer, $wager);
	}
	else
	{
        $query = "INSERT INTO jeopardy_responses (team_id, question_id, wager, answer, scoring) VALUES ($team_id, $question_id, $wager, \"$answer\", 0)";
        
        $result = mysql_query($query, $link);
	}
}


function update_response($response_id, $question_id, $team_id, $answer, $wager = 0)
{
    $link = db_connect();
    $query = "UPDATE jeopardy_responses SET team_id = $team_id, question_id = $question_id, wager = $wager, answer = \"$answer\" WHERE response_id = $response_id";
    
    $result = mysql_query($query, $link);
}


function get_rounds()
{
    // get list of rounds
    $link = db_connect();
    $query = "SELECT round, IF(SUM(IF((SELECT COUNT(response_id) FROM jeopardy_responses AS jr LEFT JOIN jeopardy_questions AS jq ON jq.question_id = jr.question_id WHERE jq.category_id = jc.category_id) > 0, 1, 0)) > 0, 1, 0) AS chosen FROM jeopardy_categories AS jc GROUP BY round ORDER BY round";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_categories($round)
{
    // get list of categories in this round
    $link = db_connect();
    $query = "SELECT category_id, title, IF((SELECT COUNT(response_id) FROM jeopardy_responses AS jr LEFT JOIN jeopardy_questions AS jq ON jq.question_id = jr.question_id WHERE jq.category_id = jc.category_id) > 0, 1, 0) AS chosen FROM jeopardy_categories AS jc WHERE round = $round ORDER BY category_order";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_all_categories()
{
    $link = db_connect();
    $query = "SELECT category_id, round, title FROM jeopardy_categories ORDER BY round, category_order";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_round_scores($round)
{
    // get scores for each team
    $link = db_connect();
    $query = "SELECT team_name, IFNULL((SELECT SUM((question_value + wager)*scoring) AS total_score FROM jeopardy_responses AS jr LEFT JOIN jeopardy_questions AS jq ON jq.question_id = jr.question_id LEFT JOIN jeopardy_categories AS jc ON jc.category_id = jq.category_id WHERE team_id = jt.team_id AND jc.round = $round), 0) AS score FROM jeopardy_teams AS jt ORDER BY team_name";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_total_scores()
{
    // get scores for each team
    $link = db_connect();
    $query = "SELECT team_name, IFNULL((SELECT SUM((question_value + wager)*scoring) AS total_score FROM jeopardy_responses AS jr LEFT JOIN jeopardy_questions AS jq ON jq.question_id = jr.question_id WHERE team_id = jt.team_id), 0) AS score FROM jeopardy_teams AS jt ORDER BY team_name";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_scores()
{
    $round_1 = get_round_scores(1);
    $round_2 = get_round_scores(2);
    $final_jeopardy = get_round_scores(3);
    $total_scores = get_total_scores();
    
    $combined_scores = array();
    
    for($i = 0; $i < count($total_scores); $i++)
    {
        $combined_scores[] = array("team_name" => $total_scores[$i]["team_name"], "round_1_score" => $round_1[$i]["score"], "round_2_score" => $round_2[$i]["score"], "final_jeopardy_score" => $final_jeopardy[$i]["score"], "total_score" => $total_scores[$i]["score"]);
    }
    
    return $combined_scores;
}


function save_score($response_id, $score)
{
    $link = db_connect();
    $query = "UPDATE jeopardy_responses SET scoring = $score WHERE response_id = $response_id";
    
    $result = mysql_query($query, $link);
}


function get_responses($category_id)
{
    $link = db_connect();
    $query = "SELECT response_id, jq.question_id, question_answer, answer, scoring FROM jeopardy_responses AS jr LEFT JOIN jeopardy_questions AS jq ON jq.question_id = jr.question_id LEFT JOIN jeopardy_categories AS jc ON jc.category_id = jq.category_id WHERE jc.category_id = $category_id ORDER BY question_value";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
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