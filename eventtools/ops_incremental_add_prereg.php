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

// see if the user already exists
$findu = "SELECT customers_id FROM ".$event_tools_db_prefix."customers WHERE customers_email_address = '".$email."';";
$reqs = mysql_query($findu);

if (mysql_num_rows($reqs) == 0) {
 
    // no, do an insert of the user
    $user = "REPLACE INTO ".$event_tools_db_prefix."customers (`customers_email_address`, `customers_firstname`, `customers_lastname`, `customers_telephone`, `customers_cellphone`, `customers_create_date`) VALUES "
        ."('".$email."','".$_REQUEST["fname"]."','".$_REQUEST["lname"]."','".$_REQUEST["phone"]."','".$_REQUEST["cell"]."',now());";
    //print '[ '.$user.' ] ';
    mysql_query($user);

} else {

    // yes, do an update of the user
    $user = "UPDATE ".$event_tools_db_prefix."customers SET customers_firstname ='".$_REQUEST["fname"]."', customers_lastname ='".$_REQUEST["lname"]."', customers_telephone ='".$_REQUEST["phone"]."', customers_cellphone ='".$_REQUEST["cell"]."' WHERE customers_email_address = '".$email."'";
    //print '[ '.$user.' ] ';
    mysql_query($user);

}

// do an insert of the address block
// following should instead properly handle prefix_customers.customers_default_address_id instead of just forcing one
// Right now, it just updates the earliest if there already is one
$findu = "SELECT ".$event_tools_db_prefix."customers.customers_id, address_book_id FROM (".$event_tools_db_prefix."customers LEFT JOIN ".$event_tools_db_prefix."address_book ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."address_book.customers_id ) WHERE customers_email_address = '".$email."';";
//print '[ '.$findu.' ] ';
$reqs = mysql_query($findu);
//print "<p>found ".mysql_result($reqs,0,"customers_id")."</p>";

$address = "REPLACE INTO ".$event_tools_db_prefix."address_book (`customers_id`, `address_book_id`, `entry_street_address`, `entry_city`, `entry_state`, `entry_postcode`) VALUES "
    ."('".mysql_result($reqs,0,"customers_id")."','".mysql_result($reqs,0,"address_book_id")."','".$_REQUEST["street"]."','".$_REQUEST["city"]."','".$_REQUEST["state"]."','".$_REQUEST["zip"]."');";
//print '[ '.$address.' ] ';
mysql_query($address);

?>

