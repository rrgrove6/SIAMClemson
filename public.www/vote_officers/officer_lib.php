<?php
error_reporting(E_ALL);

include_once "/cifsmounts/EH01/users/siam/public.www/common/database.php";
include_once "/cifsmounts/EH01/users/siam/public.www/common/general_funcs.php";

function get_setting($name)
{
    $link = db_connect();
    $query = "SELECT value FROM vote_officer_settings WHERE property = \"$name\" LIMIT 1";
    
    $result = mysql_query($query, $link);
    
    $row = mysql_fetch_assoc($result);
    
    return $row["value"];
}

function get_current_election()
{    
    return get_setting("current_election");
}

function is_voter_eligible($username)
{
    $link = db_connect();
	$query = "SELECT COUNT(username) AS is_valid FROM vote_officer_voter_list WHERE username = \"$username\" AND voted = 0 AND election_id = " . get_current_election();
	$result = mysql_query($query, $link);
	$row = mysql_fetch_assoc($result);
	
	if($row['is_valid'] == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_candidates()
{
    $link = db_connect();
    $query = "SELECT * FROM vote_officer_candidate_list WHERE election_id = " . get_current_election() . " ORDER BY FIELD(office, \"President\", \"Vice President\", \"Treasurer\", \"Secretary\"), RAND()";
    $result = mysql_query($query, $link);
    
    $candidates = fetch_all_from_result($result);
    
    $output = array();
    
    foreach($candidates as $candidate)
    {
        if(!array_key_exists($candidate["office"], $output))
        {
            $output[$candidate["office"]] = array();
        }

        $output[$candidate["office"]][] = array("first_name" => $candidate["first_name"], "last_name" => $candidate["last_name"], "photo" => $candidate["photo"], "candidate_id" => $candidate["candidate_id"]);
    }
    
    return $output;
}

function add_new_vote($username, $pres_prefs, $vp_prefs, $treas_prefs, $sec_prefs)
{
    $link = db_connect();
    $query = "SELECT voter_id FROM vote_officer_voter_list WHERE username = \"$username\" AND election_id = " . get_current_election();
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        $voter_id = $row["voter_id"];
    
        // mark this person as having voted
        $query = "UPDATE vote_officer_voter_list SET voted = 1 WHERE username = \"$username\" AND election_id = " . get_current_election();
        $result = mysql_query($query, $link);
        
        // store the preferences for each office
        
        for($i = 0; $i < count($pres_prefs); $i++)
        {
            $query = "INSERT INTO vote_officer_voting_results (voter_id, candidate_id, rank) VALUES ($voter_id, " . $pres_prefs[$i] . ", " . ($i + 1) . ")";
            $result = mysql_query($query, $link);
        }
        
        for($i = 0; $i < count($vp_prefs); $i++)
        {
            $query = "INSERT INTO vote_officer_voting_results (voter_id, candidate_id, rank) VALUES ($voter_id, " . $vp_prefs[$i] . ", " . ($i + 1) . ")";
            $result = mysql_query($query, $link);
        }
        
        for($i = 0; $i < count($treas_prefs); $i++)
        {
            $query = "INSERT INTO vote_officer_voting_results (voter_id, candidate_id, rank) VALUES ($voter_id, " . $treas_prefs[$i] . ", " . ($i + 1) . ")";
            $result = mysql_query($query, $link);
        }
        
        for($i = 0; $i < count($sec_prefs); $i++)
        {
            $query = "INSERT INTO vote_officer_voting_results (voter_id, candidate_id, rank) VALUES ($voter_id, " . $sec_prefs[$i] . ", " . ($i + 1) . ")";
            $result = mysql_query($query, $link);
        }
        
        return True;
    }
    
    return False;
}

function add_new_approval($username)
{
    $link = db_connect();
    $query = "SELECT voter_id FROM vote_officer_voter_list WHERE username = \"$username\" AND election_id = " . get_current_election();
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        $voter_id = $row["voter_id"];
    
        // mark this person as having voted
        $query = "UPDATE vote_officer_voter_list SET voted = 1 WHERE username = \"$username\" AND election_id = " . get_current_election();
        $result = mysql_query($query, $link);
        
        return True;
    }
    
    return False;
}

function get_num_candidates($office)
{
    $link = db_connect();
    $query = "SELECT COUNT(candidate_id) AS num_candidates FROM vote_officer_candidate_list WHERE office = \"$office\" AND election_id = " . get_current_election();
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        return $row["num_candidates"];
    }
    
    return 0;
}

function get_voting_data($office)
{
    $link = db_connect();
    $num_candidates = get_num_candidates($office);

    $query = "SELECT ";
    
    $pieces = array();

    for($i = 1; $i <= $num_candidates; $i++)
    {
        $pieces[] = " (SELECT c.candidate_id FROM vote_officer_voting_results AS r LEFT JOIN vote_officer_candidate_list AS c ON c.candidate_id = r.candidate_id WHERE r.voter_id = v.voter_id AND office = \"$office\" AND r.rank = $i) AS pref_$i";
    }

    $query .= implode(",\n", $pieces) . "\n";

    $query .= "FROM vote_officer_voter_list AS v WHERE voted = 1 AND election_id = " . get_current_election();
    
    $result = mysql_query($query, $link);
    
    return fetch_all_from_result($result);
}

function get_candidate_name($candidate_id)
{
    $link = db_connect();
    $query = "SELECT first_name, last_name FROM vote_officer_candidate_list WHERE candidate_id = $candidate_id";
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        return $row["first_name"] . " " . $row["last_name"];
    }
    
    return "";
}

function get_number_of_votes()
{
    $link = db_connect();
    $query = "SELECT COUNT(voter_id) AS total FROM vote_officer_voter_list WHERE voted = 1 AND election_id = " . get_current_election();
    $result = mysql_query($query, $link);
    
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        return $row["total"];
    }
    
    return -1;
}
?>