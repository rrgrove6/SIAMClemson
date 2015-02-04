<?php
include_once("jeopardy_lib.php");

$category_list = get_all_categories();
$values = array();
$display_text = array();

foreach($category_list as $category)
{
    $values[] = $category["category_id"];
    $display_text[] = "(Round $category[round]) $category[title]";
}

$category_dropdown_html = get_html_dropdown($values, $display_text, "category", get_current_category(), " id=\"category\"");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Jeopardy Admin</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function grade()
    {
        var category_id = $("#category").val();
        window.location = "scoring.php?category_id=" + category_id;
    }
</script>
</head>
<body>
    <ul>
        <li><a href="team_setup.php">Team Setup</a></li>
        <li><a href="settings.php">Game Settings</a></li>
        <li><?php print($category_dropdown_html); ?> <button type="button" onclick="javascript:grade();">Grade</button></li>
    </ul>
</body>
</html>