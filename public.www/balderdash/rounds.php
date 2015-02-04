<?php
include_once("balderdash_lib.php");

$method = get_str_value($_REQUEST, "method");

if($method == "get_rounds")
{
    print(__json_encode(get_rounds()));
    exit(0);
}
?>
<!doctype html>
<html>
<head>
    <title>Balderdash Rounds</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    load_rounds();
});

function load_rounds()
{
    var data = {method: "get_rounds"};
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'rounds.php',
        async: true,
        data: data,
        success: function (response, status, xml) {
            $(".round_table > tbody").remove();
            $.each(response, function() {
                var round_str = "Round " + this.round;
                $(".round_table").append($("<tr><td><a class=\"chosen_" + this.chosen + "\" href=\"questions.php?round=" + this.round + "\">" + round_str + "</a></td></tr>"));
            });
        },
        error: function(response, status, xml) {
            alert(JSON.stringify(response));
        }
    });
}
</script>
<link  rel="stylesheet" href="balderdash.css" type="text/css">
<style type="text/css">  
    .chosen_0
    {
        color: #FFFFFF;
        text-decoration: none;
        font-weight: bold;
    }
    
    .chosen_1
    {
        color: #563397;
        text-decoration: none;
        font-weight: bold;
    }
    
    .round_table td
    {
        text-align: center;
        padding: 10px;
        width: 125px;
        height: 50px;
        vertical-align: center;
        border: solid 2px #FFFFFF;
       -moz-border-radius: 10px;
        border-radius: 10px;
    }
</style>
</head>
<body>
    <div class="slide_layout">
        <div style="text-align: center; margin-bottom: 20px; font-size: 35px; font-weight: bold;">Balderdash</div>
        <table class="round_table" align="center" cellspacing="15" cellpadding="0">
        </table>
        <div style="text-align: center; margin-top: 30px;"><a href="admin_scoreboard.php" style="color: #FFFFFF;">Scoreboard</a></div>
    </div>
</body>
</html>