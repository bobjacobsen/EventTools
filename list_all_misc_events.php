<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Misc Event List</title>

    <link href="miscevent.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h2><?php
include_once('mysql2i.class.php'); // migration step
require_once('access.php'); echo $event_tools_event_name; ?> Misc Event List</h2>
<a href="index.php">Back to main page</a>
<?php
require_once('access.php');
require_once('utilities.php');
require_once('listing.php');
require_once('parsers.php');

// Over-ride globals for on visibility here
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

$where = parse_general_tour_query();
$order = parse_order();

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_misc_events_with_tags ";

if ($where != NULL)
    $query = $query.' WHERE '.$where.' ';

if ($order != NULL)
    $query = $query.' ORDER BY '.$order.' ';
else
    $query = $query.'ORDER BY start_date,  number';

$query = $query."
    ;
";

$result=mysql_query($query);

$num=mysql_numrows($result);

$i=0;
$lastmajorkey= "";

while ($i < $num) {
    if ($lastmajorkey != mysql_result($result,$i,"id")) {
        $lastmajorkey = mysql_result($result,$i,"id");

        list_misc_events($result,$i); // from listing.php
        echo "<p>\n";
    }

    $i++;
}

mysql_close();

?>
</body>
</html>
