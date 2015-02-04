<?php
include_once("jeopardy_lib.php");

$category_id = get_str_value($_REQUEST, "category_id");

set_current_category($category_id);
set_question_status("open");

$questions = get_current_questions();

$question_list_html = "";
$i = 0;

foreach($questions as $question)
{
    $value_str = (get_current_round() == 3) ? "" : "\$" . $question["question_value"];
    $question_list_html .= "<tr id=\"value_$i\"><td class=\"value\">$value_str</td></tr><tr id=\"question_$i\" class=\"hidden\"><td class=\"statement\">$question[question_statement]</td></tr>";
    $i++;
}

$category_title = get_current_category_title();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Questions</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var question_count = <?php print(count($questions)); ?>;
var cur_question = 0;
$(document).ready(function() {
    // start rotating the questions
    next_question();
});

function next_question()
{
    // hide all questions
    $("#questions > tbody > tr").hide();
    
    // show the current one
    $("#value_" + cur_question).show()
    $("#question_" + cur_question).show()
    
    cur_question++;
    cur_question = cur_question % question_count;
    
    setTimeout("next_question()", 10000);
}

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
    
    #category_title
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
    
    #questions
    {
        margin-top: 30px;
    }

    .statement
    {
        padding: 15px;
        vertical-align: middle;
        text-align: center;
        font-size: 40px;
        width: 500px;
        height: 500px;
        border: solid 2px #FFFFFF;
       -moz-border-radius: 10px;
        border-radius: 10px;
    }
    
    .value
    {
        width: 70px;
        font-weight: bold;
        font-size: 25px;
    }
    
    .hidden
    {
        /*display: none;*/
    }
</style>
</head>
<body style="text-align: center; font-family: Arial; background: #00144F;">
    <div class="slide_layout">
        <div id="category_title"><a href="categories.php?round=<?php print(get_current_round()); ?>" style="text-decoration:none; color: #FFFFFF;"><?php print($category_title); ?></a></div>
        <table id="questions" align="center" cellspacing="0" cellpadding="0"><?php print($question_list_html); ?></table>
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