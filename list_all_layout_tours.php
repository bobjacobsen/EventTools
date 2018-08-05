<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Layout Tour List</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h2><?php require_once('access.php'); echo $event_tools_event_name; ?> Layout Tour List</h2>  
<a href="index.php">Back to main page</a>
<?php
include_once('mysql2i.class.php'); // migration step
require_once('access.php');
require_once('utilities.php');
require_once('listing.php');
require_once('parsers.php');

// Over-ride globals for on visibility here
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$where = parse_layout_tour_query();
$order = parse_order();

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_layout_tour_with_layouts ";

if ($where != NONE)
    $query = $query.' WHERE '.$where.' ';

if ($order != NONE)
    $query = $query.' ORDER BY '.$order.' ';
else
    $query = $query.'ORDER BY number, layout_tour_link_order';

$query = $query."
    ;
";

//echo $query;
$result=mysql_query($query);

$i=0;
$num=mysql_numrows($result);
$lastmajorkey= "";
$first = 0;

while ($i < $num) {
    if ($lastmajorkey != mysql_result($result,$i,"id")) {
        $lastmajorkey = mysql_result($result,$i,"id");

        // include common stuff
        list_tour_heading($result,$i); // from formatting.php
        echo "<p>layouts:<p>\n";

    }
        
    list_layout_in_tour($result,$i);
    
    $i++;
}

mysql_close();    

?>
</body>
</html>
