<?php
include_once("jeopardy_lib.php");

if(get_current_round() != 3)
{
    $html_str = <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Error</title>
</head>
<body>
    <p>Final Jeopardy has not begun yet. Click <a href="responses.php">here</a> to go to the current round.</p>
</body>
</html>
EOT;
    print($html_str);
    exit(0);
}

// this person should be one of the valid game players
$username = get_str_value($_SERVER, "REMOTE_USER");

if(!is_valid($username))
{
    $html_str = <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Error</title>
</head>
<body>
    <p>You logged in as <span style="color: #FF0000; font-weight: bold; font-size: 20px;">$username</span>. Only your team representative can access this page.</p>
</body>
</html>
EOT;
    print($html_str);
    exit(0);
}

$method = get_str_value($_REQUEST, "method");

if($method == "save_wager")
{
    $wager = abs(get_int_value($_REQUEST, "wager"));
    $team_id = get_team_id($username);
    
    // check that the wager is less than or equal to their total score
    $scores = get_total_scores();
    $current_score = $scores[$team_id - 1]["score"];
    
    // HACK: we know team id is the same as team name order shifted by 1
    if($current_score < 0)
    {
        // no wager allowed
        $wager = 0;
    }
    else if($wager > $current_score)
    {
        // you can only wager as much as you have
        $wager = $current_score;
    }
    
    $questions = get_current_questions();
    store_response($questions[0]["question_id"], $team_id, "", $wager);
    print(__json_encode("successfully saved wager"));
    exit(0);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Final Jeopardy Wager</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    // start checking to see if the question is open
    check_for_question_status();
});

function check_for_question_status()
{
    var data = {method: "get_question_status"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'responses.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            if (response == "open")
            {
                save_wager();
            }
            else
            {
                // wait a bit and check to see if we should redirect to the responses page
                setTimeout("check_for_question_status()", 2000);
            }
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}

function save_wager()
{
    var data = {method: "save_wager", wager: $("#wager").val()};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'final_jeopardy.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            // redirect to the responses page
            window.location = "responses.php";
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
        border: solid 1px #FFFFFF;
        margin: 0px auto;
        position: relative;
    }
    
    #category_title
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
</style>
</head>
<body style="text-align: center; font-family: Arial;">
    <div class="slide_layout">
        <div><a href="scoreboard.php" target="_blank">Scoreboard</a> <a href="responses.php">Final Jeopardy</a></div>
        <div id="category_title"></div>
        <p>Enter your wager for Final Jeopardy: $<input type="text" id="wager" size="5"></p>
    </div>
</body>
</html>