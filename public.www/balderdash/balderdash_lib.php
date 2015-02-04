<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";


function get_setting($name)
{
    $link = db_connect();
    $query = "SELECT value FROM balderdash_settings WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}


function set_setting($name, $value)
{
    $link = db_connect();
    $query = "UPDATE balderdash_settings SET value = \"$value\" WHERE property = \"$name\" LIMIT 1";
    
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


function get_current_round()
{
    return get_setting("current_round");
}


function get_voting_status()
{    
    return get_setting("voting_status");
}


function set_voting_status($value)
{
    return set_setting("voting_status", $value);
}


function set_current_round($value)
{
    return set_setting("current_round", $value);
}

function get_all_rounds()
{
    $link = db_connect();
    $query = "SELECT round FROM balderdash_questions GROUP BY round ORDER BY round";
    
    $result = mysql_query($query, $link);
    
    $rounds = fetch_all_from_result($result);
    
    $output = array();
    
    foreach($rounds as $round)
    {
        $output[] = $round["round"];
    }
    
    return $output;
}


function get_questions($round)
{
    // get list of questions for this round
    $link = db_connect();
    $query = "SELECT question_id, category, question_statement FROM balderdash_questions WHERE round = $round";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_current_questions()
{
    return get_questions(get_current_round());
}


function get_questions_and_responses($round, $team_id)
{
    // get list of questions for this category
    $link = db_connect();
    $query = "SELECT IFNULL(br.answer, \"\") AS response, bq.question_id, category, question_statement FROM balderdash_questions AS bq LEFT JOIN balderdash_responses AS br ON br.question_id = bq.question_id AND team_id = $team_id WHERE round = $round";
    
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
    $query = "SELECT team_id FROM balderdash_teams WHERE username = UPPER(\"$username\")";
    
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
    $query = "SELECT team_id, team_name, UPPER(username) AS username FROM balderdash_teams";
    
    $result = mysql_query($query, $link);
    return fetch_all_from_result($result);
}


function update_team($team_id, $username)
{
    $link = db_connect();
    $query = "UPDATE balderdash_teams SET username = UPPER(\"$username\") WHERE team_id = $team_id";
    
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


function store_response($question_id, $team_id, $answer)
{
    $link = db_connect();
    
    // check for responses already (if so send to update)
    $query = "SELECT response_id FROM balderdash_responses WHERE team_id = $team_id AND question_id = $question_id";
    
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        // they have already answered this question
        $response = mysql_fetch_assoc($result);
        update_response($response["response_id"], $question_id, $team_id, $answer);
    }
    else
    {
        $query = "INSERT INTO balderdash_responses (team_id, question_id, answer, correct) VALUES ($team_id, $question_id, \"$answer\", 0)";
        
        $result = mysql_query($query, $link);
    }
}


function update_response($response_id, $question_id, $team_id, $answer)
{
    $link = db_connect();
    $query = "UPDATE balderdash_responses SET team_id = $team_id, question_id = $question_id, answer = \"$answer\" WHERE response_id = $response_id";
    
    $result = mysql_query($query, $link);
}


function store_vote($question_id, $team_id, $vote)
{
    $link = db_connect();
    
    // check for responses already (if so send to update)
    $query = "SELECT vote_id FROM balderdash_votes WHERE team_id = $team_id AND question_id = $question_id";
    
    $result = mysql_query($query, $link);
    
	if(mysql_num_rows($result) == 1)
	{
        // they have already answered this question
		$cur_vote = mysql_fetch_assoc($result);
        update_vote($cur_vote["vote_id"], $question_id, $team_id, $vote);
	}
	else
	{
        $query = "INSERT INTO balderdash_votes (team_id, question_id, vote) VALUES ($team_id, $question_id, \"$vote\")";
        
        $result = mysql_query($query, $link);
	}
}


function update_vote($vote_id, $question_id, $team_id, $vote)
{
    $link = db_connect();
    $query = "UPDATE balderdash_votes SET team_id = $team_id, question_id = $question_id, vote = \"$vote\" WHERE vote_id = $vote_id";
    
    $result = mysql_query($query, $link);
}


function get_rounds()
{
    // get list of rounds
    $link = db_connect();
    $query = "SELECT round, IF(COUNT(response_id) > 0, 1, 0) AS chosen FROM balderdash_questions AS bq LEFT JOIN balderdash_responses AS br ON br.question_id = bq.question_id GROUP BY round ORDER BY round";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_num_correct_definitions($team_id, $round)
{
    // get scores for each team
    $link = db_connect();
    $query = "SELECT SUM(correct) AS num_correct FROM balderdash_responses AS br LEFT JOIN balderdash_questions AS bq ON bq.question_id = br.question_id WHERE team_id = $team_id AND round = $round";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["num_correct"];
}


function get_num_correct_votes($team_id, $round)
{
    // get scores for each team
    $link = db_connect();
    $query = "SELECT COUNT(vote) AS num_correct_votes FROM balderdash_votes AS bv LEFT JOIN balderdash_questions AS bq ON bq.question_id = bv.question_id AND bv.vote = bq.answer_letter WHERE team_id = $team_id AND round = $round";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["num_correct_votes"];
}


function get_num_phony_votes($team_id, $round)
{
    // get scores for each team
    $link = db_connect();
    $query = "SELECT COUNT(vote) AS num_phony_votes FROM balderdash_responses AS br LEFT JOIN balderdash_questions AS bq ON bq.question_id = br.question_id LEFT JOIN balderdash_votes AS bv ON bv.question_id = br.question_id WHERE br.team_id = $team_id AND bq.round = $round AND bv.vote = br.response_letter";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["num_phony_votes"];
}


function get_round_scores($round)
{
    // get scores for each team
    $teams = get_teams();
    
    $output = array();
    
    foreach($teams as $team)
    {
        $team_id = $team["team_id"];
        $output[$team_id] = 3*get_num_correct_definitions($team_id, $round) + 2*get_num_correct_votes($team_id, $round) + get_num_phony_votes($team_id, $round);
    }
    
    return $output;
}


function get_all_scores()
{
    // get scores for each team
    $teams = get_teams();
    
    foreach($teams as $team)
    {
        $output[$team["team_id"]] = array();
    }
    
    $rounds = get_all_rounds();
    
    foreach($rounds as $round)
    {
        $round_scores = get_round_scores($round);
        
        foreach($round_scores as $team_id => $score)
        {
            $output[$team_id][$round] = $score;
        }
    }
    
    return $output;
}


function save_score_and_display($response_id, $score, $display)
{
    $link = db_connect();
    $query = "UPDATE balderdash_responses SET correct = $score, display = $display WHERE response_id = $response_id";
    
    $result = mysql_query($query, $link);
}


function blind_responses($round)
{
    $link = db_connect();
    
    $question_choices = get_choices($round);
    
    foreach($question_choices as $question)
    {
        $letters = "ABCDEFG";
        
        for($i = 0; $i < count($question["choices"]); $i++)
        {
            $response = $question["choices"][$i];
            $letter = $letters[$i];
            if($response["response_id"] == 0)
            {
                // this is the correct answer
                $query = "UPDATE balderdash_questions SET answer_letter = \"$letter\" WHERE question_id = $question[question_id]";
            }
            else
            {
                $query = "UPDATE balderdash_responses SET response_letter = \"$letter\" WHERE response_id = $response[response_id]";
            }
            
            mysql_query($query, $link);
        }
    }
}


function get_responses($round)
{
    $link = db_connect();
    $query = "SELECT response_id, bq.question_id, question_answer, answer, correct, display FROM balderdash_responses AS br LEFT JOIN balderdash_questions AS bq ON bq.question_id = br.question_id WHERE bq.round = $round ORDER BY bq.question_id, br.answer";
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}


function get_choices($round)
{
    $link = db_connect();
    
    $questions = get_questions($round);
    
    $choices = array();
    
    foreach($questions as $question)
    {
        $query = "(SELECT br.response_id, answer, br.response_letter AS letter FROM balderdash_responses AS br LEFT JOIN balderdash_questions AS bq ON bq.question_id = br.question_id WHERE br.question_id = $question[question_id] AND br.display = True AND correct = False) UNION (SELECT 0, question_answer, answer_letter FROM balderdash_questions WHERE question_id = $question[question_id]) ORDER BY answer";
        
        $result = mysql_query($query, $link);
        
        $choices[] = array("question_id" => $question["question_id"], "category" => $question["category"], "question_statement" => $question["question_statement"], "choices" => fetch_all_from_result($result));
    }
    
    return $choices;
}


function get_choices_for_team($team_id, $round)
{
    $link = db_connect();
    
    $query = "SELECT br.question_id, correct FROM balderdash_questions AS bq LEFT JOIN balderdash_responses AS br ON br.question_id = bq.question_id WHERE round = $round and br.team_id = $team_id";
    
    $result = mysql_query($query, $link);
        
    $scoring = fetch_all_from_result($result);
    $score_sheet = array();
    
    foreach($scoring as $score)
    {
        $score_sheet[$score["question_id"]] = $score["correct"];
    }
    
    $choices = get_choices($round);
    
    for($i = 0; $i < count($choices); $i++)
    {
        // remove choices if this team provided the correct definition
        if($score_sheet[$choices[$i]["question_id"]] == 1)
        {
            $choices[$i]["choices"] = array();
        }
    }
    
    return $choices;
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