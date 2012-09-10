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

// do an insert of the user
$user = "REPLACE INTO ".$event_tools_db_prefix."customers (`customers_email_address`, `customers_firstname`, `customers_lastname`, `customers_telephone`, `customers_cellphone`, `customers_create_date`) VALUES "
    ."('".$email."','".$_REQUEST["fname"]."','".$_REQUEST["lname"]."','".$_REQUEST["phone"]."','".$_REQUEST["cell"]."',now());";
//print '[ '.$user.' ] ';
mysql_query($user);

// do an insert of the address block
$findu = "SELECT * FROM ".$event_tools_db_prefix."customers (`customers_id`) WHERE customers_email_address = '".$email.";";
print '[ '.$findu.' ] ';
mysql_query($findu);
print "<p>found ".mysql_result($reqs,$i,"customers_id")."</p>";

$address = "REPLACE INTO ".$event_tools_db_prefix."address_book (`customers_id`, `entry_city`, `entry_state`) VALUES "
    ."('".$id."','".$_REQUEST["city"]."','".$_REQUEST["state"]."';";
//print '[ '.$address.' ] ';
//mysql_query($address);

?>

