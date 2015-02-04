<?php
include "common/template.php";
?>
<!doctype html>
<html>
<head>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/email.js"></script>
	<link rel="stylesheet" href="styles/siam.css" type="text/css"/>
	<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<div id="email_status" style="display: none; width: 600px; height: 290px;"><div id="email_status_msg" style="text-align: center; margin-left: auto; margin-right: auto; margin-top: 120px; width: 300px; height: 50px; font-size: 14pt;">Now sending your message.</div></div>
<div id="email_form">
Please fill out this form as completely as possible to enable us to act on your comment.<br>
<table>
	<tr><td>Your name:<span id="name_error" style="color: #FF6633;display: none;">*</span></td><td><input id="name" type="text" size="30"></td></tr>
	<tr><td>Your email:<span id="email_error" style="color: #FF6633;display: none;">*</span></td><td><input id="email_from" type="text" size="30"></td></tr>
	<tr><td>Send to:</td><td>
	<select id="email_to">
		<option value="pres" selected>SIAM president [Audrey DeVries]</option>
		<option value="fac">Faculty Advisor [Dr. Leo Rebholz]</option>
		<option value="web">Web Administrator [Ryan Grove]</option>
	</select>
	</td></tr>
	<tr><td style="vertical-align: top;">Comment:<span id="msg_error" style="color: #FF6633;display: none;">*</span></td><td><textarea id="msg" rows="8" cols="60"></textarea></td></tr>
	<tr><td><input id="bcc" type="checkbox" checked>Send me a copy</td><td style="text-align: right; vertical-align: middle;"><div id="error_text" style="display: none; color: #FF6633; text-align: center; width: 370px; float: left;">Please fill in all fields</div><button type="button" value="send" class="siam_btn" onClick="javascript:send_mail();">Send Email</button></td></tr>
</table>
</div>
</div>
</div>
<?php print_footer(); ?>
</body>
</html>