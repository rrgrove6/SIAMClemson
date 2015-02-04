<?php
include_once "../shirt_lib.php";

// these functions came from the preference voting for officers page
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
        if(count($data[$i]) > 0)
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
            $color = get_color($prefs[$i][$j]);
            $names[] = "$color[shirt_color] shirt/$color[text_color] text";
        }
        print(implode(",", $names));
        print("</li>");
    }
    print("</ol>");
}

?>
<!doctype html>
<html>
<head>
    <title>Shirt Stats</title>
    <style type="text/css">
    .title
    {
        font-family: Arial;
        font-weight: bold;
        font-size: 20px;
        margin-top: 25px;
        margin-bottom: 10px;
    }
    
    .outline
    {
        border-spacing: 0px;
        border-collapse: collapse;
    }
    
    .outline td
    {
        border: solid 2px #000000;
        padding: 4px;
    }
    
    .outline tr:first-child>td
    {
        font-weight: bold;
        font-size: 16px;
    }
    
    .voting_table td:nth-child(2)
    {
        text-align: center;
    }
    
    .color_prefs_table td
    {
        text-align: center;
    }
    </style>
</head>
<body>
<?php
if(get_str_value($_REQUEST, "func") == "vote_totals")
{
?>
<div class="title">Design Voting</div>
<table class="outline voting_table">
    <tr>
    <td>Design</td>
    <td>Votes</td>
    </tr>
<?php
    $voting_data = get_voting_data(get_current_contest());
    
    foreach($voting_data as $design)
    {
        printf("<tr><td>%s</td><td>%s</td></tr>", $design["display_description"], $design["votes"]);
    }
?>
</table>

<div class="title">Color Preferences (Point System)</div>
<table class="outline color_prefs_table">
    <tr>
        <td>Shirt Color</td>
        <td>Text Color</td>
        <td>Votes</td>
    </tr>
<?php
    $point_data =get_color_prefs_point_system(get_current_contest());
    
    foreach($point_data as $color)
    {
        printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $color["shirt_color"], $color["text_color"], $color["points"]);
    }
?>
</table>

<div style="font-family: Arial; font-weight: bold; font-size: 15pt; margin-top: 25px;">Color Preferences (Sequential Runoff)</div>

<?php
print("<div>");
print_prefs(get_pref_totals(sanitize_array(get_color_prefs_data(get_current_contest()))));
print("</div>");
} // end of vote_totals function
?>

</body>
</html>