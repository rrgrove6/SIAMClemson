<?php
include_once("balderdash_lib.php");

// this person should be one of the valid game players
$username = get_str_value($_SERVER, "REMOTE_USER");

if(!is_valid($username))
{
    $html_str = <<<EOT
<!doctype html>
<html>
<head>
    <title>Balderdash Error</title>
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

if($method == "get_question_status")
{
    print(__json_encode(get_question_status()));
}
else if($method == "get_current_questions")
{
    $questions = get_questions_and_responses(get_current_round(), get_team_id($username));
    $title = "Round " . get_current_round() . " Questions";
    
    print(__json_encode(array("questions" => $questions, "title" => $title)));
}
else if($method == "send_responses")
{
    $questions = get_current_questions();
    
    foreach($questions as $question)
    {
        store_response($question["question_id"], get_team_id($username), get_str_value($_REQUEST, "response_" . $question["question_id"]));
    }
    
    print(__json_encode("responses saved"));
}
else
{
?>


<!doctype html>
<html>
<head>
    <title>Balderdash Questions</title>
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
    
    #title
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
    
    #msg_box
    {
        display: none;
        width: 200px;
        margin: 0px auto;
        padding: 2px;
        margin: 10px;
        background: #FFFF99;
        border: 2px solid #990000;
    }
    
    .center
    {
        margin: 0px auto;
    }
    
    #questions
    {
        padding-top: 8px;
        padding-bottom: 8px;
    }
    
    .card_header
    {
        border: solid 2px #563397;
        padding: 10px;
        padding-top: 5px;
        width: 500px;
    }
    
    .note
    {
        float: left;
        margin-right: 20px;
        color: #563397;
    }
    
    .category
    {
        font-weight: bold;
        font-size: 40px;
        text-align: center;
    }

    .card_body
    {
        width: 500px;
        border: solid 2px #563397;
        border-top: 0px;
        padding: 10px;
        padding-top: 5px;
        margin-bottom: 40px;
    }
    
    .statement
    {
        width: 450px;
        vertical-align: top;
        padding-right: 15px;
    }
    
    .card_body textarea
    {
        margin-top: 5px;
        width: 490px;
        height: 120px;
    }
</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var questions_showing = false;
var responses_sent = false;
$(document).ready(function() {
    // start checking to see if there are questions available
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
                alert(JSON.stringify(response));
            }
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}


function show_questions()
{
    questions_showing = true;
    responses_sent = false;
    
    var data = {method: "get_current_questions"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'responses.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $("#msg_box").hide();
            $("#questions").html("");
            $("#title").html(response.title);
            $.each(response.questions, function() {
            
                var cur_question = $("<div></div>");
                cur_question.append($("<div class=\"center card_header\"><span class=\"note\">" + this.category + "</span><div class=\"category\">" + this.question_statement + "</div></div>"));
                cur_question.append($("<div class=\"center card_body\"><span class=\"note\">Definition</span><textarea maxlength=\"500\" class=\"response_text\" name=\"response_" + this.question_id + "\">" + this.response + "</textarea></div>"));
                
                $("#questions").append(cur_question);
            });
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
    
    check_for_question_status();
}

function send_responses()
{
    questions_showing = false;
    responses_sent = true;

    var data = $("#response_form").serialize();

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'responses.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $("#msg_box").html("<img src=\"check.png\" style=\"width: 25px; height: 25px;\" alt=\"check\">Responses saved");
            $("#msg_box").show();
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
    $(".response_text").attr("disabled", "disabled");
    
    check_for_question_status();
}
</script>
</head>
<body style="text-align: center; font-family: Arial;">
    <div class="slide_layout">
        <div><a href="scoreboard.php" target="scoreboard_tab">Scoreboard</a> <a href="voting.php" target="voting_tab">Vote on Answers</a></div>
        <div id="title"></div>
        <div style="min-height: 35px; text-align: center;">
            <div id="msg_box"></div>
        </div>
        <form action="responses.php" method="POST" id="response_form">
            <div id="questions"></div>
            <input type="hidden" name="method" value="send_responses">
        </form>
    </div>
</body>
</html>

<?php
}
?>