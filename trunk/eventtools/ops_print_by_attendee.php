<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Assignments</title>
    <style type="text/css">
        @media print
        {
          table { page-break-after:auto }
          tr    { page-break-inside:avoid; page-break-after:auto }
          td    { page-break-inside:avoid; page-break-after:auto }
          thead { display:table-header-group }
          tfoot { display:table-footer-group }
        }
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
    echo '<form method="get" action="ops_print_by_attendee.php">
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
    ORDER BY customers_lastname, customers_firstname, start_date, show_name
    ;
";

//echo $query;
$result=mysql_query($query);
$num = mysql_numrows($result);

$title = mysql_result($result,0,"customers_firstname").' '.mysql_result($result,0,"customers_lastname");
$first = TRUE;

echo '<table border="1"><tr>';
echo '<tr><th></th><th>2012-04-25</th><th>2012-04-26</th><th>2012-04-27</th><th>2012-04-28</th><th>2012-04-29</th></tr>';
echo '<td>'.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").' </td>';

$colarray = array("2012-04-25","2012-04-26","2012-04-27","2012-04-28","2012-04-29");
$colnum = 0;

for ($i=0; $i < $num; $i++) {
    // if doesn't match, do a session break
    if ($title != mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname")) {
        $title = mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname");
        // first end old session
        while ( $colnum < 5 ) {
            echo '<td></td>';
            $colnum++;
        }
        
        echo '</tr>';
        // finally, start layout
        echo '<td>'.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").' </td>';
        $colnum = 0;
    }
    // show if allocated
    if (1 == mysql_result($result,$i,"status")) {
        // skip to here
        while ($colarray[$colnum] != substr(mysql_result($result,$i,"start_date"), 0, 10) && $colnum < 5) {
            echo '<td></td>';
            $colnum++;
        }
        echo '<td class="attendee">';
        echo mysql_result($result,$i,"show_name");
        echo '</td>';
        $colnum++;
    }
}

echo '</tr></table>';


return;

?>
</body>
</html>
