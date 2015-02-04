<?php
include_once("jeopardy_lib.php");

$method = get_str_value($_REQUEST, "method");

if($method == "get_scores")
{   
    print(__json_encode(get_scores()));
    exit(0);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Scoreboard</title>
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
            $(".score_table").append($("<tr><td class=\"team_name\"></td><td class=\"score_title\">Round 1</td><td class=\"score_title\">Round 2</td><td class=\"score_title\">Final Jeopardy</td><td class=\"score_title\">Total Score</td></tr>"));
            $.each(response, function() {
                $(".score_table").append($("<tr><td class=\"team_name\">" + this.team_name + "</td><td class=\"score\">" + this.round_1_score + "</td><td class=\"score\">" + this.round_2_score + "</td><td class=\"score\">" + this.final_jeopardy_score + "</td><td class=\"score\">" + this.total_score + "</td></tr>"));
            });
            setTimeout("load_scores()", 2000);
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}
</script>
<style type="text/css">
    .slide_layout
    {
        text-align: left;
        width: 965px;
        height: 711px;
        padding-right: 15px;
        margin: 0px auto;
        position: relative;
        color: #FFFFFF;
    }
    
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
    }
</style>
</head>
<body style="text-align: center; font-family: Arial; background: #00144F;">
    <div class="slide_layout">
        <div style="text-align: center; margin-bottom: 20px; font-size: 35px; font-weight: bold;">Scoreboard</div>
        <table class="score_table" align="center" cellspacing="0" cellpadding="0">
        </table>
    </div>
</body>
</html>