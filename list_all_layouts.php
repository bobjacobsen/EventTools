<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<?php
    require_once('access.php');

echo '<html>
<head>
    <title>'.$event_tools_event_name.' Layout List</title>

        <link href="layouts.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1>'.$event_tools_event_name.' Layout List</h1>
<a href="index.php">Back to main page</a>
';
include_once('mysql2i.class.php'); // migration step

require_once('access.php');
require_once('utilities.php');
require_once('listing.php');
require_once('parsers.php');

// Over-ride globals for on visibility here
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

$where = parse_layout_query();
$order = parse_order();

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");


$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_layout_with_layout_tours ";

if ($where != NULL)
    $query = $query.' WHERE '.$where.' ';

if ($order != NULL)
    $query = $query.' ORDER BY '.$order.' ';
else
    $query = $query.'ORDER BY layout_owner_lastname, layout_owner_firstname';

$query = $query."
    ;
";

$result=mysql_query($query);

$i=0;
$num = mysql_numrows($result);
$lastmajorkey= "";

while ($i < $num) {
    $majorkey = mysql_result($result,$i,"layout_id");
    if ($lastmajorkey != $majorkey) {
        echo "<hr>\n";
        // new layout, do all layout-specific information
        $lastmajorkey = $majorkey;
        list_layout_heading($result,$i);
        echo "<p>\n";
        if (mysql_result($result,$i,"id")!='')
            echo 'tours:<br>';
    }
    // list all layout tours this layout belongs to
    if (mysql_result($result,$i,"id")!='')
        list_tour_in_layout($result,$i);

    $i++;
}

echo "<hr>\n";

// done, clean up

mysql_close();





?>
</body>
</html>
