<?php
include_once("balderdash_lib.php");

$round = get_str_value($_REQUEST, "round");
$action = get_str_value($_REQUEST, "action");

$responses = get_responses($round);

if($action == "save_scoring")
{
    // save scores
    foreach($responses as $response)
    {
        $score = get_int_value($_REQUEST, "correct_" . $response["response_id"]);
        $display = get_int_value($_REQUEST, "display_" . $response["response_id"]);
        
        save_score_and_display($response["response_id"], $score, $display);
    }
    
    // create letters to blind the responses
    blind_responses($round);
    
    redirect("admin.php");
}

$correcting_html = "";

$cur_id = 0;

foreach($responses as $response)
{
    if($response["question_id"] == $cur_id)
    {
        $response["question_answer"] = "";
    }
    else
    {
        $cur_id = $response["question_id"];
        $correcting_html .= "<tr><td colspan=\"4\"><hr></td></tr>";
    }
    
    $correct_checked = "";
    if($response["correct"])
    {
        $correct_checked = " checked";
    }
    $correct_checkbox = "<input type=\"checkbox\" value=\"1\" name=\"correct_$response[response_id]\"$correct_checked>";
    
    $display_checked = "";
    if($response["display"])
    {
        $display_checked = " checked";
    }
    $display_checkbox = "<input type=\"checkbox\" value=\"1\" name=\"display_$response[response_id]\"$display_checked>";

    $correcting_html .= "<tr><td>$response[question_answer]</td><td>$response[answer]</td><td style=\"text-align: center;\">$correct_checkbox</td><td style=\"text-align: center;\">$display_checkbox</td></tr>";
}

$title = "Round $round";
?>
<!doctype html>
<html>
<head>
    <title>Balderdash Correcting</title>
    <link  rel="stylesheet" href="balderdash.css" type="text/css">
<style type="text/css">   
    #category_title
    {
        height: 40px;
        font-weight: bold;
        text-align: center;
        font-size: 35px;
    }
    
    .header td
    {
        font-weight: bold;
        padding-top: 8px;
    }
</style>
</head>
<body>
    <div class="slide_layout">
        <form action="correcting.php" method="POST">
            <div class="admin_navigation"><a href="admin.php">Admin Page</a></div>
            <div id="category_title"><?php print($title); ?></div>
            <table align="center" style="margin-top: 20px;" cellspacing="0" cellpadding="4">
                <tr class="header">
                    <td>Correct Answer</td>
                    <td style="max-width: 300px;">Their Response</td>
                    <td style="text-align: center;">Correct</td>
                    <td style="text-align: center;">Display</td>
                </tr>
            <?php print($correcting_html); ?>
            </table>
            <input type="hidden" name="round" value="<?php print($round); ?>">
            <input type="hidden" name="action" value="save_scoring">
            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" value="Save Scoring Decisions">
            </div>
        </form>
    </div>
</body>
</html>