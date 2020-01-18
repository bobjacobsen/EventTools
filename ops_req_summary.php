<?php require_once('access_and_open.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Op Session Request Summary</title>
</head>
<body>
<h3>Op Session Request Summary</h3>
<a href="index.php">Back to main page</a>
<p>
<?php
include_once('mysql2i.class.php'); // migration step

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing convention information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011, 2012
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

function score($choice,$key) {
    global $stats;
    if ($key == NONE || $key == '') return;
    $stats[$key][$choice] = $stats[$key][$choice]+1;
}

function summarize_question($shortOptionName, $where) {
    global $event_tools_db_prefix;
    
    $query="
        SELECT customers_id
            FROM ( ".$event_tools_db_prefix."eventtools_customer_cross_options_and_values
                JOIN ".$event_tools_db_prefix."eventtools_opsession_req 
                ON customers_email_address = opsreq_person_email )
            ";
    if ($where) {
        $query = $query." ".$where." AND customer_option_value_value = \"Y\" ";
    } else {
        $query = $query." WHERE customer_option_value_value = \"Y\" ";
    }
    $query=$query."        
            AND customer_option_short_name = \"".$shortOptionName."\"
            ;
        ";
    //echo $query;
    $resultSurvey=mysql_query($query);
    return mysql_numrows($resultSurvey);
}

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

parse_str($_SERVER["QUERY_STRING"], $args);
$where = "";
if ($args["session"]) {
    $where = " WHERE ops_id = \"".$args["session"]."\"";
}

// get list of all tours, build array of choices

$query="
    SELECT *
        FROM ".$event_tools_db_prefix."eventtools_opsession_name
        ".$where."
        ORDER BY show_name, start_date
        ;
    ";
//echo $query;

$resultSessions=mysql_query($query);
$numSessions = mysql_numrows($resultSessions);

$stats = array();
$i = 0;

while ($i < $numSessions) {
    $key = mysql_result($resultSessions,$i,"ops_id");
    $stats[$key] = array(0,0,0,0,0,0,0,0);  // 0 to 7 for the choices offered


    $i++;
}


// accumulate statistics
$where = "";
if ($args["session"]) {
    $where = ' WHERE (
        opsreq_pri1 = "'.$args["session"].'" OR 
        opsreq_pri2 = "'.$args["session"].'" OR 
        opsreq_pri3 = "'.$args["session"].'" OR 
        opsreq_pri4 = "'.$args["session"].'" OR 
        opsreq_pri5 = "'.$args["session"].'" OR 
        opsreq_pri6 = "'.$args["session"].'" OR 
        opsreq_pri7 = "'.$args["session"].'" OR 
        opsreq_pri8 = "'.$args["session"].'" OR 
        opsreq_pri9 = "'.$args["session"].'" OR 
        opsreq_pri10 = "'.$args["session"].'" OR 
        opsreq_pri11 = "'.$args["session"].'" OR 
        opsreq_pri12 = "'.$args["session"].'" 
         ) ';
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

while ($i < $numReqs) {
    score(0, mysql_result($resultReqs,$i,"opsreq_pri1"));
    score(1, mysql_result($resultReqs,$i,"opsreq_pri2"));
    score(2, mysql_result($resultReqs,$i,"opsreq_pri3"));
    score(3, mysql_result($resultReqs,$i,"opsreq_pri4"));
    score(4, mysql_result($resultReqs,$i,"opsreq_pri5"));
    score(5, mysql_result($resultReqs,$i,"opsreq_pri6"));
    score(6, mysql_result($resultReqs,$i,"opsreq_pri7"));
    score(7, mysql_result($resultReqs,$i,"opsreq_pri8"));
    score(8, mysql_result($resultReqs,$i,"opsreq_pri9"));
    score(9, mysql_result($resultReqs,$i,"opsreq_pri10"));
    score(10, mysql_result($resultReqs,$i,"opsreq_pri11"));
    score(11, mysql_result($resultReqs,$i,"opsreq_pri12"));
    $i++;
}


// print table
echo '<table border="1">';
echo '<tr><th>Layout</th><th>Start</th><th>Spots</th><th>Sum</th><th>Wgt</th><th>Dm4</th><th>1st</th><th>2nd</th><th>3rd</th><th>4th</th><th>5th</th><th>6th</th><th>7th</th><th>8th</th><th>9th</th><th>10th</th><th>11th</th><th>12th</th></tr>';

$i = 0;
$grandtotal = array(0,0,"","",0,0,0,0,0,0,0,0,0,0,0,0);

while ($i < $numSessions) {
    $key = mysql_result($resultSessions,$i,"ops_id");
    echo '<tr>';
    echo '<td><a href="?session='.mysql_result($resultSessions,$i,"ops_id").'">'.mysql_result($resultSessions,$i,"show_name").'</a></td>';
    echo '<td>'.mysql_result($resultSessions,$i,"start_date").'</td>'."\n";
    
    $include = FALSE;
    if (mysql_result($resultSessions,$i,"show_name") !=NULL) $include = TRUE;
    
    echo '<td align="right">'.mysql_result($resultSessions,$i,"spaces").'</td>'."\n";
    if ($include) $grandtotal[0] += mysql_result($resultSessions,$i,"spaces");

    // sum column
    $j = 0;
    $sum = 0;
    while ($j < 12) {
        $sum = $sum + $stats[$key][$j];
        $j++;
    }
    echo '<td align="right">';
    if ($sum < mysql_result($resultSessions,$i,"spaces"))
        echo '<div style="background: #ffe0e0">';
    else
        echo '<div>';            
    echo $sum;
    echo '</div></td>'."\n";
    if ($include) $grandtotal[1] += $sum;
    
    // weight column
    echo '<td align="right">';
    $p = 0;
    $totweight = 0.0;
    while ($p < 12) {
        $totweight = $totweight + ($p+1)*$stats[$key][$p];
        $p++;
    }
    if ($sum > 0) echo number_format($totweight/$sum,2);
    echo '</div></td>'."\n";
    
    // Dm4 column: sum of 1st 4 / spots
    echo '<td align="right">';
    $p = 0;
    $totweight = 0.0;
    while ($p < 4) {
        $totweight = $totweight + $stats[$key][$p];
        $p++;
    }
    $slots = mysql_result($resultSessions,$i,"spaces");
    if ($slots > 0 and $totweight/$slots > 0) {
        if ($totweight/$slots > 1.0)
            echo '<div style="background: #ffe0e0">'.number_format($totweight/$slots,2).'</div>';
        else
            echo number_format($totweight/$slots,2);        
    }
    echo '</td>'."\n";
    
    
    $j = 0;
    $linetotal = 0;
    while ($j < 12) {
        $linetotal += $stats[$key][$j];
        if ($linetotal > mysql_result($resultSessions,$i,"spaces"))
            echo '<td align="right"><div style="background: #ffffd0">';
        else
            echo '<td align="right"><div>';            
        if ($stats[$key][$j] != 0) {
            echo $stats[$key][$j];
            if ($include) $grandtotal[$j+2+2] += $stats[$key][$j];
        } else {
            echo '&nbsp;';
        }
        echo '</div></td>'."\n";
        $j++;
    }
    $i++;
}

echo '<tr><th>Sum over rows</th><th></th>';
$j = 0;
while ($j < 2+2+12) {
    echo '<td>'.$grandtotal[$j].'</td>';
    $j++;
}
echo '</tr>';
echo '</table>';
echo '<p>';
echo 'Click on a session name to get more information on the attendees who have requested it.<br>';
echo 'Spots: Total available spaces in session; Red background indicates insufficient requests to fill the session<br>'; 
echo 'Wgt: Weighted average of request priority.<br>';
echo 'Dm4: ratio of priority 1-4 requests to session capacity (red if > 1.0, indicates more 1-4 requests than capacity).<br>';
echo 'The yellow request cells indicate insufficient room if all higher requests are assigned.<br>';
echo '<p>';


echo '<h3>Extra Question Totals</h3>';
    global $event_tools_db_prefix;    
    $query="
        SELECT *
            FROM ".$event_tools_db_prefix."eventtools_customer_options
            ORDER BY customer_option_order
            ;
        ";
    //echo $query;
    $resultOpt=mysql_query($query);
    
    $i = 0;
    $numOpt = mysql_numrows($resultOpt);
    echo "<table border=\"1\">\n";
    while ($i < $numOpt) {
        echo "   <tr><td>";
          echo '<a href="ops_reg_attendee_summary.php?order=value'.$i.'">';
            echo mysql_result($resultOpt,$i,"customer_option_long_name");
          echo "</a>";
        echo "</td><td>";
          echo '<a href="ops_reg_attendee_summary.php?order=value'.$i.'">';
            echo summarize_question(mysql_result($resultOpt,$i,"customer_option_short_name"), $where);
          echo "</a>";
        echo "</td></tr>\n";
        $i++;
    }
    echo "</table>\n";
echo '<p>';
echo 'Click on a question or answer count to get a time-sorted list of who said Yes.';
echo '<p>';




echo '<h3>Requestors</h3>';

$i = 0;

echo "Total requests: ".$numReqs;

echo '<table border="1">';
echo '<tr>';
echo '<th><a href="ops_req_summary.php?order=cfirstname">First</a></th>';
echo '<th><a href="ops_req_summary.php?order=clastname">Last</a></th>';
echo '<th><a href="ops_req_summary.php?order=email">Email</a></th>';
echo '<th><a href="ops_req_summary.php?order=create">Created Date</a></th>';
echo '<th><a href="ops_req_summary.php?order=update">Updated Date</a></th>';
echo '<th><a href="ops_req_summary.php?order=category">Attendee<br/>Category</a></th>';
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
echo '<p>';
echo 'Click on a column header to sort the results.<br>';
echo 'Category numbers can be <a href="http://localhost/eventtools/edit_user_req_group.php">changed here</a>.<br>';
echo '<p>';


echo "</body></html>";
