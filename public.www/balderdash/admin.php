<?php
include_once("balderdash_lib.php");

$round_list = get_all_rounds();
$values = array();
$display_text = array();

foreach($round_list as $round)
{
    $values[] = $round;
    $display_text[] = "Round $round";
}

$round_dropdown_html = get_html_dropdown($values, $display_text, "round", get_current_round(), " id=\"round\"");
?>
<!doctype html>
<html>
<head>
    <title>Balderdash Admin</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
    <link  rel="stylesheet" href="balderdash.css" type="text/css">
    <style type="text/css">
    .title
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
    
    .admin_navigation li
    {
        margin-bottom: 10px;
    }
    </style>
<script type="text/javascript">
    function grade()
    {
        var round = $("#round").val();
        window.location = "correcting.php?round=" + round;
    }
</script>
</head>
<body>
    <div class="slide_layout">
        <div class="title">Balderdash Admin</div>
        <ul class="admin_navigation">
            <li><a href="team_setup.php">Team Setup</a></li>
            <li><a href="settings.php">Game Settings</a></li>
            <li><?php print($round_dropdown_html); ?> <button type="button" onclick="javascript:grade();">Mark Correct Answers</button></li>
        </ul>
    </div>
</body>
</html>