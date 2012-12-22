<?php
// this is PHP to log
// the contents of the $_REQUEST array called out by
// the values array.

// Specific values expected in $_REQUEST, e.g. $_REQUEST[ "email" ]
//  email (primary key)
//  fname
//  lname
//  phone
//  cell
//  street
//  city
//  state
//  zip
//    opt1 - opt8
//    v_###   a request for a session (### is the session, value is priority)

function check_for_value($values, $check, $reqs)
{
    // look through values array, checking the layout items for a match with a specific value
    // print "<br/>check ";
    foreach ( array_keys($_REQUEST) as $k ) {
        if (substr($k, 0, 2) === "v_") { // is a layout
            $value = $_REQUEST[ $k ];
            if ($value == $check) { // got it, add layout to requests
                $reqs[] = substr($k,2);
                print " (hit on ".$value." w ".substr($k,2).") ";
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

// save op session request
$reqs = array();

// run the requests - need one for each priority that is given on page
$reqs = check_for_value($values, "1", $reqs);
$reqs = check_for_value($values, "2", $reqs);
$reqs = check_for_value($values, "3", $reqs);
$reqs = check_for_value($values, "4", $reqs);
$reqs = check_for_value($values, "5", $reqs);
$reqs = check_for_value($values, "6", $reqs);
$reqs = check_for_value($values, "7", $reqs);
$reqs = check_for_value($values, "8", $reqs);
$reqs = check_for_value($values, "9", $reqs);
$reqs = check_for_value($values, "10", $reqs);
$reqs = check_for_value($values, "11", $reqs);
$reqs = check_for_value($values, "12", $reqs);

// so reqs now is an ordered list of requests

// do an insert of the request
$op = "REPLACE INTO ".$event_tools_db_prefix."eventtools_opsession_req (`opsreq_person_email`, `opsreq_pri1`, `opsreq_pri2`, `opsreq_pri3`, `opsreq_pri4`, `opsreq_pri5`, `opsreq_pri6`, `opsreq_pri7`, `opsreq_pri8`, `opsreq_pri9`, `opsreq_pri10`, `opsreq_pri11`, `opsreq_pri12`, `opsreq_opt1`, `opsreq_opt2`, `opsreq_opt3`, `opsreq_opt4`, `opsreq_opt5`, `opsreq_opt6`, `opsreq_opt7`, `opsreq_opt8`, `opsreq_comment`) VALUES "
    ."('".$email."','".$reqs[0]."','".$reqs[1]."','".$reqs[2]."','".$reqs[3]."','".$reqs[4]."','".$reqs[5]."','".$reqs[6]."','".$reqs[7]."','".$reqs[8]."','".$reqs[9]."','".$reqs[10]."','".$reqs[11]."','".$_REQUEST['opt1']."','".$_REQUEST['opt2']."','".$_REQUEST['opt3']."','".$_REQUEST['opt4']."','".$_REQUEST['opt5']."','".$_REQUEST['opt6']."','".$_REQUEST['opt7']."','".$_REQUEST['opt8']."','".$_REQUEST[ "comments" ]."');";
print 'Request [ '.$op.' ] <p>';
mysql_query($op);

// do an insert of the user
$user = "REPLACE INTO ".$event_tools_db_prefix."customers (`customers_email_address`, `customers_firstname`, `customers_lastname`, `customers_telephone`, `customers_cellphone`) VALUES "
    ."('".$email."','".$_REQUEST["fname"]."','".$_REQUEST["lname"]."','".$_REQUEST["phone"]."','".$_REQUEST["cell"]."');";
print 'User [ '.$user.' ] <p>';
mysql_query($user);

?>

