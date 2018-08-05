<?php require_once('access.php'); require_once('secure.php'); ?>
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

$min_status = 60;                  // min status below which get warning; 40 is "under construction", 50 is "special", 60 is "approved"^M

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

echo 'Use this page to move requests from one session to another.<p>';
echo 'On the line for the session from which you want to move requests, select the new session for those requests, and click "Move to".<br>';
echo 'The attendee requests will be changed from the old session to the newly-selected one, keeping all the priority information unchanged.';
echo '<p>';
echo '<span style="background: #ffa0a0">Red highlights</span> indicate an error:  requests still on a session that\'s been disabled.';

// is there a requested move?
if ($args["move"]) {
    
    // doing this the database way!
    // UPDATE fruits SET name='oranges' WHERE name='apples';
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri1='".$args["to"]."' WHERE opsreq_pri1='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri2='".$args["to"]."' WHERE opsreq_pri2='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri3='".$args["to"]."' WHERE opsreq_pri3='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri4='".$args["to"]."' WHERE opsreq_pri4='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri5='".$args["to"]."' WHERE opsreq_pri5='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri6='".$args["to"]."' WHERE opsreq_pri6='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri7='".$args["to"]."' WHERE opsreq_pri7='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri8='".$args["to"]."' WHERE opsreq_pri8='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri9='".$args["to"]."' WHERE opsreq_pri9='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri10='".$args["to"]."' WHERE opsreq_pri10='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri11='".$args["to"]."' WHERE opsreq_pri11='".$args["move"]."';");
    mysql_query("UPDATE ".$event_tools_db_prefix."eventtools_opsession_req SET opsreq_pri12='".$args["to"]."' WHERE opsreq_pri12='".$args["move"]."';");
}
// done with move, if any, display

// get status value mapping
$query="
    SELECT *
        FROM ".$event_tools_db_prefix."eventtools_event_status_values
        ;
    ";
//echo $query;

// get list of all tours, build array of choices
$resultStatus=mysql_query($query);
$numStatus = mysql_numrows($resultStatus);
$i = 0;
$statusMap = array();
while ($i < $numStatus) {
    $statusMap[mysql_result($resultStatus,$i,"event_status_code")] = mysql_result($resultStatus,$i,"event_status_name");
    $i++;
}

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
echo '<tr><th>Layout</th><th>Start</th><th>Spots</th><th>Sum</th><th>Status</th><th></th></tr>';

$i = 0;

while ($i < $numSessions) {
    $key = mysql_result($resultSessions,$i,"ops_id");
    echo '<tr>';
    
    // layout name
    echo '<td><a href="?session='.mysql_result($resultSessions,$i,"ops_id").'">'.mysql_result($resultSessions,$i,"show_name").'</a></td>';
    
    // start time
    echo '<td>'.mysql_result($resultSessions,$i,"start_date").'</td>'."\n";
        
    // spaces (called spots)
    echo '<td align="right">'.mysql_result($resultSessions,$i,"spaces").'</td>'."\n";

    // sum column
    $j = 0;
    $sum = 0;
    while ($j < 12) {
        $sum = $sum + $stats[$key][$j];
        $j++;
    }
    echo '<td align="right">';
    if (mysql_result($resultSessions,$i,"status_code") < $min_status 
            && $sum != 0
            && mysql_result($resultSessions,$i,"spaces") > 0)
        echo '<div style="background: #ffa0a0">';  // red if not at or above min_status
    else
        echo '<div>';            
    echo $sum;
    echo '</div></td>'."\n";
    
    // status
    echo '<td>'.$statusMap[mysql_result($resultSessions,$i,"status_code")].'</td>';
    
    // set up the move-to form
    echo "\n".'<td>';
    echo '<form onsubmit="
        var sel = 
            document.getElementById(\'to'
                .mysql_result($resultSessions,$i,"ops_id")
                .'\');
        var dest = sel.options[sel.selectedIndex].text;
        if (dest == \'\') {
            return confirm(\'Really erase requests?\');
        } else {
            return confirm(
                \'Move to \'+dest+\'?\'
            );
        }
      ">';
    echo 'To:';
    echo "\n".'<select name="to" id="to'.mysql_result($resultSessions,$i,"ops_id").'">'."\n";
    $n = 0;
    while ($n < $numSessions) {
        if ($i != $n) 
            echo '  <option value="'.mysql_result($resultSessions,$n,"ops_id").'">'.mysql_result($resultSessions,$n,"show_name")." ".mysql_result($resultSessions,$n,"start_date").'</option>'."\n";
        $n++;
    }
    
    echo '</select>';
    echo "\n".'<button type="submit" name="move" value="'.$key.'">Move!</button>';
    echo '</form></td>';

    $i++;
    
}

echo '</table>';
echo '<p>';
echo '';
echo '<p>';

echo "</body></html>";
