<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Advanced Section</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />
    <link href="layouts.css" rel="stylesheet" type="text/css" />
    <link href="miscevent.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1>X2011West Advanced Section</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$order = parse_order();

echo '<h2>Layout Tours</h2>';
$where = 'STRCMP(start_date, "2011-07-03 12:00:00") < 0';
format_all_layout_tours_as_8table($where, $order, "format_all_layouts.php#");

echo '<h2>General and Rail Tours</h2>';
$where = "number like 'A%'";
format_all_general_tours_as_8table($where, $order);

echo '<h2>Other Events</h2>';
$where = 'STRCMP(start_date, "2011-07-03 12:00:00") < 0';
format_all_misc_events_as_3table($where, $order);

?>
</body>
</html>
