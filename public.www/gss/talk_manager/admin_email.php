<?php
include_once "../../common/template.php";
include_once "../../common/general_funcs.php";
include_once "../gss_lib.php";
?>
<?php

$page_name = get_str_value($_REQUEST, "page_name");
$username = strtolower($_SERVER["REMOTE_USER"]);

$talk_info = get_current_talk();
$full_date = date("F j", strtotime($talk_info["date"]));

// clean up html from talk abstract
$abstract = $talk_info["abstract"];
$abstract = str_replace("<br>", "\n", $abstract);
$abstract = str_replace("<div>", "", $abstract);
$abstract = str_replace("</div>", "\n", $abstract);
$abstract = html_entity_decode($abstract);

$announcement_email_subject = "GSS Wednesday";
$announcement_email_text = <<< EOF
<html>
<body>
<p>The Graduate Student Seminar is pleased to bring you the next in our series of weekly talks. The details of this week's talk are provided below.</p>

<p>Title: $talk_info[title]</p>

<p>Speaker(s): $talk_info[speaker]</p>

<p>Time: 5:00 PM, Wednesday $full_date</p>

<p>Place: Martin M-102</p>

<p>Food: free pizza and soft drinks</p>

<p>Sign Up: Click <a href="http://people.clemson.edu/~siam/gss/signup.php">here</a> to sign up. (signing up is not required, but if you are planning to eat, please sign up by 2:00 PM Wednesday so that we know how much pizza to get)</p>

<p>Abstract: $abstract</p>
</body>
</html>
EOF;

$reminder_email_subject = "GSS Today";
$reminder_email_text = <<< EOF
<html>
<body>
<p>This is just a reminder that GSS will meet at 5:00 PM today in Martin M-102. The title of the talk is "$talk_info[title]" and further details are available on the <a href="http://people.clemson.edu/~siam/gss/schedule.php">schedule</a>. If you plan on eating, please sign up by 2:00 PM so we know how much pizza to order.</p>

<p>Sign Up: Click <a href="http://people.clemson.edu/~siam/gss/signup.php">here</a> to sign up.</p>
</body>
</html>
EOF;

if($page_name == "send_email")
{
    // send email for this week
    $type = get_str_value($_REQUEST, "email_type");
    $display_name = get_str_value($_REQUEST, "email_name");
    $valid = False;
    
    if($type == "announcement")
    {
        $subject = $announcement_email_subject;
        $message = $announcement_email_text;
        $valid = True;
    }
    else if($type == "reminder")
    {
        $subject = $reminder_email_subject;
        $message = $reminder_email_text;
        $valid = True;
    }
    
    if($valid)
    {
        // headers for sending email as html
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    
        $headers .= "From: $display_name <$username@clemson.edu>\r\n";
        #$recipients = "GSS_MTHSC_GRAD_STUDENTS-L@clemson.edu";
        $recipients = "gss_mthsc_grad_students-l@lists.clemson.edu";
        // we only need to add on the students who just came after the bridge course until they are included on the list server
        // for testing
        //$recipients = "";
		  
        $extra_emails = array("jcoyken@clemson.edu");
        // for testing
        //$extra_emails = array("nblack@clemson.edu");
        
        if(count($extra_emails) > 0)
        {
            $headers .= "Bcc: " . implode(",", $extra_emails) . "\r\n";
        }

        $output = mail($recipients, $subject, $message, $headers);
        
        $content = <<< EOF
<p>The $type email was sent. You should receive a copy shortly since you are listed on the GSS_MTHSC_GRAD_STUDENTS-L list.</p>
EOF;
    }
    else
    {
        $content = <<< EOF
<p>There was a problem sending the email. Contact Nate Black (nblack@clemson.edu).</p>
EOF;
    }
}
else
{
    // display form

    $content = <<< EOF
<form method="POST" action="admin_email.php">
	<div style="margin-bottom: 10px; margin-top: 20px;">
		<div style="position: relative; float: left; width: 150px;">Name to send email as:</div>
		<input type="text" name="email_name" style="width: 250px;">
	</div>
	<div style="margin-bottom: 10px; margin-top: 20px;">
		<div style="position: relative; float: left; width: 150px;">Email address:</div>
		$username@clemson.edu
	</div>
	<div style="margin-bottom: 10px; margin-top: 20px;">
		<div style="position: relative; float: left; width: 150px;">Choose email type:</div>
		<input type="radio" id="announcement" name="email_type" value="announcement" checked onclick="javascript:show_preview('announcement');">
        <label for="announcement">announcement</label> 
        <input type="radio" name="email_type" value="reminder" onclick="javascript:show_preview('reminder');">
        <label for="reminder">reminder</label> 
	</div>
	<div style="margin-bottom: 10px; margin-top: 20px;">
		<div style="position: relative; float: left; width: 150px;">Preview:</div>
        <div style="border: solid 2px #000000; padding: 8px; margin-left: 150px;">
            <div style="margin-bottom: 20px;">
                <div style="position: relative; float: left; width: 50px;">Subject:</div>
                <div id="announcement_subject" style="margin-left: 100px; border: solid 1px #000000; padding: 5px;">$announcement_email_subject</div>
                <div id="reminder_subject" style="margin-left: 100px; border: solid 1px #000000; padding: 5px; display: none;">$reminder_email_subject</div>
            </div>
            <div>
                <div style="position: relative; float: left; width: 50px;">Body:</div>
                <div id="announcement_body" style="max-height: 400px; overflow-y: scroll; margin-left: 100px; border: solid 1px #000000; padding: 5px;">
                    <textarea style="width: 600px; min-height: 300px;" readonly="readonly">$announcement_email_text</textarea>
                </div>
                
                <div id="reminder_body" style="max-height: 400px; margin-left: 100px; border: solid 1px #000000; padding: 5px; display: none;">
                    <textarea style="width: 600px; min-height: 300px;" readonly="readonly">$reminder_email_text</textarea>
                </div>
            </div>
        </div>
    </div>
	<div style="text-align: center;">
		<input type="submit" value="Send Email">
		<input type="hidden" name="page_name" value="send_email">
	</div>
</form>
EOF;
}
?>
<!doctype html>
<html>
<head>
    <title>Clemson University SIAM student chapter</title>
    <link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
    <script type="text/javascript">
    function show_preview(preview_type)
	{
        if(preview_type == "announcement")
        {
            document.getElementById("reminder_subject").style.display = "none";
            document.getElementById("announcement_subject").style.display = "";
            document.getElementById("reminder_body").style.display = "none";
            document.getElementById("announcement_body").style.display = "";
        }
        else if(preview_type == "reminder")
        {
            document.getElementById("announcement_subject").style.display = "none";
            document.getElementById("reminder_subject").style.display = "";
            document.getElementById("announcement_body").style.display = "none";
            document.getElementById("reminder_body").style.display = "";
        }
	}
	</script>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 900px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_admin_header(); ?>
<?php print($content); ?>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>