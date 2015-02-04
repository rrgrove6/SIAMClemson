<?php
include "../common/template.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	<link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
	<title>Clemson University SIAM student chapter</title>
    <style type="text/css">
    .candidate_cell
    {
        text-align: center;
        font-weight: bold;
        width: 100px;
        padding: 3px;
    }
    
    .candidate_name
    {
        border-right: solid 2px #000000;
        padding-right: 5px;
    }
    
    .candidate_header td
    {
        border-bottom: solid 2px #000000;
    }
    
    .tab:first-child
    {
        margin-left: 10px;
    }
    
    .tab
    {
        cursor: pointer;
        border: solid 3px #000000;
        text-align: center;
        padding: 0px;
        margin-left: 5px;
        margin-right: 5px;
        position: relative;
        float: left;
        margin-bottom: -2px;
        font-size: 0px;
        z-index: 2;
        height: 100px; /* 106 */
    }
    
    .info
    {
        position: relative;
        padding-left: 8px;
        padding-right: 8px;
        border: solid 2px #000000;
        clear: both;
    }
    
    .questionnaire
    {
        list-style: none;
        margin-left: 0px;
        padding-left: 6px;
    }
    
    .questionnaire li
    {
        margin-bottom: 10px;
    }
    
    .question
    {
        font-weight: bold;
    }
    
    .response
    {
        font-style: italic;
    }
    
    .name
    {
        padding-top: 20px;
        text-align: center;
        font-weight: bold;
        font-size: 40px;
        font-family: Arial;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
		preview(1);
	});
    
     function preview(id)
     {
          $(".info").hide();
          $("#info_" + id).show();
          //$("#tab_" + id).css("padding", "3px");
          $(".tab").css("borderColor", "#000000");
          $("#tab_" + id).css("borderColor", "#FF6633");
     }
    </script>
</head>
<body style="text-align: center;">
<?php print_header(); ?>
<div style="text-align: center;">
<div style="width: 800px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">


<p>SIAM is pleased to announce the candidates for the four SIAM offices for next year. A green check mark in the table below indicates that the candidate in that row is running for the office in that column. Details on the election will be made available when voting begins on Friday.</p>
<table align="center" cellspacing="0" style="margin-top: 15px; margin-bottom: 15px;">
    <tr class="candidate_header">
        <td class="candidate_name"></td>
        <td class="candidate_cell">President</td>
        <td class="candidate_cell">Vice President</td>
        <td class="candidate_cell">Treasurer</td>
        <!--<td class="candidate_cell">Secretary</td>-->
    </tr>
    <tr>
        <td class="candidate_name">Audrey DeVries</td>
	<td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Lucas Waddell</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Thanh To</td>
	<td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Ashleigh Craig</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
    </tr>
    </table>

<p>To help you get to know the candidates better, we asked each of them to fill out a questionnaire about themselves. Their responses are available below. To view a candidates responses, click on their picture. Candidates are displayed in the same order as in the table above.</p>

<div style="margin-top: 20px; position: relative;">
    <div style="position: relative;">
        <div id="tab_1" class="tab">
            <img id="image_1" src="audrey_devries.jpg" title="Audrey DeVries" onClick="javascript:preview(1);">
        </div>
        <div id="tab_2" class="tab">
            <img id="image_2" src="lucas_waddell.jpg" title="Lucas Waddell" onClick="javascript:preview(2);">
        </div>
	<div id="tab_3" class="tab">
            <img id="image_3" src="thanh_to.jpg" title="Thanh To" onClick="javascript:preview(3);">
        </div>
	<div id="tab_4" class="tab">
            <img id="image_4" src="ashleigh_craig.jpg" title="Ashleigh Craig" onClick="javascript:preview(4);">
        </div>
    </div>
    <div id="info_1" class="info" style="display: none;">
        <div class="name">Audrey DeVries</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Grove City College (Grove City, PA)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Operations Research</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Warren Adams</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD (3rd year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Lancaster, Pennsylvania</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I have had an incredible experience here at Clemson so far and seek to help make the experiences of all my fellow math graduate students positive ones. I would be honored and delighted to serve in a position where I can better use my knowledge and skills to help organize and lead events for the math department. I also desire to be available as a resource for newer students seeking advice or answers to questions.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">Here at Clemson I served as Secretary of SIAM last year and Treasurer of SIAM since April 2014. I am often involved with a lot of one-on-one tutoring. Currently I also volunteer and help with the English Improvement Sessions for international students.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">At this point I am hoping to become a college math professor, but I really just want to be a stay-at-home mom.</div>
            </li>
        </ul>
    </div>
    <div id="info_2" class="info" style="display: none;">
        <div class="name">Lucas Waddell</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Grove City College (Grove City, PA)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Operations Research</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Warren Adams</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD (5th year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Central Pennsylvania</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">During my time at Clemson I have really enjoyed attending the events that SIAM organizes.  I thought that it would be fun to give back to the organization during my last year here.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">I’m involved with a lot of the events put on by various groups within our department (Clemson Calculus Challenge, various AWM outreach activities, etc.).  I've been serving as interim VP of SIAM for the past month.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">Get a job, hopefully!  I'm considering teaching at a small liberal arts college or working in industry.</div>
                </li>
        </ul>
    </div>
    <div id="info_3" class="info" style="display: none;">
        <div class="name">Thanh To</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Dickinson College (in Carlisle, PA)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Operations Research</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Warren Adams</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">I'm working towards a PhD.</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Saigon, Vietnam</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I believe this would be a worthwhile experience and I like to do budgeting work. In fact, I was the business manager for Dickinson Student Yearbook where I worked as a treasurer and at the same time managed the sales and advertisement of the yearbook. I also helped with obtaining funding for the book.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">I used to do some martial art with Cuong Nhu.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">I would either pursue a postdoc or work in the industry. It really depends on what opportunities I might have upon graduation and I'm open to all possibilities.</div>
                </li>
        </ul>
    </div>
    <div id="info_4" class="info" style="display: none;">
        <div class="name">Ashleigh Craig</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Indiana University of Pennsylvania (IUP)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Operations Research</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">N/A</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">MS possibly en route to a PhD.</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Central Pennsylvania</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I am running for Treasurer of SIAM because as a first year grad student here at Clemson I want to become more involved in SIAM and the math department. I also think that serving as Treasurer would be a great way for me to get to know other students in the department. I am a very organized person and believe I would be a great fit for the role of treasurer. </div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">Since I am new to Clemson, I am not involved in many things, yet. I am playing flag football on our department intramural team. 
In undergrad, I was involved in symphony band, clarinet choir, math tutoring, and many community service projects. </div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">At this point, I am not sure whether I want to go into industry or academia after earning my degree.</div>
                </li>
        </ul>
    </div>
</div>


</div>
</div>
<?php print_footer(); ?>
</body>
</html>
