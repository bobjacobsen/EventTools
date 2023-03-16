<?php require_once('access_and_open.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Roster</title>
    <style type="text/css">
        div.session { margin-top: 3em; font-weight:bold; font-size: 18pt; font-family: Ariel; }
        span.date { font-weight:bold; font-size: 18pt; font-family: Ariel; }
        th.carpool { font-weight:normal; font-size: 10pt; font-family: Ariel; }
        td.num { font-weight:normal; font-size: 14pt; font-family: Ariel; }
        td.attendee { font-weight:normal; font-size: 14pt; font-family: Ariel; }
        td.slot { font-weight:normal; font-size: 14pt; font-family: Ariel; }
        div.break { page-break-before:always; page-break-after:always;}
    </style>
</head>
<body>

<?php
include_once('mysql2i.class.php'); // migration step

require_once('ops_assign_common.php');
require_once('utilities.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" or "?start=" in the arguments
if (! ($args["cy"]) ) {
    echo '<h1>'.$event_tools_event_name.' Op Session Roster</h1>';
    echo '<a href="index.php">Back to main page</a><p/>';
    echo '<form method="get" action="ops_print_summary.php">
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
if ($date != NULL && $date != "") {
    if (strlen($date) == 1) $date = '0'.$date;
    $where = ' AND start_date LIKE "2___-__-'.substr($date,-2).'%" ';
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

echo '<div class="session">'.mysql_result($result,$i,"show_name").' <div>';
echo '<span class="date">'.mysql_result($result,$i,"start_date").' <span>';
echo '<table><tr><th></th><th class="carpool">Seats for<br/>carpool?</th><th></th></tr>';
$colnum = 1;

for ($i=0; $i < $num; $i++) {
    // if doesn't match, do a session break
    if ($title != mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date")) {
        $title = mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date");
        // first end old session
        if ($count > 0) {
            for ($j = 0; $j < $count; $j++) echo '<tr><td class="num">'.($colnum++).'</td><td>____</td><td class="slot"><pre>__________________________________________________</pre></td></tr>';
        }
        $count = 0+mysql_result($result,$i,"spaces");
        echo '</table>';
        // page break
        echo '<div class="break"></div>';
        // finally, start new header
        echo '<div class="session">'.mysql_result($result,$i,"show_name").' <div>';
        echo '<span class="date">'.mysql_result($result,$i,"start_date").' <span>';
        echo '<table><tr><th></th><th class="carpool">Seats for<br/>carpool?</th><th></th></tr>';
        $colnum = 1;
    }
    // show if allocated
    if (1 == mysql_result($result,$i,"status")) {
        echo '<tr><td class="num">'.($colnum++).'</td><td>____</td><td class="attendee">';
        echo mysql_result($result,$i,"customers_firstname").' ';
        echo mysql_result($result,$i,"customers_lastname").' ';
        echo '</td></tr>';
        $count--;
    }
}

// end last session
if ($count > 0) {
    for ($j = 0; $j < $count; $j++) echo '<tr><td class="num">'.($colnum++).'</td><td>____</td><td class="slot"><pre>__________________________________________________</pre></td></tr>';
}
echo '</table>';


return;

?>
</body>
</html>
