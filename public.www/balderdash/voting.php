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

if($method == "get_voting_status")
{
    print(__json_encode(get_voting_status()));
}
else if($method == "get_current_choices")
{
    $choices = get_choices_for_team(get_team_id($username), get_current_round());
    $title = "Round " . get_current_round() . " Voting";
    
    // remove the response id so that people can't cheat
    for($i = 0; $i < count($choices); $i++)
    {
        for($j = 0; $j < count($choices[$i]["choices"]); $j++)
        {
            unset($choices[$i]["choices"][$j]["response_id"]);
        }
    }
    
    print(__json_encode(array("choices" => $choices, "title" => $title)));
}
else if($method == "send_votes")
{
    $questions = get_current_questions();
    
    foreach($questions as $question)
    {
        store_vote($question["question_id"], get_team_id($username), get_str_value($_REQUEST, "question_" . $question["question_id"], ""));
    }
    
    print(__json_encode("votes saved"));
}
else
{
?>


<!doctype html>
<html>
<head>
    <title>Balderdash Voting</title>
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
    
    .choices_list
    {
        list-style: none;
    }
    
    .choices_list li
    {
        margin-bottom: 10px;
    }
</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var choices_showing = false;
var votes_sent = false;
$(document).ready(function() {
    // start checking to see if you can vote
    check_for_voting_status();
});

function check_for_voting_status()
{
    var data = {method: "get_voting_status"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'voting.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            //alert(response);
            if (response == "open")
            {
                if(!choices_showing)
                {
                    show_choices();
                }
                else
                {
                    // wait a bit and check to see if we should collect the votes
                    setTimeout("check_for_voting_status()", 2000);
                }
            }
            else if(response == "closed")
            {
                if(choices_showing && !votes_sent)
                {
                    send_votes();
                }
                else
                {
                    // wait a bit and check to see if we can get the choices
                    setTimeout("check_for_voting_status()", 2000);
                }
            }
            else
            {
                // something has gone wrong
                alert("voting_status is empty<br>" + JSON.stringify(response));
            }
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}


function show_choices()
{
    choices_showing = true;
    votes_sent = false;
    
    var data = {method: "get_current_choices"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'voting.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $("#msg_box").hide();
            $("#choices").html("");
            $("#title").html(response.title);
            $.each(response.choices, function() {
            
                var cur_choice = $("<div></div>");
                cur_choice.append($("<div class=\"center card_header\"><span class=\"note\">" + this.category + "</span><div class=\"category\">" + this.question_statement + "</div></div>"));
                
                var vote_html = "";
                
                if(this.choices.length > 0)
                {
                    for(var i = 0; i < this.choices.length; i++)
                    {
                        vote_html += "<li><input type=\"radio\" class=\"choice_btns\" name=\"question_" + this.question_id + "\" id=\"question_" + this.question_id + "_" + this.choices[i].letter + "\" value=\"" + this.choices[i].letter + "\"><label for=\"question_" + this.question_id + "_" + this.choices[i].letter + "\">" + this.choices[i].answer + "</label></li>";
                    }
                
                    vote_html = "<ul class=\"choices_list\">" + vote_html + "</ul>";
                }
                else
                {
                    vote_html = "<div style=\"text-align: center; margin: 15px;\">Your answer was correct you get 3 points.</div>";
                }
                
                cur_choice.append($("<div class=\"center card_body\">" + vote_html + "</div>"));
                
                $("#choices").append(cur_choice);
            });
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
    
    check_for_voting_status();
}

function send_votes()
{
    choices_showing = false;
    votes_sent = true;

    var data = $("#voting_form").serialize();

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'voting.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $("#msg_box").html("<img src=\"check.png\" style=\"width: 25px; height: 25px;\" alt=\"check\">Votes saved");
            $("#msg_box").show();
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
    $(".choice_btns").attr("disabled", "disabled");
    
    check_for_voting_status();
}
</script>
</head>
<body style="text-align: center; font-family: Arial;">
    <div class="slide_layout">
        <div><a href="scoreboard.php" target="scoreboard_tab">Scoreboard</a> <a href="responses.php" target="answer_tab">Answer Questions</a></div>
        <div id="title"></div>
        <div style="min-height: 35px; text-align: center;">
            <div id="msg_box"></div>
        </div>
        <form action="votes.php" method="POST" id="voting_form">
            <div id="choices"></div>
            <input type="hidden" name="method" value="send_votes">
        </form>
    </div>
</body>
</html>

<?php
}
?>