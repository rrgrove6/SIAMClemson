<?php
include_once "../common/template.php";
include_once "shirt_lib.php";
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
    <title>Clemson University SIAM student chapter</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript"> 
 function select_preference(btn)
 {
    // clear out the other preferences for this option
    $("." + $(btn).attr("class")).each(function()
        {
            $(this).prop("checked", false);
        }
    );

    // check this preference for this option
    $(btn).prop("checked", true);
 }

 function preview(id)
 {
      $(".preview").hide();
      $("#image_" + id).show();
      $(".thumb").css("marginBottom", "10px");
      $("#thumb_" + id).css("marginBottom", "25px");
 }
 
 function update_vote_preview()
 {
    $("#display_vote").attr("src", designs[$("#design_pref").val()]);
 }
</script>
<style type="text/css">
.preview
{
    border: solid 2px #000000;
    border-bottom: 0px;
    text-align: center;
    padding-top: 15px;
    padding-bottom: 15px;
    min-height: 500px;
}

.thumb
{
    cursor: pointer;
    border: solid 2px #000000;
    margin: 10px;
    padding: 2px;
}
</style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 1000px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">

<?php
$contest_id = get_current_contest();
$username = get_str_value($_SERVER, "REMOTE_USER");
    
// check if the vote is being submitted
if(get_int_value($_POST, "submit_vote", -1) == 1)
{
    $design_pref = get_int_value($_POST, "design_pref");

    // get prefs from form
    $colors = get_colors($contest_id);
    $color_prefs = array();
    foreach($colors as $color)
    {
        $color_prefs[$color["color_id"]] = 0;
    }
    
    for($i = 1; $i <= count($colors); $i++)
    {
        $temp = get_str_value($_POST, "pref_$i");
        if(strlen($temp) > 0)
        {
            $color_prefs[$temp] = $i;
        }
    }
    
    $design_vote = get_design_vote($contest_id, $username);

    if($design_vote != -1)
    {
        update_vote($contest_id, $username, $design_pref, $color_prefs);
    }
    else
    {
        add_new_vote($contest_id, $username, $design_pref, $color_prefs);
    }
?>

<p>Your vote has been recorded. Remember that if you wish to revise your vote at any time you can log back in <a href="vote_shirt.php">here</a> and change your vote. Voting will end Wednesday, August 27th at 2 PM. The winning design will be announced at the first GSS of the semester, August 27th at 5 PM in Martin M102.</p>

<?php
} // end display of vote confirmation page
else
{	 
    $design_info = get_designs($contest_id);
    
    // randomize the order of the designs everytime
    shuffle($design_info);

    // check if the person has already voted
    $design_vote = get_design_vote($contest_id, $username);
    
    // if they have voted already we will display their choices as selected
    $design_html = "";
    $thumb_html = "";
    $select_design_html = "";
    $designs = array(0 => "undecided.png");
    $vote_preview = "undecided.png";
    $first = True;
    foreach($design_info as $design)
    {
        $selected = "";
        $color = "";
        $border = "";
        if($design["design_id"] == $design_vote) // they have selected this
        {
            $selected = " selected";
            $vote_preview =  $design["image_base_name"] . "_thumb.png";
        }
        
        $display = "none";
        $margin = "10";
        if($first)
        {
            $first = False;
            $display = "";
            $margin = "25";
        }
        
        $design_html .= "<div id=\"image_" . $design["design_id"] . "\" class=\"preview\" style=\"display: $display;\"><img src=\"" . $design["image_base_name"] . "_design.png\"></div>\n";
        $thumb_html .= "<img class=\"thumb\" style=\"margin-bottom: " . $margin . "px;\" id=\"thumb_" . $design["design_id"] . "\" onClick=\"javascript:preview(" . $design["design_id"] . ")\" src=\"" . $design["image_base_name"] . "_thumb.png\">\n";
        $select_design_html .= "<option value=\"" . $design["design_id"] . "\"$selected >" . $design["display_description"] . "</option>\n";
        $designs[$design["design_id"]] = $design["image_base_name"] . "_thumb.png";
    }
?>

<form name="main_form" method="post" action="vote_shirt.php">
<p>The following designs have been submitted for this year's SIAM T-shirt design competition. This year you can vote on two things: the design and the shirt/text color. If you decide to change your vote at any time you can log back into this page and resubmit your vote. However, only the latest vote will count. To view the designs click on the thumbnail images displayed below the main preview window.</p>
<?php print($design_html); ?>
<div style="text-align: center; border: solid 2px #000000; border-top: 0px; height: 120px;"><?php print($thumb_html); ?></div>
<ol style="margin-top: 40px;">
    <li style="margin-bottom: 35px;">
        <span style="font-size: 25px; font-weight: bold;">Design:</span>
        <div style="margin-top: 8px; margin-bottom: 4px;">From the designs shown above, select one to vote for. The designs are listed in the same order as they are displayed above.</div>
        <script type="text/javascript"><?php print("var designs = " . __json_encode($designs) . ";"); ?></script>
        <select name="design_pref" id="design_pref" onchange="javascript:update_vote_preview();">
        <?php print($select_design_html); ?>
        </select>
        <div>
        <img id="display_vote" style="border: solid 2px #000000; margin-top: 10px; padding: 2px;" src="<?php print($vote_preview); ?>">
        </div>
    </li>
    <li style="margin-bottom: 35px;">
        <span style="font-size: 25px; font-weight: bold;">Colors:</span>
        <div style="margin-bottom: 10px;">Rank the following shirt/text color combinations using a preference schedule, where 1st is your most desirable choice.</div>
        <table cellspacing="0">
            <tr>
                <td style="border-right: solid black 2px; border-bottom: solid black 2px; padding-right: 10px;">shirt color/text color</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">1st</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">2nd</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">3rd</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">4th</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">5th</td>
                <td style="border-bottom: solid black 2px; padding-left: 4px; padding-right: 4px;">Don't Care</td>
            </tr>
<?php
    $colors = get_colors($contest_id);
    
    // randomize the order each time
    shuffle($colors);
    
    $color_prefs = get_color_prefs($contest_id, $username);

    foreach($colors as $color)
    {
        print("<tr>\n");
        print("\t<td style=\"padding-right: 10px; border-bottom: solid gray 1px; border-right: solid black 2px;\">$color[shirt_color] shirt/$color[text_color] text</td>\n");
        for($j = 1; $j <= count($colors); $j++)
        {
            $checked = "";
            if($color_prefs[$color["color_id"]] == $j)
            {
                $checked = " checked";
            }
            print("\t<td style=\"padding-right: 10px; border-bottom: solid gray 1px; text-align: center;\">\n\t\t<input type=\"radio\" name=\"pref_$j\" class=\"color_$color[color_id]\" value=\"$color[color_id]\" onchange=\"javascript:select_preference(this);\"$checked>\n\t</td>\n");
        }
        $checked = "";
        if($color_prefs[$color["color_id"]] == 0)
        {
            // we select the "don't care" preference
            $checked = " checked";
        }
        print("\t<td style=\"border-bottom: solid gray 1px; text-align: center;\"><input type=\"radio\" name=\"color_$color[color_id]\" id=\"color_$color[color_id]\" class=\"color_$color[color_id]\" value=\"0\" onchange=\"javascript:select_preference(this);\"$checked></td>\n");
        print("</tr>\n");
    }
?>
        </table>
    </li>
</ol>
<div style="text-align: center;">
    <input type="submit" value="Vote">
    <input type="hidden" name="submit_vote" value="1">
</div>
</form>

<?php
} // end display of voting page
?>

</div>
</div>
<?php print_footer(); ?>
</body>
</html>