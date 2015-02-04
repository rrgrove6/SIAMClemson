<?php
include_once("balderdash_lib.php");

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
<!doctype html>
<html>
<head>
    <title>Balderdash Team Setup</title>
    <link  rel="stylesheet" href="balderdash.css" type="text/css">
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
        <div class="admin_navigation"><a href="admin.php">Admin Page</a></div>
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