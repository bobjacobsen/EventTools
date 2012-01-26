<?php
// this is PHP to log
// the contents of the $_REQUEST array called out by
// the values array.

function check_for_value($values, $check, $reqs)
{
    // look through values array, checking the layout items for a match with a specific value
    print "<br/>check ";
    foreach ( $values as $k ) {
        if (substr($k, 0, 2) === "v_") { // is a layout
            $value = $_REQUEST[ $k ];
            if ($value == $check) { // got it, add layout to requests
                $reqs[] = $value;
                //print " (hit on ".$value.") ";
            }
        }
    }
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
check_for_value($values, "on", $reqs);
check_for_value($values, "1", $reqs);
check_for_value($values, "2", $reqs);
check_for_value($values, "3", $reqs);
check_for_value($values, "4", $reqs);
check_for_value($values, "5", $reqs);
check_for_value($values, "6", $reqs);

print $reqs;

?>

