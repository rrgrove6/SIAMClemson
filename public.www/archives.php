<?php
include "common/template.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/awards.js"></script>
	<script type="text/javascript" src="scripts/officers.js"></script>
	<link rel="stylesheet" href="styles/awards.css" type="text/css"/>
	<link rel="stylesheet" href="styles/officers.css" type="text/css"/>
	<link rel="stylesheet" href="styles/siam.css" type="text/css"/>
	<title>Clemson University SIAM student chapter</title>
</head>
<body style="text-align: center;" onload="javascript:display_award_panel(); javascript:display_officer_panel();">
<?php print_header(); ?>
<table>
<tr>
	<td style="vertical-align: top; width: 550px;">
		<div id="award_menu"><ul id="award_years"></ul></div>
		<table id="awards" cellspacing="0">
		</table>
	</td>
	<td style="vertical-align: top;">
		<div id="officer_menu"><ul id="officer_years"></ul></div>
		<table id="officers" style="border-spacing: 0px; clear: left; width: 450px;" cellspacing="0">
		</table>
	</td>
</tr>
</table>
<?php print_footer(); ?>
</body>
</html>