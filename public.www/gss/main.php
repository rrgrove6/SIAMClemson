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
    .category
    {
        vertical-align: top;
        width: 200px;
        font-size: 1.5em;
        color: #F66733;
        font-weight: bold
        font-family: Arial;
        padding-bottom: 10px;
    }
    </style>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 1000px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">
<?php print_gss_menu(); ?>
    <p style="text-align: center; font-size: 2em;">Graduate Student Seminar</p>
    <table>
        <tr>
            <td class="category">Description:</td>
            <td>
                <div>The Graduate Student Seminar (GSS) is a weekly seminar run by the graduate students in the Mathematical Sciences department. The speakers are graduate students from the department, and the topics range from original research to general background talks on various subjects of interest. In the past, there have also been talks about planning your graduate coursework, taking prelim exams, and finding a job in academia. All graduate students in the department are encouraged to attend.</div>
                <div>Pizza and soft drinks are provided following the seminar. There is no charge to attend, but we ask that you sign up by clicking on the &quot;Sign Up&quot; link below by 2:00 PM the day of the talk to assist us in planning for the food.</div>
            </td>
        </tr>
        <tr>
            <td class="category">Time/Location:</td>
            <td>GSS is held every Wednesday afternoon at 5:00 PM in Martin M-102.</td>
        </tr>
        <tr>
            <td class="category">Purpose:</td>
            <td>
                <ul>
                    <li>Provide a forum for students to talk in an informal and unpressured way about their work to other graduate students. Talks are presented at a level geared toward second year students as an audience.</li>
                    <li>Provide exposure to research level mathematics.</li>
                    <li>Share ideas and collaborate on problems.</li>
                    <li>Gain speaking/presentation experience.</li>
                    <li>Get to know the other students in the department better.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="category">Contact:</td>
            <td>The organizer for this year is Michael Dowling. Please contact Michael if you are interested in giving a talk.</td>
        </tr>
        <tr>
            <td class="category">Schedule:</td>
            <td>The current schedule is available <a href="schedule.php">here</a>.</td>
        </tr>
        <tr>
            <td class="category">Sign up:</td>
            <td>Click <a href="signup.php">here</a> to sign up for the current talk. (remember to sign up before 2:00 PM on the day of the talk)</td>
        </tr>
    </table>
    <div style="margin-top: 20px; text-align: center; color: #86898C;">GSS is sponsored by the Clemson University SIAM chapter.</div>
</div>	
</div>
<?php print_footer(); ?>
</body>
</html>