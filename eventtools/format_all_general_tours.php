<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Prototype/General/Rail Tours</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1>X2011West Prototype/General/Rail Tours</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_general_tour_query();
$order = parse_order();

format_all_general_tours_as_8table($where, $order);

?>
</body>
</html>
