<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<?php 
    require_once('access.php'); 

echo '<html>
<head>
    <title>'.$event_tools_event_name.' Layouts</title>

    <link href="layouts.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1>'.$event_tools_event_name.' Layouts</h1>  
<a href="index.php">Back to main page</a>
<p>
';

require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_layout_query();
$order = parse_order();

format_all_layouts_as_table("format_all_layout_tours.php?number=", $where, $order);

?>
</body>
</html>
