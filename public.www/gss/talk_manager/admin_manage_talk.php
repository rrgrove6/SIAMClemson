<?php
include_once "../../common/template.php";
include_once "../../common/general_funcs.php";
include_once "../gss_lib.php";

function parse_meta_data($meta_data)
{
    $output = array();
    // I think there are only 2 lines at most
    $temp = explode("\r\n", $meta_data);
    $pieces = explode("; ", $temp[0]);
    
    for($i = 1; $i < count($pieces); $i++)
    {
        $pair = explode("=", $pieces[$i]);
        $output[$pair[0]] = substr($pair[1], 1, -1);
    }
    
    if(count($temp) == 2)
    {
        $pair = explode(": ", $temp[1]);
        $output[$pair[0]] = substr($pair[1], 1, -1);
    }
    
    return $output;
}

function parse_request()
{
    $data = file_get_contents('php://input');
    
    //print($data);
    
    $temp = explode("\r\n", $data, 2);
    $boundary = $temp[0];
    
    // + 6 is for the \r\n{boundary}--\r\n that is the last content boundary line (+ 2 is for the \r\n that used to separate the boundary from the first line)
    $blocks = explode("\r\n" . $boundary . "\r\n", substr($data, strlen($boundary) + 2, -1 * (strlen($boundary) + 6)));
    
    $output = array();
    
    foreach($blocks as $block)
    {
        $loc = strpos($block, "\r\n\r\n");
        if(substr($block, 0, $loc) != "")
        {
            $meta_data = parse_meta_data(substr($block, 0, $loc));
            //print("|" . substr($block, 0, $loc) . "|");
            $output[$meta_data["name"]] = array("meta-data" => $meta_data, "data" => substr($block, $loc + 4));
        }
    }
    
    return $output;
}

function get_parsed_str_value($data, $name, $default = "")
{
    return array_key_exists($name, $data) ? $data[$name]["data"] : get_str_value($_REQUEST, $name, $default);
}

function get_parsed_int_value($data, $name, $default = 0)
{
    return array_key_exists($name, $data) ? intval($data[$name]["data"]) : get_int_value($_REQUEST, $name, $default);
}

// this will get the data since we have to parse it ourselves (thanks to php file uploads being not allowed)
$data = parse_request();
//print_r($data);

$action = get_parsed_str_value($data, "action");
$page_name = get_parsed_str_value($data, "page_name");



if($page_name == "add_talk")
{
	$date = get_parsed_str_value($data, "date");
	$title = get_parsed_str_value($data, "title");
	$speaker = get_parsed_str_value($data, "speaker");
	$abstract = get_parsed_str_value($data, "abstract");
	// get rid of extra newlines
	$abstract = str_replace("\r", "", $abstract);
	$abstract = str_replace("\n", "", $abstract);
	$abstract = str_replace("&nbsp;", " ", $abstract);

	// strip out consecutive spaces
	while(!(strpos($abstract, "  ") === False))
	{
		$abstract = $abstract = str_replace("  ", " ", $abstract);
	}
    
    $additions = get_parsed_str_value($data, "additions");
    $additions = (strlen($additions) > 0) ? explode(",", $additions) : array();
    
    foreach($additions as $temp_id)
    {
        $link_text = get_parsed_str_value($data, "file_" . $temp_id . "_text");
        $file_object = $data["file_" . $temp_id];
        
        add_new_attachment($talk_id, $link_text, $file_object["meta-data"]["filename"], $file_object["data"]);
    }
    

    $talk_id = add_new_talk($title, $date, $speaker, $abstract);
    $content = <<< EOF
<p>The talk has been successfully added.
    <ul>
        <li>To set this talk as the current talk, click on the &quot;Settings&quot; link above and choose the talk that you want to set as the current talk.</li>
        <li>To edit this talk click <a href="admin_manage_talk.php?action=edit&talk_id=$talk_id">here</a>.</li>
        <li>To add another talk, click on the &quot;Add a new talk&quot; link above.</li>
        <li>If you wish to edit a talk that you have added, click on the &quot;Talk list&quot; link above.</li>
</p>
EOF;
}
else if($page_name == "save_talk")
{
	$talk_id = get_parsed_int_value($data, "talk_id");
	$date = get_parsed_str_value($data, "date");
	$title = get_parsed_str_value($data, "title");
	$speaker = get_parsed_str_value($data, "speaker");
	$abstract = get_parsed_str_value($data, "abstract");
	// get rid of extra newlines
	$abstract = str_replace("\r", " ", $abstract);
	$abstract = str_replace("\n", " ", $abstract);
	$abstract = str_replace("&nbsp;", " ", $abstract);

	// strip out consecutive spaces
	while(!(strpos($abstract, "  ") === False))
	{
		$abstract = $abstract = str_replace("  ", " ", $abstract);
	}
    
    $additions = get_parsed_str_value($data, "additions");
    $additions = (strlen($additions) > 0) ? explode(",", $additions) : array();
    $deletions = get_parsed_str_value($data, "deletions");
    $deletions = (strlen($deletions) > 0) ? explode(",", $deletions) : array();
    
    save_talk($talk_id, $title, $date, $speaker, $abstract);
    
    foreach($deletions as $deleted_id)
    {
        delete_attachment($deleted_id);
    }
    
    foreach($additions as $temp_id)
    {
        $link_text = get_parsed_str_value($data, "file_" . $temp_id . "_text");
        $file_object = $data["file_" . $temp_id];
        
        add_new_attachment($talk_id, $link_text, $file_object["meta-data"]["filename"], $file_object["data"]);
    }

    $content = <<< EOF
<p>The talk has been successfully saved.
    <ul>
        <li>To set this talk as the current talk, click on the &quot;Settings&quot; link above and choose the talk that you want to set as the current talk.</li>
        <li>To edit this talk again click <a href="admin_manage_talk.php?action=edit&talk_id=$talk_id">here</a>.</li>
        <li>To add a talk, click on the &quot;Add a new talk&quot; link above.</li>
        <li>If you wish to edit a talk that you have added, click on the &quot;Talk list&quot; link above.</li>
</p>
EOF;
}
else if($page_name == "delete_talk")
{
    $talk_id = get_parsed_int_value($data, "talk_id");
    $semester = get_parsed_str_value($data, "semester");
    
    $result = delete_talk($talk_id);
    
    if($result)
    {
        // redirect back to talk list page
        redirect("http://people.clemson.edu/~siam/gss/talk_manager/admin_talk_list.php?semester=$semester");
    }
    else
    {
        // display info warning about people being signed up
        $content = <<< EOF
<p>This talk cannot be deleted because people have already signed up to attend it. If you really need to delete this talk for some reason contact Nate Black.</p>
EOF;
    }
}
else
{
    if($action == "edit")
    {
        $talk_id = get_parsed_int_value($data, "talk_id");
        
        $talk_info = get_talk_info($talk_id);
        $talk_attachments = get_talk_attachments($talk_id);
        $btn_title = "Save talk";
        $page_name = "save_talk";
    }
    else
    {
        // we assume they want to add a talk
        $talk_id = -1;
        $talk_info = array("date" => "", "title" => "", "speaker" => "", "abstract" => "");
        $talk_attachments = array();
        $btn_title = "Add talk";
        $page_name = "add_talk";
    }
    
    $attachment_html = "";
    
    foreach($talk_attachments as $attachment)
    {
        $attachment_html .= <<<EOF
            <div style="border: solid 1px #000000; background: #99CCFF; padding: 5px; width: 490px; margin-bottom: 10px;">
                <img src="del.png" style="cursor: pointer; position: relative; float: right;" onclick="javascript:delete_old($attachment[attachment_id], this);" title="Delete attachment">
                <div style="clear: right;"><a href="../download.php?attachment_id=$attachment[attachment_id]">$attachment[link_text]</a></div>
            </div>
EOF;
    }

    $content = <<< EOF
<form method="POST" action="admin_manage_talk.php" enctype="multipart/form-data">
	<div style="margin-bottom: 10px; margin-top: 20px;">
		<div style="position: relative; float: left; width: 100px;">Date of talk:</div>
		<input id="date" type="text" name="date" value="$talk_info[date]">
	</div>
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div  style="position: relative; float: left; width: 100px;">Title of talk:</div>
		<input type="text" name="title" style="width: 500px;" value="$talk_info[title]">
	</div>
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div  style="position: relative; float: left; width: 100px;">Speaker(s):</div>
		<input type="text" name="speaker" style="width: 500px;" value="$talk_info[speaker]">
	</div>
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div style="position: relative; float: left; width: 100px;">Abstract:</div>
		<div style="margin-left: 100px;"><textarea class="editor" name="abstract">$talk_info[abstract]</textarea></div>
	</div>
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div style="position: relative; float: left; width: 100px;">Attachments:</div>
            <div id="attachment_panel" style="margin-left: 100px; clear: left;">
$attachment_html
            </div>
            <div style="margin-left: 100px; margin-top: 10px;"><img src="add.png" onclick="javascript:add_attachment();" style="cursor: pointer;" title="Add attachment"> Add attachment</div>
	</div>
	<div style="text-align: center;">
		<input type="submit" value="$btn_title">
        <input type="hidden" name="talk_id" value="$talk_id">
        <input type="hidden" id="additions" name="additions">
        <input type="hidden" id="deletions" name="deletions">
		<input type="hidden" name="page_name" value="$page_name">
	</div>
</form>
EOF;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Clemson University SIAM student chapter</title>
    <link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
    <link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="CLEditor1_3_0/jquery.cleditor.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="CLEditor1_3_0/jquery.cleditor.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $( "#date" ).datepicker();
        $(".editor").cleditor({
            width: 500,
            useCSS: true,
            controls: "bold italic underline | bullets numbering indent outdent | alignleft center alignright justify | link unlink | undo redo | pastetext source"
        });
    });
    
    var next_id = 1;
    var additions = [];
    var deletions = [];
    
    function add_attachment()
    {
        var id = next_id;
        next_id++;
        
        var container_div = $(document.createElement("div"));
        container_div.css({"border": "solid 1px #000000", "background": "#99CCFF", "padding": "5px", "width": "490px", "marginBottom": "10px"});
        
        var del_img = $(document.createElement("img"));
        del_img.attr("src", "del.png");
        del_img.title = "Delete attachment";
        del_img.css({"cursor": "pointer", "position": "relative", "float": "right"});
        del_img.bind("click", new Function("delete_new(" + id + ", this)"));
        
        var title_div = $(document.createElement("div"));
        title_div.append(document.createTextNode("link text: "));
        
        var input_text = $(document.createElement("input"));
        input_text.attr("type", "text");
        input_text.attr("name", "file_" + id + "_text");
        input_text.css("width", "200px");
        
        title_div.append(input_text);
        
        var upload_div = $(document.createElement("div"));
        upload_div.css("marginTop", "10px");
        upload_div.append(document.createTextNode("Choose file to upload: "));
        
        var input_file = $(document.createElement("input"));
        input_file.attr("type", "file");
        input_file.attr("name", "file_" + id);

        upload_div.append(input_file);
        
        container_div.append(del_img);
        container_div.append(title_div);
        container_div.append(upload_div);
        $("#attachment_panel").append(container_div);
        
        additions.push(id);
        $("#additions").val(additions.join(","));
    }
    
    function delete_new(id, obj)
    {
        var container = obj.parentNode;
        container.parentNode.removeChild(container);
    
        additions.splice($.inArray(id, additions), 1);
        $("#additions").val(additions.join(","));
    }
    
    function delete_old(id, obj)
    {
        var container = obj.parentNode;
        container.parentNode.removeChild(container);
        
        deletions.push(id);
        $("#deletions").val(deletions.join(","));
    }
</script>
<style type="text/css">
div.ui-datepicker
{
    font-size:10px;
}
</style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 700px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_admin_header(); ?>
<?php print($content); ?>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>