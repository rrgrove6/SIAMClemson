<?php
include_once "officer_lib.php";

function generate_spreadsheet($name, $data)
{
    $headers = array();

    for($i = 1; $i <= count($data[0]); $i++)
    {
        $headers[] = "pref_$i";
    }

    // generate out spreadsheet
    for($i = 0; $i < count($data); $i++)
    {
        $data[$i] = implode(",", $data[$i]);
    }

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=\"" . $name . "_data.csv\";");

    print(implode(",", $headers) . "\n");
    print(implode("\n", $data));
}

$data_set = get_str_value($_REQUEST, "data_set");

if($data_set == "president")
{
    generate_spreadsheet("president", get_voting_data("President"));
}
else if($data_set == "vice_president")
{
    generate_spreadsheet("vice_president", get_voting_data("Vice President"));
}
else if($data_set == "treasurer")
{
    generate_spreadsheet("treasurer", get_voting_data("Treasurer"));
}
else if($data_set == "secretary")
{
    generate_spreadsheet("secretary", get_voting_data("Secretary"));
}
else
{
    // print the links to each download
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Election Data</title>
</head>
<body>
<p>Click on the links below to download the data for each of the offices in the election.</p>
<ul>
    <li><a href="download_data.php?data_set=president">President data</a></li>
    <li><a href="download_data.php?data_set=vice_president">Vice President data</a></li>
    <li><a href="download_data.php?data_set=treasurer">Treasurer data</a></li>
    <li><a href="download_data.php?data_set=secretary">Secretary data</a></li>
</ul>
</body>
</html>
<?php
}
?>