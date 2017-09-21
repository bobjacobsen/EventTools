<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Layout</title>

    <link href="layouts.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1><?php require_once('access.php'); echo $event_tools_event_name; ?> Layout</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_layout_query();

format_all_layouts_as_table("format_all_layout_tours.php#", $where);

?>
</body>
</html>
