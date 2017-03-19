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

function clean_text($text) 
{
    return mysql_real_escape_string(stripslashes($text));
}

global $values;

global $opts, $event_tools_db_prefix;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$email = clean_text($_REQUEST[ "email" ]);

// see if the user already exists
$findu = "SELECT customers_id FROM ".$event_tools_db_prefix."customers WHERE customers_email_address = '".$email."';";
$reqs = mysql_query($findu);

if (mysql_num_rows($reqs) == 0) {
 
    // no, do an insert of the user
    $user = "REPLACE INTO ".$event_tools_db_prefix."customers (`customers_email_address`, `customers_firstname`, `customers_lastname`, `customers_telephone`, `customers_cellphone`, `customers_create_date`, `customers_x2011_emerg_contact_name`, `customers_x2011_emerg_contact_phone`) VALUES "
        ."('".$email."','".clean_text($_REQUEST["fname"])."','".clean_text($_REQUEST["lname"])."','".clean_text($_REQUEST["phone"])."','".clean_text($_REQUEST["cell"])."',now(),'".clean_text($_REQUEST["econtact"])."','".clean_text($_REQUEST["ephone"])."');";
    //print '[ '.$user.' ] ';
    mysql_query($user);

} else {

    // yes, do an update of the user
    $user = "UPDATE ".$event_tools_db_prefix."customers SET customers_firstname ='".clean_text($_REQUEST["fname"])."', customers_lastname ='".clean_text($_REQUEST["lname"])."', customers_telephone ='".clean_text($_REQUEST["phone"])."', customers_cellphone ='".clean_text($_REQUEST["cell"])."', customers_x2011_emerg_contact_name ='".clean_text($_REQUEST["econtact"])."', customers_x2011_emerg_contact_phone ='".clean_text($_REQUEST["ephone"])."' WHERE customers_email_address = '".$email."'";
    //print '[ '.$user.' ] ';
    mysql_query($user);

}

// do an insert of the address block
// Right now, it just updates the earliest if there already is one
$findu = "SELECT ".$event_tools_db_prefix."customers.customers_id, address_book_id FROM (".$event_tools_db_prefix."customers LEFT JOIN ".$event_tools_db_prefix."address_book ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."address_book.customers_id ) WHERE customers_email_address = '".$email."';";
//print '[ '.$findu.' ] ';
$reqs = mysql_query($findu);
//print "<p>found ".mysql_result($reqs,0,"customers_id")."</p>";

$address = "REPLACE INTO ".$event_tools_db_prefix."address_book (`customers_id`, `address_book_id`, `entry_street_address`, `entry_city`, `entry_state`, `entry_postcode`) VALUES "
    ."('".mysql_result($reqs,0,"customers_id")."','".mysql_result($reqs,0,"address_book_id")."','".clean_text($_REQUEST["street"])."','".clean_text($_REQUEST["city"])."','".clean_text($_REQUEST["state"])."','".clean_text($_REQUEST["zip"])."');";
//print '[ '.$address.' ] ';
$repl = mysql_query($address);

$id = mysql_insert_id();
if ($id == NONE || $id =='' || $id == 0) {
    // need to retrieve the address row by query
    $finda = $findu = "SELECT address_book_id FROM ".$event_tools_db_prefix."address_book WHERE customers_id = '".mysql_result($reqs,0,"customers_id")."';";
    $repa = mysql_query($finda);
    //print '['.$finda.']';
    //print '{'.$repa.'}';
    //print "Update to address ID ".mysql_result($repa,0,"address_book_id");
    $id = mysql_result($repa,0,"address_book_id");
}

// and make sure prefix_customers.customers_default_address_id is correct
$customer = "UPDATE ".$event_tools_db_prefix."customers SET customers_default_address_id =".$id." WHERE customers_id = ".mysql_result($reqs,0,"customers_id")." ;";
//print '[ '.$customer.' ] ';
$repl = mysql_query($customer);

?>

