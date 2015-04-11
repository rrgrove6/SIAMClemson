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


<p>SIAM is pleased to announce the candidates for the five SIAM offices for next year. A green check mark in the table below indicates that the candidate in that row is running for the office in that column. </p>
<table align="center" cellspacing="0" style="margin-top: 15px; margin-bottom: 15px;">
    <tr class="candidate_header">
        <td class="candidate_name"></td>
        <td class="candidate_cell">President</td>
        <td class="candidate_cell">Vice President</td>
        <td class="candidate_cell">Treasurer</td>
        <td class="candidate_cell">Secretary</td>
        <td class="candidate_cell">Webmaster</td>
    </tr>
    <tr>
        <td class="candidate_name">Audrey DeVries</td>
	<td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Garrett Dranichak</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
 	<td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Thanh To</td>
	<td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
 	<td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Elaine Sotherden</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
 	<td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Ryan Grove</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
 	<td class="candidate_cell"></td>
 	<td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
    </tr>
    </table>

<p>To help you get to know the candidates better, we asked each of them to fill out a questionnaire about themselves. Their responses are available below. To view a candidates responses, click on their picture. Candidates are displayed in the same order as in the table above.</p>

<div style="margin-top: 20px; position: relative;">
    <div style="position: relative;">
        <div id="tab_1" class="tab">
            <img id="image_1" src="audrey_devries.jpg" title="Audrey DeVries" onClick="javascript:preview(1);">
        </div>
        <div id="tab_2" class="tab">
            <img id="image_2" src="garret_dranichak.jpg" title="Garrett Dranichak" onClick="javascript:preview(2);">
        </div>
	<div id="tab_3" class="tab">
            <img id="image_3" src="thanh_to.jpg" title="Thanh To" onClick="javascript:preview(3);">
        </div>
	<div id="tab_4" class="tab">
            <img id="image_4" src="elaine_sotherden.jpg" title="Elaine Sotherden" onClick="javascript:preview(4);">
        </div>
        <div id="tab_5" class="tab">
            <img id="image_5" src="ryan_grove.jpg" title="Ryan Grove" onClick="javascript:preview(5);">
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
                <div class="response">Here at Clemson I served as President of SIAM this past year and Treasurer and Secretary in the past. I am also involved with a lot of one-on-one tutoring.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">I love teaching and am hoping to be a college math professor after getting my PhD.</div>
            </li>
        </ul>
    </div>
    <div id="info_2" class="info" style="display: none;">
        <div class="name">Garrett Dranichak</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Pfeiffer University</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Operations Research (multicriteria robust optimization)</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Margaret Wiecek</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">I am originally from Dublin, OH</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I am running for the office of Vice President in order to continue to serve the math graduate students by providing social events, opportunities to present research, and engage in professional development.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">I am currently involved with Clemson's SIAM Student Chapter as acting Secretary. In addition, I am a member of the student chapter of AWM, I volunteer at the Clemson Calculus Challenge, and I help out with the Math-In every semester before finals. </div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response"> After my degree, I plan to work as a professor at a smaller liberal arts college, similar to the university I attended for undergrad. I appreciate the opportunity to teach as a graduate student here at Clemson, and I thoroughly enjoy working with the students.</div>
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
                <div class="response">I'm the current treasurer for SIAM. I used to do some martial art with Cuong Nhu.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">I would either pursue a postdoc or work in the industry. It really depends on what opportunities I might have upon graduation and I'm open to all possibilities.</div>
                </li>
        </ul>
    </div>
    <div id="info_4" class="info" style="display: none;">
        <div class="name">Elaine Sotherden</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Grove city college.</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Statistics</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">N/A</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Pennsylvania</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">Really like helping SIAM.  </div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">SIAM (2013-2014), GSG (2013-2014), Actuarial Club (2014-2015), AWM (2013-2015)</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">Research in industry (hopefully).</div>
                </li>
        </ul>
    </div>
 <div id="info_5" class="info" style="display: none;">
        <div class="name">Ryan Grove</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Indiana University of Pennsylvania</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Comp</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Timo Heister</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">I'm working towards a PhD.</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Portage, PA</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I know HTML and like managing the website.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">Create and captain intramural teams for our department and currently the webmaster for SIAM.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">Enjoy life, duh. Same thing I do now.</div>
                </li>
        </ul>
    </div>
</div>


</div>
</div>
<?php print_footer(); ?>
</body>
</html>
