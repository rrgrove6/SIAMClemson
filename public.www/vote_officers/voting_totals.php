<?php
include_once "officer_lib.php";

function removed_candidate($val)
{
    return ($val != -1);
}

function sanitize_array($data)
{
    for($i = 0; $i < count($data); $i++)
    {
        $data[$i] = array_values($data[$i]);
    }
    
    return $data;
}

function get_losers($data)
{
    $cand_totals = array();
    
    for($i = 0; $i < count($data); $i++)
    {
        $cand_id = $data[$i][0];
        if(!array_key_exists($cand_id, $cand_totals))
        {
            $cand_totals[$cand_id] = 1;
        }
        else
        {
            $cand_totals[$cand_id] += 1;
        }
    }
    
    $min = min($cand_totals);
    // here we are getting rid of possibly multiple candidates at the same time
    return array_keys($cand_totals, $min);
}

function get_pref_totals($data)
{
    $pref_order = array();
    $num_candidates = count($data[0]);
    
    while($num_candidates > 0)
    {
        // try to eliminate a candidate
        $elim_candidates = get_losers($data);
        $pref_order[] = $elim_candidates;
        
        for($i = 0; $i < count($data); $i++)
        {
            for($j = 0; $j < count($data[$i]); $j++)
            {
                if(in_array($data[$i][$j], $elim_candidates))
                {
                    $data[$i][$j] = -1;
                }
            }
            $data[$i] = array_values(array_filter($data[$i], "removed_candidate"));
        }
        
        $num_candidates -= count($elim_candidates);
    }
    
    return array_reverse($pref_order);
}

function print_prefs($prefs)
{
    print("<ol>");
    for($i = 0; $i < count($prefs); $i++)
    {
        print("<li>");
        $names = array();
        for($j = 0; $j < count($prefs[$i]); $j++)
        {
            $names[] = get_candidate_name($prefs[$i][$j]);
        }
        print(implode(",", $names));
        print("</li>");
    }
    print("</ol>");
}

function get_point_totals($data)
{
    $cand_totals = array();
    $max_points = count($data[0]);
    
    for($i = 0; $i < count($data); $i++)
    {
        for($j = 0; $j < $max_points; $j++)
        {
            $cand_id = $data[$i][$j];
            if(!array_key_exists($cand_id, $cand_totals))
            {
                $cand_totals[$cand_id] = $max_points - $j;
            }
            else
            {
                $cand_totals[$cand_id] += $max_points - $j;
            }
        }
    }
    
    asort($cand_totals);
    
    return array_reverse($cand_totals, true);
}

function print_points($prefs)
{
    print("<ol>");
    foreach($prefs as $cand_id => $total)
    {
        print("<li>");
        print(get_candidate_name($cand_id) . " ($total)");
        print("</li>");
    }
    print("</ol>");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Voting Results</title>
    <style type="text/css">
    .title
    {
        text-align: center;
        font-weight: bold;
        font-size: 28px;
        font-family: Arial;
    }
    </style>
</head>
<body>
<?php
print("<div class=\"title\">Election Data</div>");
print("<p>" . get_number_of_votes() . " votes cast.</p><hr>");

print("<div class=\"title\">Sequential Runoff</div>");

print("<div><div>President</div>");
print_prefs(get_pref_totals(sanitize_array(get_voting_data("President"))));
print("</div>");

print("<div><div>Vice President</div>");
print_prefs(get_pref_totals(sanitize_array(get_voting_data("Vice President"))));
print("</div>");

print("<div><div>Treasurer</div>");
print_prefs(get_pref_totals(sanitize_array(get_voting_data("Treasurer"))));
print("</div>");

print("<div><div>Secretary</div>");
print_prefs(get_pref_totals(sanitize_array(get_voting_data("Secretary"))));
print("</div>");

print("<hr><div class=\"title\">Point System</div>");

print("<div><div>President</div>");
print_points(get_point_totals(sanitize_array(get_voting_data("President"))));
print("</div>");

print("<div><div>Vice President</div>");
print_points(get_point_totals(sanitize_array(get_voting_data("Vice President"))));
print("</div>");

print("<div><div>Treasurer</div>");
print_points(get_point_totals(sanitize_array(get_voting_data("Treasurer"))));
print("</div>");

print("<div><div>Secretary</div>");
print_points(get_point_totals(sanitize_array(get_voting_data("Secretary"))));
print("</div>");
?>
</body>
</html>