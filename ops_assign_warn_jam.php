<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Update: Warning</title>
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
<h1>Op Session Update: Warning</h1>  

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
echo '<h2>Cycle: '.$cycle.'</h2>';

$id = $args["id"];

// open db

global $opts, $event_tools_db_prefix, $event_tools_href_add_on, $cycle;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// retrieve info
$query = "SELECT * 
            FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
            WHERE opsreq_req_status_id = '".$id."'
            ;";
//echo '<p>'.$query.'<p>';
$result = mysql_query($query);

if (mysql_numrows($result) != 1 ) echo '<b>Internal error: should have matched once, found '.mysql_numrows($result).'</b><p/>'; 

// Warning

echo 'You are requesting to force an assignment of '
    .mysql_result($result,0,"customers_firstname").' '.mysql_result($result,0,"customers_lastname")
    .'&lt;'.mysql_result($result,0,"opsreq_person_email").'&gt;';
echo ' to the '.mysql_result($result,0,"show_name").' '.mysql_result($result,0,"start_date").' session.';
echo '<p/>';

echo '<b>'.$args["reason"],'</b>';

// head buttons

echo '<form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Cancel and return to grouping</button></form>';

echo '<form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<input type="hidden" name="op" value="A">';
echo '<input type="hidden" name="id" value="'.$id.'">';
echo '<button type="submit">Force the assignment</button></form>';


?>
</body>
</html>
