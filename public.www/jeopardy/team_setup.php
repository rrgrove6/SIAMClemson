<?php
include_once("jeopardy_lib.php");

$method = get_str_value($_REQUEST, "method");

if($method == "update_teams")
{
    $teams = get_teams();
    
    foreach($teams as $team)
    {
        $username = get_str_value($_REQUEST, "team_" . $team["team_id"]);
        update_team($team["team_id"], $username);
    }
    
    redirect("admin.php");
    exit(0);
}

$teams = get_teams();

$team_html = "";

foreach($teams as $team)
{
    $team_html .= "<tr><td>$team[team_name]:</td><td><input type=\"text\" name=\"team_$team[team_id]\" size=\"10\" value=\"$team[username]\"></td></tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Team Setup</title>
    <style type="text/css">
    .team_table td
    {
        padding: 6px;
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
<body>
    <div class="slide_layout">
        <div><a href="admin.php">Admin Page</a></div>
        <div id="category_title">Team Setup</div>
        <form action="team_setup.php" method="POST">
            <table class="team_table" align="center" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="margin-right: 10px; font-weight: bold;">Team Name</td>
                    <td style="font-weight: bold;">Username</td>
                </tr>
                <?php print($team_html); ?>
            </table>
            <input type="hidden" name="method" value="update_teams">
            <div style="text-align: center; margin-top: 15px;"><input type="submit" value="Save Teams"></div>
        </form>
    </div>
</body>
</html>