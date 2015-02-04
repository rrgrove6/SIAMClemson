<?php
include_once "../picnic_lib.php";

$form_name = get_str_value($_REQUEST, "form_name");

$alert_text = "";

if($form_name == "picnic_settings")
{
    set_current_semester(get_str_value($_REQUEST, "semester"));
    set_current_year(get_str_value($_REQUEST, "year"));
    set_signup_start(get_str_value($_REQUEST, "signup_start_month") . "/" . get_str_value($_REQUEST, "signup_start_day") . "/" . get_str_value($_REQUEST, "signup_start_year"));
    set_signup_end(get_str_value($_REQUEST, "signup_end_month") . "/" . get_str_value($_REQUEST, "signup_end_day") . "/" . get_str_value($_REQUEST, "signup_end_year"));
    set_discount_end(get_str_value($_REQUEST, "discount_end_month") . "/" . get_str_value($_REQUEST, "discount_end_day") . "/" . get_str_value($_REQUEST, "discount_end_year"));
    set_signup_discount(get_str_value($_REQUEST, "early_signup_discount"));
    set_food_discount(get_str_value($_REQUEST, "food_discount"));
    set_grad_student_price(get_str_value($_REQUEST, "grad_student_price"));
    set_faculty_staff_price(get_str_value($_REQUEST, "faculty_staff_price"));
    
    $alert_text = "Settings saved";
}
?>

<!doctype html>
<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
    <link rel="stylesheet" href="/~siam/styles/siam.css" type="text/css"/>
    <title>SIAM Picnic Settings</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
        // display alert if the form was submitted
        show_save_confirmation();
	});

function show_save_confirmation()
{
    if($(".alert").html().length > 0)
    {
        $(".alert").show();
        $(".alert").delay(10000).fadeOut(600);
    }
}
</script>
    <style type="text/css">   
    .settings_table
    {
         margin-top: 5px;
    }
    
    .settings_table td
    {
        padding-bottom: 8px;
        padding-right: 15px;
    }
    
    .settings_table td:last-child
    {
        text-align: right;
    }
    
    .settings_table td:last-child input
    {
        text-align: right;
    }
    
    .table_header
    {
        font-family: Arial;
        font-weight: bold;
        font-size: 20px;
    }
    
    .alert
    {
        display: none;
        padding: 2px;
        margin: 10px;
        margin-top: 20px;
        background: #FFFF99;
        border: 2px solid #990000;
        font-weight: bold;
        font-size: 20px;
        margin: 0px auto;
    }
    </style>
</head>
<body style="text-align: center;">
<div style="width: 700px; margin: 0 auto; text-align: left;">
<div><a href="http://www.siam.org/" title="SIAM website"><img alt="SIAM logo" src="/~siam/img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 46px; text-align: center; margin-bottom: 0px; margin-top: 20px; padding-top: 30px;">Picnic Settings</p></div>
<hr style="color: #ff6206; clear: left;">
<div style="text-align: center;">
<div style="width: 700px; margin-left: auto; margin-right: auto; position: relative; text-align: left;">


<?php
$semester = get_current_semester();
$year = get_current_year();
$discount_end_date = strtotime(get_discount_end());
$signup_start_date = strtotime(get_signup_start());
$signup_end_date = strtotime(get_signup_end());
$signup_discount = get_signup_discount();
$food_discount = get_food_discount();
$grad_student_price = get_grad_student_price();
$faculty_staff_price = get_faculty_staff_price();


?>
<div style="min-height: 40px; text-align: center; margin-top: 10px;">
    <span class="alert"><?php print($alert_text); ?></span>
</div>
<form method="POST" action="picnic_settings.php">
<table class="settings_table" align="center">
    <tr>
        <td class="table_header">Setting</td>
        <td class="table_header">Value</td>
    </tr>
    <tr>
        <td>Current semester:</td>
        <td>
<?php
$semester_html = get_html_dropdown(array("fall", "spring"), array("Fall", "Spring"), "semester", $semester);
print($semester_html)
?>
            <input type="text" name="year" style="width: 40px;" value="<?php print($year);?>">
        </td>
    </tr>
    <tr>
        <td>Signup open:</td>
        <td>
            <input type="text" name="signup_start_month" style="width: 20px;" value="<?php print(date("n", $signup_start_date)); ?>"> /
            <input type="text" name="signup_start_day" style="width: 20px;" value="<?php print(date("j", $signup_start_date)); ?>"> /
            <input type="text" name="signup_start_year" style="width: 20px;" value="<?php print(date("y", $signup_start_date)); ?>">
        </td>
    </tr>
    <tr>
        <td>Signup close:</td>
        <td>
            <input type="text" name="signup_end_month" style="width: 20px;" value="<?php print(date("n", $signup_end_date)); ?>"> /
            <input type="text" name="signup_end_day" style="width: 20px;" value="<?php print(date("j", $signup_end_date)); ?>"> /
            <input type="text" name="signup_end_year" style="width: 20px;" value="<?php print(date("y", $signup_end_date)); ?>">
        </td>
    </tr>
    <tr>
        <td>Discount ends the morning of:</td>
        <td>
            <input type="text" name="discount_end_month" style="width: 20px;" value="<?php print(date("n", $discount_end_date)); ?>"> /
            <input type="text" name="discount_end_day" style="width: 20px;" value="<?php print(date("j", $discount_end_date)); ?>"> /
            <input type="text" name="discount_end_year" style="width: 20px;" value="<?php print(date("y", $discount_end_date)); ?>">
        </td>
    </tr>
    <tr>
        <td>Early signup discount amount:</td>
        <td>
            $ <input type="text" name="early_signup_discount" style="width: 40px;" value="<?php print($signup_discount);?>">
        </td>
    </tr>
    <tr>
        <td>Food discount amount:</td>
        <td>
            $ <input type="text" name="food_discount" style="width: 40px;" value="<?php print($food_discount);?>">
        </td>
    </tr>
    <tr>
        <td>Grad student price:</td>
        <td>
            $ <input type="text" name="grad_student_price" style="width: 40px;" value="<?php print($grad_student_price);?>">
        </td>
    </tr>
    <tr>
        <td>Faculty price:</td>
        <td>
            $ <input type="text" name="faculty_staff_price" style="width: 40px;" value="<?php print($faculty_staff_price);?>">
        </td>
    </tr>
</table>
<div style="text-align: center;">
    <input type="submit" value="Save Settings">
    <input type="hidden" name="form_name" value="picnic_settings">
</div>
</form>

</div>
</div>
</div>
</body>
</html>