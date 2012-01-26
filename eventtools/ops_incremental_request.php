<?php
// this is PHP to log
// the contents of the $_REQUEST array called out by
// the values array.

function check_for_value($values, $check, $reqs)
{
    // look through values array, checking the layout items for a match with a specific value
    // print "<br/>check ";
    foreach ( $values as $k ) {
        if (substr($k, 0, 2) === "v_") { // is a layout
            $value = $_REQUEST[ $k ];
            if ($value == $check) { // got it, add layout to requests
                $reqs[] = $k;
                //print " (hit on ".$value.") ";
            }
        }
    }
    return $reqs;
}

global $values;

global $opts, $event_tools_db_prefix;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$email = $_REQUEST[ "email" ];

foreach ( $values as $k ) {
    $value = $_REQUEST[ $k ];
    $op = "INSERT INTO ".$event_tools_db_prefix."eventtools_user_request_entry (`email`, `key`, `value`) VALUES ('".$email."','".$k."','".$value."');";
    // echo $op."<br/>";
    mysql_query($op);
    // echo " r=".mysql_insert_id()." <br/>"; 
}

// now we try to save a reqular format request
$reqs = array();

// run the requests
$reqs = check_for_value($values, "on", $reqs);
$reqs = check_for_value($values, "1", $reqs);
$reqs = check_for_value($values, "2", $reqs);
$reqs = check_for_value($values, "3", $reqs);
$reqs = check_for_value($values, "4", $reqs);
$reqs = check_for_value($values, "5", $reqs);
$reqs = check_for_value($values, "6", $reqs);

// so reqs now is an ordered list of requests

// create the transfer list
$layoutindex = array();
$layoutindex["v_adams"] = 7;
$layoutindex["v_bowdidge"] = 28;
$layoutindex["v_burgessdias"] = 8;
$layoutindex["v_calcentral"] = 9;
$layoutindex["v_fortin"] = 10;
$layoutindex["v_kaufman"] = 11;
$layoutindex["v_mcgee"] = 12;
$layoutindex["v_marzeni"] = 13;
$layoutindex["v_neumann"] = 14;
$layoutindex["v_parksbo"] = 29;
$layoutindex["v_parkswm"] = 15;
$layoutindex["v_paul"] = 16;
$layoutindex["v_providenza"] = 17;
$layoutindex["v_radkey"] = 18;
$layoutindex["v_schnur"] = 19;
$layoutindex["v_svl"] = 20;
$layoutindex["v_vail"] = 21;
$layoutindex["v_weiss"] = 22;
$layoutindex["v_hayeszach"] = 23;
$layoutindex["v_houston"] = 24;
$layoutindex["v_merrin"] = 25;
$layoutindex["v_williams"] = 26;

// convert res to numbers
$reqs_num = array();
foreach ( $reqs as $k ) {
    $reqs_num[] = $layoutindex[$k];
}

foreach ( $reqs_num as $k ) {
    print " ".$k." ";
}

?>

