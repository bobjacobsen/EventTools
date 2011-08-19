<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Layout Index</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h2>X2011West Layout Index</h2>  
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

$where = parse_layout_query();
$order = parse_order();

index_layouts("format_all_layouts.php#", $where, $order);

?>
</body>
</html>
