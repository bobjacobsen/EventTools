<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Display Assignments</title>
    <style type="text/css">
        span.released { background: #ffffff; }
        span.assigned { background: #b0b0ff; }
        span.conflict { background: #ffc0c0; }
        span.filled   { background: #c0ffc0; }
        span.disabled { background: #ff8080; }
        
        td.session { width: 600px; }
    </style>
</head>
<body>
<h1>Op Session Display Assignments</h1>  

<?php

include_once('mysql2i.class.php'); // migration step
require_once('ops_assign_common.php');




// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" or "?start=" in the arguments
if (! ($args["cy"]) ) {
    echo 'This page has to be entered from the <a href="ops_assign_group.php">grouping page</a>.<p/>';
    echo "Please click on that link and start there.<p/>";
    return;
} else {
    $cycle = $args["cy"];
}

// here, the cycle name exists, 
echo 'Cycle: '.$cycle.'<p/>';

// Load it

global $opts, $event_tools_db_prefix, $event_tools_href_add_on, $cycle;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

echo '<table><tr>';

echo '<td><form method="get" action="ops_assign_group.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Return to grouping</button></form></td>';

echo '<td><form method="get" action="ops_assign_update.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Go to update requests</button></form></td>';

echo '<td><form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Return to set assignments</button></form></td>';

echo '</tr></table>';

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ORDER BY customers_lastname, opsreq_person_email
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);
if ($num <= 0) echo "<br/>Query failed: ".$query.'<br/>';

echo '<table border="1">'."\n";

$key = "0";
$first = TRUE;

for ($i = 0; $i < $num; $i++) {
    if ($key != mysql_result($result,$i,"opsreq_person_email")) {
        if ($first) { $first = FALSE; } else { echo '</tr>'; }
        $key = mysql_result($result,$i,"opsreq_person_email");
        echo '<tr><td>'.mysql_result($result,$i,"opsreq_person_email").'</td>';
    }
    if (mysql_result($result,$i,"status") == "1") {
        echo '<td>';
        echo mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date");
        echo '</td>';
    }
}

echo '</tr></table><p/>';


?>
</body>
</html>
