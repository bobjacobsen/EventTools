<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Op Session Attendee Summary</title>
</head>
<body>
<h3>Op Session Attendee Summary</h3>
<a href="index.php">Back to main page</a>
<p>
<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing convention information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011, 2012, 2013
// -------------------------------------------------------------------------

//
// PHP to summarize the Operating Session requests
//

// -------------------------------------------------------------------------
// Configure:

$min_status = 40;                  // min status included; 40 is "under construction", 60 is "approved"
$update_quantity =  FALSE;         // TRUE to update quantity, FALSE to leave unchanged

// -------------------------------------------------------------------------


// Code starts here

require_once('utilities.php');

global $stats;

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

parse_str($_SERVER["QUERY_STRING"], $args);
$where = "";
if ($args["session"]) {
    $where = " WHERE ops_id = \"".$args["session"]."\"";
}
if ($args["q"]) {
    $where = " WHERE opsreq_opt".$args["q"]." = \"Y\"";
}

require_once('parsers.php');
$order = parse_order();
if ($order == NONE) $order = "customers_lastname";
$query="
    SELECT *
        FROM ( 
        ".$event_tools_db_prefix."eventtools_opsession_req LEFT JOIN ".$event_tools_db_prefix."customers
        ON ".$event_tools_db_prefix."eventtools_opsession_req.opsreq_person_email = ".$event_tools_db_prefix."customers.customers_email_address
        ) 
        ".$where." ORDER BY ".$order."
        ;
    ";
//echo $query;
$resultReqs=mysql_query($query);
$numReqs= mysql_numrows($resultReqs);

$i = 0;

echo '<table border="1">';
echo '<tr>';
echo '<th><a href="?order=cfirstname">First</a></th>';
echo '<th><a href="?order=clastname">Last</a></th>';
echo '<th><a href="?order=email">Email</a></th>';
echo '<th><a href="?order=create">Created Date</a></th>';
echo '<th><a href="?order=update">Updated Date</a></th>';
echo '<th><a href="?order=category">Attendee<br/>Category</a></th>';
echo '</tr>';

while ($i < $numReqs) {
    echo '<tr>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_firstname").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_lastname").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"opsreq_person_email").'</td>';
    echo  '<td>';
        if (("".mysql_result($resultReqs,$i,"customers_create_date")) != "")
            echo mysql_result($resultReqs,$i,"customers_create_date");
    echo '</td>';
    echo  '<td>';
        if (("".mysql_result($resultReqs,$i,"customers_updated_date")) != "0000-00-00 00:00:00")
            echo mysql_result($resultReqs,$i,"customers_updated_date");
    echo '</td>';
    echo  '<td align="center">'.mysql_result($resultReqs,$i,"opsreq_priority").'</td>';
    echo '</tr>';
    $i++;
}
echo '</table>';


echo '</body></html>';
