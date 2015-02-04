<?php
include_once("balderdash_lib.php");

$method = get_str_value($_REQUEST, "method");

if($method == "get_scores")
{
    $teams = get_teams();
    $scores = get_all_scores();
    
    $output = array();
    
    foreach($teams as $team)
    {
        $score_array = $scores[$team["team_id"]];
        $output[] = array("team_name" => $team["team_name"], "scores" => $score_array, "total_score" => array_sum($score_array));
    }
    
    print(__json_encode($output));
    exit(0);
}
?>

<!doctype html>
<html>
<head>
    <title>Balderdash Scoreboard</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    // start checking the scores
    load_scores();
});

function load_scores()
{
    var data = {method: "get_scores"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'scoreboard.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $(".score_table > tbody").remove();
            $(".score_table").append($("<tr><td class=\"team_name\"></td><td class=\"score_title\">Round 1</td><td class=\"score_title\">Round 2</td><td class=\"score_title\">Round 3</td><td class=\"score_title\">Round 4</td><td class=\"score_title\">Round 5</td><td class=\"score_title\">Total Score</td></tr>"));
            $.each(response, function() {
                $(".score_table").append($("<tr><td class=\"team_name\">" + this.team_name + "</td><td class=\"score\">" + this.scores[1] + "</td><td class=\"score\">" + this.scores[2] + "</td><td class=\"score\">" + this.scores[3] + "</td><td class=\"score\">" + this.scores[4] + "</td><td class=\"score\">" + this.scores[5] + "</td><td class=\"score\">" + this.total_score + "</td></tr>"));
            });
            setTimeout("load_scores()", 2000);
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}
</script>
<link  rel="stylesheet" href="balderdash.css" type="text/css">
<style type="text/css">   
    .score_title
    {
        text-align: center;
        font-weight: bold;
    }
    
    .score
    {
        text-align: right;
        width: 90px;
        font-size: 20px;
    }
    
    .team_name
    {
        font-size: 20px;
        padding-right: 5px;
        padding-left: 5px;
    }
    
    .score_table
    {
        border: solid 3px #FFFFFF;
       -moz-border-radius: 10px;
        border-radius: 10px;
    }
    
    .score_table td
    {
        border: solid 1px #FFFFFF;
        padding: 6px;
        height: 40px;
    }
</style>
</head>
<body style="text-align: center; font-family: Arial; color: #FFFFFF; background: #563397;">
    <div class="slide_layout">
        <div class="admin_navigation"><a href="responses.php" target="answer_tab">Answer Questions</a> <a href="voting.php" target="voting_tab">Vote on Answers</a></div>
        <div style="text-align: center; margin-bottom: 20px; font-size: 35px; font-weight: bold;">Scoreboard</div>
        <table class="score_table" align="center" cellspacing="0" cellpadding="0">
        </table>
    </div>
</body>
</html>