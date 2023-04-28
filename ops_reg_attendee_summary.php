<?php require_once('access_and_open.php'); require_once('secure.php'); ?>
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

include_once('mysql2i.class.php'); // migration step

//
// PHP to summarize the Operating Session requests
//

// -------------------------------------------------------------------------
// Configure:

$min_status = 40;                  // min status included; 40 is "under construction", 60 is "approved"
$update_quantity =  FALSE;         // TRUE to update quantity, FALSE to leave unchanged

// -------------------------------------------------------------------------


// Code starts here
require('access_and_open.php');
require_once('utilities.php');
require_once('options_utilities.php');

global $stats;

parse_str($_SERVER["QUERY_STRING"], $args);

// are we changing an option to start?
if (array_key_exists("id", $args) AND array_key_exists("option", $args) AND array_key_exists("curvalue", $args)) {
    // here to do an update
    $id = $args["id"];
    $option = $args["option"];
    $curvalue = $args["curvalue"];
    $newvalue = ($curvalue == "Y") ? "" : "Y";

    $email = $args["email"];
    $j = $args["j"];

    echo "updated ".$email." option ".$j." to ".($newvalue == "Y" ? "Y" : "N")."<br>";

    $update_query = "UPDATE ".$event_tools_db_prefix."eventtools_customer_option_values SET customer_option_value_value='".$newvalue."' WHERE customers_id='".$id."' AND customer_option_id='".$option."';";
    //echo $update_query;
    mysql_query($update_query);
}

// is this only for a specific session?
$where = "";
if (array_key_exists("session", $args)) {
    $where = " WHERE ops_id = \"".$args["session"]."\"";
}
if (array_key_exists("q", $args)) {
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
echo '<th><a href="?order=telephone">Telephone</a></th>';
echo '<th><a href="?order=cellphone">Cell Phone</a></th>';

global $event_tools_emergency_contact_info;
if ($event_tools_emergency_contact_info ) {
    echo '<th><a href="?order=cellphone">Emergency<br>Contact</a></th>';
    echo '<th><a href="?order=cellphone">Emergency<br>Phone</a></th>';
}

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

if ( array_key_exists("order", $args) AND strlen($args["order"])>5 AND substr($args["order"],0,5) == "value") $order = urldecode($args["order"]).' DESC, customers_updated_date ';
else $order = parse_order();

if ($order == NULL) $order = "customers_lastname";

$query=options_select_statement();  // create default select statement

$query=$query."
        ".$where." ORDER BY ".$order."
        ;
    ";
// echo $query;

$resultReqs=mysql_query($query);
$numReqs= mysql_numrows($resultReqs);


// for each line
$i = 0;
while ($i < $numReqs) {
    echo '<tr>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_firstname").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_lastname").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"opsreq_person_email").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_telephone").'</td>';
    echo  '<td>'.mysql_result($resultReqs,$i,"customers_cellphone").'</td>';

    if ($event_tools_emergency_contact_info ) {
        echo  '<td>'.mysql_result($resultReqs,$i,"customers_x2011_emerg_contact_name").'</td>';
        echo  '<td>'.mysql_result($resultReqs,$i,"customers_x2011_emerg_contact_phone").'</td>';
    }

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
        echo  '<td align="center">';
            // enclose in a link to an edit page
            echo '<a href="';
                    echo '?id='.mysql_result($resultReqs,$i,"customers_id");
                    echo '&option='.mysql_result($resultReqs,$i,"id".$j);
                    echo '&curvalue='.mysql_result($resultReqs,$i,"value".$j);
                    echo '&email='.mysql_result($resultReqs,$i,"opsreq_person_email");
                    echo '&j='.$j;
                    echo '">';

                echo '&nbsp;';
                echo mysql_result($resultReqs,$i,"value".$j);
                echo '&nbsp;';
            echo '</a>';
        echo '</td>';
        $j++;
    }
    echo '</tr>';
    $i++;
}
echo '</table>';


echo '</body></html>';
