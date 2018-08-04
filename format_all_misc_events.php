<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Misc Events</title>

    <link href="miscevent.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h1><?php require_once('access.php'); echo $event_tools_event_name; ?> Misc Events</h1>  
<a href="index.php">Back to main page</a>
<p>

<form method="get">
    <button type="submit">Filter on tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_misc_event_query();
$order = parse_order();

format_all_misc_events_as_3table($where, $order);

?>

</body>
</html>
