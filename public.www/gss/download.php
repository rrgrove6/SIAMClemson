<?php
include_once "../common/general_funcs.php";
include_once "gss_lib.php";

$attachment_id = get_int_value($_REQUEST, "attachment_id");

$info = get_attachment_info($attachment_id);

if(count($info) > 0)
{
    header("HTTP/1.1 200 Ok");

	 header("MIME-Version: 1.0");
	 //header("Content-Type: $info[content_type]");
	 header("Content-Type: application/octet-stream");

    header('Content-Disposition: attachment; filename="' . $info["display_filename"] . '";');
    readfile("/cifsmounts/EH01/users/siam/public.www/gss/uploaded_attachments/$info[actual_filename]");
}
else
{
    header("HTTP/1.1 404 Not Found");
}
?>