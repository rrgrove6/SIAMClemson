<?php
include_once("balderdash_lib.php");
    
$method = get_str_value($_REQUEST, "method");

if($method == "update_question_status")
{
    $status = get_str_value($_REQUEST, "question_status");
    set_question_status($status);
    print(__json_encode("status updated successfully"));
    exit(0);
}
else if($method == "update_voting_status")
{
    $status = get_str_value($_REQUEST, "voting_status");
    set_voting_status($status);
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
else
{   
    $round_list = get_all_rounds();
    $values = array();
    $display_text = array();

    foreach($round_list as $round)
    {
        $values[] = $round;
        $display_text[] = "Round $round";
    }

    $current_round_html = get_html_dropdown($values, $display_text, "current_round", get_current_round(), " id=\"current_round\"");

    $question_status_html = get_html_dropdown(array("closed", "open"), array("closed", "open"), "question_status", get_question_status(), " id=\"question_status\"");
    
    $voting_status_html = get_html_dropdown(array("closed", "open"), array("closed", "open"), "voting_status", get_voting_status(), " id=\"voting_status\"");
?>


<!doctype html>
<html>
<head>
    <title>Balderdash Settings</title>
    <link  rel="stylesheet" href="balderdash.css" type="text/css">
    <style type="text/css">
        .title
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
    // wire up onchange event for the dropdowns
    $("#question_status").change(update_question_status);
    $("#voting_status").change(update_voting_status);
    $("#current_round").change(update_current_round);
});

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
            alert("Question status was updated.");
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}

function update_voting_status()
{
    var data = {method: "update_voting_status", voting_status: $("#voting_status").val()};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'settings.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            // it was updated
            alert("Voting status was updated.");
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
            alert("Current round was updated.");
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
        <div class="admin_navigation"><a href="admin.php">Admin Page</a></div>
        <div class="title">Settings</div>
        <table align="center" style="margin-top: 20px;">
            <tr>
                <td>Question status: </td>
                <td><?php print($question_status_html); ?></td>
            </tr>
            <tr>
                <td>Voting status: </td>
                <td><?php print($voting_status_html); ?></td>
            </tr>
            <tr>
                <td>Current round: </td>
                <td><?php print($current_round_html); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
}
?>