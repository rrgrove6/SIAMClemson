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


<p>SIAM is pleased to announce the candidates for the four SIAM offices for next year. Some candidates are running for multiple offices. A green check mark in the table below indicates that the candidate in that row is running for the office in that column. Details on the election will be made available when voting begins on Thursday.</p>
<table align="center" cellspacing="0" style="margin-top: 15px; margin-bottom: 15px;">
    <tr class="candidate_header">
        <td class="candidate_name"></td>
        <td class="candidate_cell">President</td>
        <td class="candidate_cell">Vice President</td>
        <td class="candidate_cell">Treasurer</td>
        <td class="candidate_cell">Secretary</td>
    </tr>
    <tr>
        <td class="candidate_name">Audrey DeVries</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
    </tr>
    <tr>
        <td class="candidate_name">Michael Dowling</td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Paul Kuberry</td>
        <td class="candidate_cell"><img src="/~siam/img/check.png"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
        <td class="candidate_cell"></td>
    </tr>
    <tr>
        <td class="candidate_name">Elaine Sotherden</td>
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
            <img src="michael_dowling.jpg" title="Michael Dowling" onClick="javascript:preview(2);">
        </div>
        <div id="tab_3" class="tab">
            <img src="paul_kuberry.jpg" title="Paul Kuberry" onClick="javascript:preview(3);">
        </div>
        <div id="tab_4" class="tab">
            <img src="elaine_sotherden.jpg" title="Elaine Sotherden" onClick="javascript:preview(4);">
        </div>
    </div>
    <div id="info_1" class="info">
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
                <div class="response">Dr. Ebrahim Nasrabadi</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">MS (1st year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Lancaster, PA</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I love grad school, love the math department, and would love to help organize and lead events.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">In my undergrad I was involved in music ensembles, Cru, Kappa Mu Epsilon, and IM volleyball. Here at Clemson I am simply involved with my classes and homework, but would like to be more involved in the math department.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">At this point I am leaning towards being a college professor, but I really just want to be a stay-at-home mom.</div>
            </li>
        </ul>
    </div>
    <div id="info_2" class="info" style="display: none;">
        <div class="name">Michael Dowling</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Bob Jones University (Greenville, SC)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Algebra</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Shuhong Gao</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD (3rd year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Metropolitan Greenville, SC</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">My short term goal is to facilitate the healthy relationship between SIAM and GSS. My longer-term goals are to promote interaction between the student SIAM chapters in the southeast and to begin an online semi-annual publication for Clemson's math graduate students.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">I helped organize GSS this year and was the vice president of SIAM.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">After I obtain my PhD, I plan to get a job in industry or academia.</div>
            </li>
        </ul>
    </div>
    <div id="info_3" class="info" style="display: none;">
        <div class="name">Paul Kuberry</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Clarion University (Clarion, PA)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Computational Mathematics (fluid-structure interactions)</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Hyesuk Lee</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">PhD (3rd year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">Pennsylvania</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I feel that the Clemson SIAM chapter helps to bring the mathematics department closer together through picnics and that it develops needed presentation skills in its members via the graduate student seminar, so I would like to show my appreciation and support by donating my time and energy as the SIAM chapter president.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">I was the president of the mathematics club at Clarion University and served as the treasurer for SIAM this year.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">I would like to become a professor and instruct courses in applied mathematics.</div>
            </li>
        </ul>
    </div>
    <div id="info_4" class="info" style="display: none;">
        <div class="name">Elaine Sotherden</div>
        <hr>
        <ul class="questionnaire">
            <li>
                <div class="question">Where did you attend undergrad?</div>
                <div class="response">Grove City College (Grove City, PA)</div>
            </li>
            <li>
                <div class="question">What is your concentration area at Clemson?</div>
                <div class="response">Statistics</div>
            </li>
            <li>
                <div class="question">Who is your advisor(s)?</div>
                <div class="response">Dr. Colin Gallagher</div>
            </li>
            <li>
                <div class="question">Are you currently working on a MS or a PhD?</div>
                <div class="response">MS (1st year)</div>
            </li>
            <li>
                <div class="question">What area of the US/world do you consider to be home?</div>
                <div class="response">I grew up in central NY and central PA.</div>
            </li>
            <li>
                <div class="question">Why you are running?</div>
                <div class="response">I am running for this office because I really want to be able to contribute more help to SIAM, especially our student chapter.</div>
            </li>
            <li>
                <div class="question">What things are you involved with at Clemson (or were involved in during undergrad)?</div>
                <div class="response">At Clemson, I've attended SIAM events as well as the AP stats day. I hope to join the graduate student government, and I teach a weekly children's class at a local church.</div>
            </li>
            <li>
                <div class="question">What do you plan to do after you get your degree?</div>
                <div class="response">After I get my degree, I plan to work in government or industry.</div>
            </li>
        </ul>
    </div>
</div>


</div>
</div>
<?php print_footer(); ?>
</body>
</html>
