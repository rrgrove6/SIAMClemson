<?php
include_once("jeopardy_lib.php");

$category_id = get_str_value($_REQUEST, "category_id");
$action = get_str_value($_REQUEST, "action");

$responses = get_responses($category_id);

if($action == "save_scores")
{
    // save scores
    foreach($responses as $response)
    {
        $score = get_int_value($_REQUEST, "response_" . $response["response_id"]);
        
        save_score($response["response_id"], $score);
    }
    
    redirect("admin.php");
}

$scoring_html = "";

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
        $scoring_html .= "<tr><td colspan=\"3\"><hr></td></tr>";
    }
    $dropdown_html = get_html_dropdown(array(0, -1, 1), array("no response", "incorrect", "correct"), "response_" . $response["response_id"], $response["scoring"]);
    $scoring_html .= "<tr><td>$response[question_answer]</td><td>$response[answer]</td><td style=\"text-align: center;\">$dropdown_html</td></tr>";
}

$title = get_category_title($category_id);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Scoring</title>
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
    
    .header td
    {
        font-weight: bold;
        padding-top: 8px;
    }
</style>
</head>
<body style="text-align: center; font-family: Arial;">
    <div class="slide_layout">
        <form action="scoring.php" method="POST">
            <div><a href="admin.php">Admin Page</a></div>
            <div id="category_title"><?php print($title); ?></div>
            <table align="center" style="margin-top: 20px;" cellspacing="0" cellpadding="4">
                <tr class="header">
                    <td>Correct Answer</td>
                    <td style="max-width: 300px;">Their Response</td>
                    <td style="text-align: center;">Scoring Decision</td>
                </tr>
            <?php print($scoring_html); ?>
            </table>
            <input type="hidden" name="category_id" value="<?php print($category_id); ?>">
            <input type="hidden" name="action" value="save_scores">
            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" value="Save Scoring Decisions">
            </div>
        </form>
    </div>
</body>
</html>