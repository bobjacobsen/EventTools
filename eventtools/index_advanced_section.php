<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Advanced Section</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />
    <link href="layouts.css" rel="stylesheet" type="text/css" />
    <link href="miscevent.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1><?php require_once('access.php'); echo $event_tools_event_name; ?> Advanced Section Index</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('indexing.php');
require_once('parsers.php');

// Over-ride globals for full visibility here
$event_tools_show_min_value = 0;
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is


$order = parse_order();

echo '<h2>Layout Tours</h2>';
$where = 'STRCMP(start_date, "2011-07-03 12:00:00") < 0';
index_layout_tours("format_all_layout_tours.php#", $where);

echo '<h2>General and Rail Tours</h2>';
$where = "number like 'A%'";
index_general_tours("format_all_general_tours.php?number=", $where);

echo '<h2>Other Events</h2>';
$where = 'STRCMP(start_date, "2011-07-03 12:00:00") < 0';
index_misc_events("format_all_misc_events.php?id=", $where);

?>
</body>
</html>
