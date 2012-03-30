<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Roster</title>
    <style type="text/css">
    </style>
</head>
<body>

<?php

require_once('ops_assign_common.php');
require_once('utilities.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" or "?start=" in the arguments
if (! ($args["cy"]) ) {
    echo '<h1>'.$event_tools_event_name.' Op Session Roster</h1>';
    echo '<a href="index.php">Back to main page</a><p/>';
    echo '<form method="get" action="ops_print_by_layout.php">
        Cycle Name: <input  name="cy"></textarea>
        Date (blank or day-of-month, e.g. 25): <input  name="date"></textarea>
        <button type="submit">Start</button>
        </form>
    ';
    return;
}

$cycle = $args["cy"];
$date = $args["date"];
$where = "";
if ($date != NONE && $date != "") {
    if (strlen($date) == 1) $date = '0'.$date;
    $where = ' AND start_date LIKE "2012-04-'.substr($date,-2).'%" ';
}

// open db

global $opts, $event_tools_db_prefix, $event_tools_href_add_on, $cycle;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");


$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
    WHERE opsreq_group_cycle_name = '".$cycle."'
        AND show_name != \"\" AND start_date != \"\" ".$where."
    ORDER BY start_date, show_name, customers_lastname, customers_firstname
    ;
";

//echo $query;
$result=mysql_query($query);
$num = mysql_numrows($result);

$title = mysql_result($result,0,"show_name").' '.mysql_result($result,0,"start_date");
$date = date_from_long_format(mysql_result($result,0,"start_date"));
$count = 0+mysql_result($result,0,"spaces");
$first = TRUE;

echo '<table border="1"><tr>';
echo '<td>'.mysql_result($result,$i,"show_name").' </td>';
echo '<td>'.mysql_result($result,$i,"start_date").' </td>';
$colnum = 1;

for ($i=0; $i < $num; $i++) {
    // if doesn't match, do a session break
    if ($title != mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date")) {
        $title = mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date");
        // first end old session
        echo '</tr>';
        // finally, start layout
        echo '<tr><td>'.mysql_result($result,$i,"show_name").' </td>';
        echo '<td>'.mysql_result($result,$i,"start_date").' </td>';
        $colnum = 1;
    }
    // show if allocated
    if (1 == mysql_result($result,$i,"status")) {
        echo '<td class="attendee">';
        echo mysql_result($result,$i,"customers_firstname").' ';
        echo mysql_result($result,$i,"customers_lastname").' ';
        echo '</td>';
        $count--;
    }
}

echo '</tr></table>';


return;

?>
</body>
</html>
