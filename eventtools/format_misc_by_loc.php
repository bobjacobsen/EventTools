<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Misc Events by Date and Location</title>

    <link href="miscevent.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h1>X2011West Misc Events by Date and Location</h1>  
<a href="index.php">Back to main page</a>
<p>

<!-- URLS with e.g. ?tag=DCC will select only items with specific tags -->

<form method="get" action="format_misc_by_loc.php">
    <button type="submit">Show only events with tag:</button>
    <select name="tag">
    <?php  require_once('access.php'); require_once('utilities.php');
    foreach(get_misc_event_tags() as $tag) { echo "<option>".$tag."</option>"; }
    ?>
    </select>
</form>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('locale.php');
require_once('parsers.php');

$where = parse_misc_event_query();

format_misc_events_by_loc("format_all_misc_events.php#", $where);

?>
</body>
</html>
