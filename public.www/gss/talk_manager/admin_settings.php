<?php
include_once "../../common/template.php";
include_once "../../common/general_funcs.php";
include_once "../gss_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 1000px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_admin_header(); ?>
<?php
$info = "";

if(get_str_value($_REQUEST, "page_name") == "save_settings")
{
	$talk_id = get_int_value($_REQUEST, "talk");
	$row = set_current_talk($talk_id);
	
	$talk_title = $row["title"];
	$talk_date = $row["date"];
    
    set_current_semester(get_str_value($_REQUEST, "semester"));
    set_current_year(get_int_value($_REQUEST, "year"));
    
    $info = "* settings updated";
}

// display the settings form

// get the current talk id
$current_talk = get_current_talk();

$year = get_current_year();
$semester = get_current_semester();

$talk_list = get_talk_list($semester, $year);

?>
<form method="POST" action="admin_settings.php">
	<div style="margin-top: 20px; margin-bottom: 10px;">
		<div>The current talk is: <?php print("($current_talk[date]) $current_talk[title]");?></div>
          <span>Choose another talk: </span>
<?php
$ids = array(-1);
$display_text = array("**Talk is not in the current semester.**");

// popping them off will reverse the order so that the latest ones are displayed first
while($row = array_pop($talk_list))
{
    $ids[] = $row["talk_id"];
    $display_text[] = "($row[talk_date]) $row[title]";
}

print(get_html_dropdown($ids, $display_text, "talk", $current_talk["talk_id"]));
?>
	</div>
    <div>
        <span>The current semester is: </span>
<?php
$semesters = array("Spring", "Fall");

print(get_html_dropdown($semesters, $semesters, "semester", $semester));
?>
    </div>
    <div>
        <span>The current year is: </span>
        <input type="text" name="year" value="<?php print($year);?>" style="width: 50px;">
    </div>
	<div style="text-align: center;">
            <div style="text-align: center; color: #FF0000;"><?php print($info);?></div>
		<input type="submit" value="Save settings">
		<input type="hidden" name="page_name" value="save_settings">
	</div>
</form>

</div>	
</div>
<?php print_footer(); ?>
</body>
</html>