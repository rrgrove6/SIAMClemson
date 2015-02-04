<?php
include_once "../common/template.php";
include_once "../common/general_funcs.php";
include_once "gss_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <link rel="stylesheet" href="../styles/siam.css" type="text/css"/>
    <title>Clemson University SIAM student chapter</title>
    <style type="text/css">
    .talk_title
    {
        font-weight: bold;
        font-size: 20px;
    }
    
    .talk_abstract
    {
        margin-top: 14px;
        font-family: Arial;
        font-size: 14px !important;
        font-family: "Times New Roman" !important;
    }
    .talk_abstract pre
    {
        margin-top: 14px;
        font-family: Arial;
        font-size: 14px !important;
        font-family: "Times New Roman" !important;
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
<div style="width: 1000px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_menu(); ?>
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
    
    print("<form action=\"schedule.php\" method=\"POST\">");
    
    $values = array();
    $display_text = array();
    
	while($row = array_shift($semester_list))
    {
        $values[] = "$row[semester]_$row[year]";
        $display_text[] = "$row[semester] $row[year]";
    }
    
    print(get_html_dropdown($values, $display_text, "semester", $semester . "_" . $year));
    
    print("<input type=\"submit\" value=\"View Semester\"></form>");
    
    print("<div style=\"text-align: center; font-size: 30px; font-weight: bold; margin-bottom: 20px;\">$semester $year</div>");
?>
<table class="schedule" style="border: solid 1px #000000;" align="center" cellspacing="0">
    <tr>
        <td style="width: 75px;">Date</td>
        <td>Talk Information</td>
    </tr>
<?php
    $talk_list = get_talk_list($semester, $year);
    
	while($row = array_shift($talk_list))
    {
        $cur_talk_id = $row["talk_id"];
        $attachments = get_talk_attachments($cur_talk_id);
        
        if(count($attachments) > 0)
        {
            $attachment_html = "<hr>";
            
            foreach($attachments as $attachment)
            {
                $attachment_html .= "<div><a href=\"download.php?attachment_id=$attachment[attachment_id]\">$attachment[link_text]</a></div>\n";
            }
        }
        else
        {
            $attachment_html = "";
        }
        
        print("<tr>\n\t<td style=\"vertical-align: top;\">$row[talk_date]</td>\n\t<td><div class=\"talk_title\">$row[title]</div><div class=\"talk_speaker\">$row[speaker]</div><div class=\"talk_abstract\">$row[abstract]</div>$attachment_html\t</td>\n</tr>\n");
    }
?>
</table>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>