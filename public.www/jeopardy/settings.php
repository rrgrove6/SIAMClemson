<?php
include_once("jeopardy_lib.php");
    
$method = get_str_value($_REQUEST, "method");

if($method == "update_question_status")
{
    $status = get_str_value($_REQUEST, "question_status");
    set_question_status($status);
    print(__json_encode("status updated successfully"));
    exit(0);
}
else if($method == "update_current_round")
{
    $current_round = get_str_value($_REQUEST, "current_round");
    set_current_round($current_round);
    print(__json_encode("current round updated successfully"));
    exit(0);
}
else if($method == "update_current_category")
{
    $current_category = get_str_value($_REQUEST, "current_category");
    set_current_category($current_category);
    print(__json_encode("current category updated successfully"));
    exit(0);
}
else
{
    $current_round_html = get_html_dropdown(array(1, 2, 3), array("Round 1", "Round 2", "Final Jeopardy"), "current_round", get_current_round(), " id=\"current_round\"");
    $question_status_html = get_html_dropdown(array("closed", "open"), array("closed", "open"), "question_status", get_question_status(), " id=\"question_status\"");

    $category_list = get_all_categories();
    $values = array();
    $display_text = array();
    
    foreach($category_list as $category)
    {
        $values[] = $category["category_id"];
        $display_text[] = "(Round $category[round]) $category[title]";
    }
    
    $current_category_html = get_html_dropdown($values, $display_text, "current_category", get_current_category(), " id=\"current_category\"");
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Settings</title>
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    // start checking to see if there are responses available
    check_for_responses();
    
    // wire up onchange event for the dropdowns
    $("#question_status").change(update_question_status);
    $("#current_round").change(update_current_round);
    $("#current_category").change(update_current_category);
});

function check_for_responses()
{
    return 0; // shortcut for now

    var data = {method: "get_question_status"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'questions.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            //alert(response);
            if (response == "open")
            {
                if(!questions_showing)
                {
                    show_questions();
                }
                else
                {
                    // wait a bit and check to see if we should collect the responses
                    setTimeout("check_for_question_status()", 2000);
                }
            }
            else if(response == "closed")
            {
                if(questions_showing && !responses_sent)
                {
                    send_responses();
                }
                else
                {
                    // wait a bit and check to see if we can get the questions
                    setTimeout("check_for_question_status()", 2000);
                }
            }
            else
            {
                // something has gone wrong
            }
        },
        error: function(response, status, xml) {
            alert("error:");
            alert(JSON.stringify(response));
        }
    });
}


function update_question_status()
{   
    var data = {method: "update_question_status", question_status: $("#question_status").val()};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'settings.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            // it was updated
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}

function update_current_round()
{
    var data = {method: "update_current_round", current_round: $("#current_round").val()};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'settings.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            // it was updated
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}

function update_current_category()
{
    var data = {method: "update_current_category", current_category: $("#current_category").val()};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'settings.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            // it was updated
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}
</script>
</head>
<body style="text-align: center; font-family: Arial;">
    <div class="slide_layout">
        <div><a href="admin.php">Admin Page</a></div>
        <div id="category_title">Settings</div>
        <table align="center" style="margin-top: 20px;">
            <tr>
                <td>Question status: </td>
                <td><?php print($question_status_html); ?></td>
            </tr>
            <tr>
                <td>Current round: </td>
                <td><?php print($current_round_html); ?></td>
            </tr>
            <tr>
                <td>Current category: </td>
                <td><?php print($current_category_html); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
}
?>