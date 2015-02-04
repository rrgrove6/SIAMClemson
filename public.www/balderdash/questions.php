<?php
include_once("balderdash_lib.php");

$round = get_str_value($_REQUEST, "round");

set_current_round($round);
set_question_status("open");

$questions = get_current_questions();

$question_list_html = "";
$i = 0;

foreach($questions as $question)
{
    $question_list_html .= "<tr><td>$question[category]</td><td>$question[question_statement]</td></tr>";
    $i++;
}

$title = "Round $round";
?>
<!doctype html>
<html>
<head>
    <title>Balderdash Questions</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var timer_running = false;
var timer_id = 0;
var timer_seconds = 0;

function start_timer()
{
    timer_running = true;
    timer_id = setInterval("tic_toc()", 1000);
}

function stop_timer()
{
    timer_running = false;
    clearInterval(timer_id);
}

function reset_timer()
{
    stop_timer();
    // set timer to 0
    timer_seconds = 0;
    update_display();
}

function tic_toc()
{
    // increment the timer by 1
    timer_seconds += 1;
    update_display();
}

function update_display()
{
    $("#seconds").html(pad((timer_seconds % 60).toString(), 2));
    $("#minutes").html(Math.floor(timer_seconds / 60));
}

function pad (str, max)
{
    return str.length < max ? pad("0" + str, max) : str;
}
</script>
<link  rel="stylesheet" href="balderdash.css" type="text/css">
<style type="text/css">  
    .round
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
    
    .questions
    {
        margin-top: 30px;
        border: solid 2px #FFFFFF;
        border-radius: 10px;
       -moz-border-radius: 10px;
        text-align: center;
    }
    
    .questions td
    {
        padding: 20px;
        border: solid 2px #FFFFFF;
        border-radius: 0px;
        -moz-border-radius: 0px;
        border-left: 0px;
        border-top: 0px;
    }

    .questions tr td:last-child
    {
        border-right: 0px;
        height: 50px;
        font-weight: bold;
        font-size: 35px;
    }
    
    .questions tr:last-child td
    {
        border-bottom: 0px;
    }
</style>
</head>
<body>
    <div class="slide_layout">
        <div class="round"><a href="rounds.php" style="text-decoration:none; color: #FFFFFF;"><?php print($title); ?></a></div>
        <table class="questions" align="center" cellspacing="0" cellpadding="0"><?php print($question_list_html); ?></table>
        <div style="text-align: center; margin-top: 30px; font-size: 50px;">
            <span id="minutes">0</span>:<span id="seconds">00</span>
        </div>
         <div style="text-align: center; font-size: 50px;">
            <button type="button" onclick="javascript:start_timer();">start</button>
            <button type="button" onclick="javascript:stop_timer();">stop</button>
            <button type="button" onclick="javascript:reset_timer();">reset</button>
        </div>
    </div>
</body>
</html>