<?php
include_once "../common/template.php";
include_once "officer_lib.php";

$link = db_connect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
    <link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js" type="text/javascript"></script>
    <title>Clemson University SIAM student chapter</title>
<script type="text/javascript">
function highlight(id)
{
	// unhighlight all cells
	var cells = document.getElementById('teams').rows[0].cells;
	
	for(var i = 0; i < cells.length; ++i)
	{
		cells[i].style.backgroundColor = "#FFFFFF";
		cells[i].style.border = "none";
	}
	
	// highlight the correct cell
	document.getElementById('team_' + id).style.backgroundColor = "#FFE59A";
	document.getElementById('team_' + id).style.border = "solid orange 2px";
}

function get_pref_order(office)
{
    var output = [];
    
    var candidates = $("#sortable_" + office + " > li");
    
    candidates.each(function(index)
    {
        output.push({"id" : $(this).attr("id").split("_")[1], "name" : $(this).text()});
    }
    );
    
    return output;
}

function create_summary(office, list)
{
    var voting_summary = office + "\n";
    
    $(list).each(function(index)
    {
        voting_summary += "  - " + this.name + "\n";
    }
    );
    
    return voting_summary;
}

function pack_up(list)
{
    var temp = [];
    
    for(var i = 0; i < list.length; i++)
    {
        temp.push(list[i].id);
    }
    
    return temp.join(",");
}

function confirm_vote()
{
    var pres_prefs = get_pref_order("President");
    var vp_prefs = get_pref_order("Vice_President");
    var treas_prefs = get_pref_order("Treasurer");
    var sec_prefs = get_pref_order("Secretary");
    
    $("#pres_prefs").val(pack_up(pres_prefs));
    $("#vp_prefs").val(pack_up(vp_prefs));
    $("#treas_prefs").val(pack_up(treas_prefs));
    $("#sec_prefs").val(pack_up(sec_prefs));
    
    var voting_summary = create_summary("President", pres_prefs);
    voting_summary += create_summary("\nVice President", vp_prefs);
    voting_summary += create_summary("\nTreasurer", treas_prefs);
    voting_summary += create_summary("\nSecretary", sec_prefs);

    var response = confirm("Are you sure you want to vote for the following people?\n\n" + voting_summary);
    
    if(response)
    {
        return true;
    }
    
    return false;
}

$(function() {
    $( "#sortable_President" ).sortable();
    $( "#sortable_President" ).disableSelection();
    
    $( "#sortable_Vice_President" ).sortable();
    $( "#sortable_Vice_President" ).disableSelection();
    
    $( "#sortable_Treasurer" ).sortable();
    $( "#sortable_Treasurer" ).disableSelection();
    
    $( "#sortable_Secretary" ).sortable();
    $( "#sortable_Secretary" ).disableSelection();
});
</script>
<style type="text/css">
.pref_table
{
    margin-left: 30px;
}

.pref_table tr td
{
    height: 100px;
    font-weight: bold;
    font-size: 24px;
}

.candidate
{
    font-size: 24px;
    font-weight: bold;
    list-style: none;
}

.candidate li
{
    margin-top: 6px;
    margin-bottom: 6px;
    border: solid 2px #000000;
    cursor: move;
    background: #FFFFFF;
    height: 100px;
}

.candidate_photo
{
    text-align: center;
    float: left;
    width: 100px;
    height: 100px;
    border-right: solid 2px #000000;
    margin-right: 20px;
}

.candidate_name
{
    margin-top: 25px;
    width: 350px;
    
}

.office_title
{
    font-size: 30px;
    font-weight: bold;
    font-family: Arial;
    background: #FF6633;
    padding: 5px;
}
</style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 700px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php
// check whether this person is eligible to vote
$username = $_SERVER['REMOTE_USER'];

if(is_voter_eligible($username))
{
	if(get_str_value($_POST, "form_name") == "vote_officer_form")
	{
		// the user is submitting their vote
        add_new_approval($username);
?>
<p>Thank you for voting in the SIAM officer elections.</p>
<?php
	}
	else
	{
		// the user is loading the page to vote
?>
<p style="margin-bottom: 40px;"><span style="font-weight: bold; font-size: 1.2em;">Instructions:</span> Please click the Approve button at the bottom of the page to indicate your approval of the following people as the SIAM officers for next year.</p>
<form method="POST" action="vote_officer_approval.php">
<table align="center" cellspacing="0">
    <tr>
        <td style="width: 110px; border-bottom: solid 2px #000000; font-weight: bold;">Office</td>
        <td style="border-bottom: solid 2px #000000; font-weight: bold;">Candidate</td>
    </tr>
    <tr>
        <td>President</td>
        <td>Paul Kuberry</td>
    </tr>
    <tr>
        <td>Vice President</td>
        <td>Michael Dowling</td>
    </tr>
    <tr>
        <td>Treasurer</td>
        <td>Elaine Sotherden</td>
    </tr>
    <tr>
        <td>Secretary</td>
        <td>Audrey DeVries</td>
    </tr>
</table>
<div style="text-align: center; margin-top: 20px;">
    <input type="submit" value="Approve">
    <input type="hidden" name="form_name" value="vote_officer_form">
</div>
</form>
<?php
	}
}
else
{
?>
<p>You have logged in as  <span style="font-weight: bold; font-size: 25px; color: #FF6633;"><?php print($username); ?></span>, but that username cannot vote. Either you are not on the list of eligible voters or you have already voted. Note that you must login with your student username to vote. If you know that you are eligible to vote and have not done so already, then please contact us at siam@clemson.edu.</p>
<?php
}
?>
</div>
</div>
<?php print_footer(); ?>
</body>
</html>