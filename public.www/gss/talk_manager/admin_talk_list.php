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
    <style type="text/css">
    .img_btn
    {
        cursor: pointer;
        margin-left: 5px;
        margin-right: 5px;
    }
    
    .schedule tr td
    {
        border: solid 1px #000000;
        padding: 5px;
    }
    </style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_admin_header(); ?>
<?php
    $view = get_str_value($_REQUEST, "semester");
    
    if(strlen($view) == 0 || strpos($view, "_") === FALSE)
    {
        $view = get_current_semester() . "_" . get_current_year();
    }

    $data = explode("_", $view);
    $semester = $data[0];
    
    if(strtolower($semester) != "spring" && strtolower($semester) != "fall")
    {
        $semester = get_current_semester();
    }
    
    $year = intval($data[1]);
    $semester = ucfirst($semester);
    
    $semester_list = get_semester_talk_list();
    
	 print("<div style=\"margin-top: 25px;\">");
	 
    print("<form action=\"admin_talk_list.php\" method=\"POST\">");
    
    $values = array();
    $display_text = array();
    
	while($row = array_shift($semester_list))
    {
        $values[] = "$row[semester]_$row[year]";
        $display_text[] = "$row[semester] $row[year]";
    }
    
    print(get_html_dropdown($values, $display_text, "semester", $semester . "_" . $year));
    
    print("<input type=\"submit\" value=\"View Semester\"></form>");
	 
	 print("</div>");
    
    print("<div style=\"text-align: center; font-size: 30px; font-weight: bold; margin-bottom: 20px;\">$semester $year</div>");
?>
<table class="schedule" style="border: solid 1px #000000;" align="center" cellspacing="0">
    <tr>
        <td>&nbsp;</td>
        <td style="width: 75px;">Date</td>
        <td>Talk Title</td>
    </tr>
<?php
    $talk_list = get_talk_list($semester, $year);
    
	while($row = array_shift($talk_list))
    {
        print("<tr><td style=\"width: 60px; text-align: center;\"><a href=\"admin_manage_talk.php?action=edit&talk_id=$row[talk_id]\"><img src=\"edit.png\" class=\"img_btn\" title=\"Edit talk\"></a><a href=\"admin_manage_talk.php?page_name=delete_talk&talk_id=$row[talk_id]&semester=$semester" . "_" . "$year\"><img src=\"del.png\" class=\"img_btn\" title=\"Delete talk\"></a></td><td style=\"vertical-align: top;\">$row[talk_date]</td><td>$row[title]</td></tr>");
    }
?>
</table>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>