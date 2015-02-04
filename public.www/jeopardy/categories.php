<?php
include_once("jeopardy_lib.php");

$method = get_str_value($_REQUEST, "method");

if($method == "get_categories")
{
    print(__json_encode(get_categories(get_current_round())));
    exit(0);
}

$round= get_str_value($_REQUEST, "round");

set_current_round($round);
set_question_status("closed");

$round_string = ($round == 3) ? "Final Jeopardy" : "Round " . $round ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Categories</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    load_categories();
});

function load_categories()
{
    var data = {method: "get_categories"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'categories.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $(".category_table > tbody").remove();
            $.each(response, function() {
                $(".category_table").append($("<tr><td><a class=\"chosen_" + this.chosen + "\" href=\"questions.php?category_id=" + this.category_id + "\">" + this.title + "</a></td></tr>"));
            });
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
    
    .chosen_0
    {
        color: #FFFFFF;
        text-decoration: none;
        font-weight: bold;
    }
    
    .chosen_1
    {
        color: #00144F;
        text-decoration: none;
        font-weight: bold;
    }
    
    .category_table td
    {
        text-align: center;
        padding: 10px;
        width: 125px;
        height: 100px;
        vertical-align: center;
        border: solid 2px #FFFFFF;
       -moz-border-radius: 10px;
        border-radius: 10px;
    }
</style>
</head>
<body style="text-align: center; font-family: Arial; background: #00144F;">
    <div class="slide_layout">
        <div style="text-align: center; margin-bottom: 20px; font-size: 25px; font-weight: bold;"><a href="rounds.php" style="text-decoration: none; color: #FFFFFF;"><?php print($round_string); ?></a></div>
        <table class="category_table" align="center" cellspacing="15" cellpadding="0">
        </table>
    </div>
</body>
</html>