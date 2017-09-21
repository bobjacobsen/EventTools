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
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011, 2012, 2013
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
require_once('options_utilities.php');

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

// get the list of extras
$queryExtras="
    SELECT *
        FROM ( 
        ".$event_tools_db_prefix."eventtools_customer_options
        ) 
        ".$where." ORDER BY customer_option_order 
        ;
    ";
//echo $queryExtras;
$resultExtras=mysql_query($queryExtras);
$numExtras= mysql_numrows($resultExtras);

// create table
echo '<table border="1">';
echo '<tr>';
echo '<th><a href="?order=cfirstname">First</a></th>';
echo '<th><a href="?order=clastname">Last</a></th>';
echo '<th><a href="?order=email">Email</a></th>';
echo '<th><a href="?order=create">Created Date</a></th>';
echo '<th><a href="?order=update">Updated Date</a></th>';
echo '<th><a href="?order=category">Attendee<br/>Category</a></th>';

// add columns for extras
$i = 0;
while ($i < $numExtras) {
    echo '<th><a href="?order=value'.$i.'">'.mysql_result($resultExtras,$i,"customer_option_short_name").'</a></th>';
    $i++;
}

// end of header
echo '</tr>';


// create a query that includes the extras

if (strlen($args["order"])>5 AND substr($args["order"],0,5) == "value") $order = urldecode($args["order"]).' DESC, customers_updated_date ';
else $order = parse_order();

if ($order == NONE) $order = "customers_lastname";

$query=options_select_statement();  // create default select statement
     
$query=$query."
        ".$where." ORDER BY ".$order." 
        ;
    ";
//echo $query;

$resultReqs=mysql_query($query);
$numReqs= mysql_numrows($resultReqs);


// for each line
$i = 0;
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
    // add options
    $j = 0;
    while ($j < $numExtras) {
        echo  '<td align="center">'.mysql_result($resultReqs,$i,"value".$j).'</td>';
        $j++;
    }
    echo '</tr>';
    $i++;
}
echo '</table>';


echo '</body></html>';
