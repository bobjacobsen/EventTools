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
                //print " (hit on ".$value." w ".substr($k,2).") ";
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
$op = "REPLACE INTO ".$event_tools_db_prefix."eventtools_opsession_req (`opsreq_person_email`, `opsreq_pri1`, `opsreq_pri2`, `opsreq_pri3`, `opsreq_pri4`, `opsreq_pri5`, `opsreq_pri6`, `opsreq_pri7`, `opsreq_pri8`, `opsreq_pri9`, `opsreq_pri10`, `opsreq_pri11`, `opsreq_pri12`, `opsreq_comment`) VALUES "
    ."('".$email."','".$reqs[0]."','".$reqs[1]."','".$reqs[2]."','".$reqs[3]."','".$reqs[4]."','".$reqs[5]."','".$reqs[6]."','".$reqs[7]."','".$reqs[8]."','".$reqs[9]."','".$reqs[10]."','".$reqs[11]."','".clean_text($_REQUEST[ "comments" ])."');";
//print 'Request [ '.$op.' ] <p>';
mysql_query($op);

// see if the user already exists
$findu = "SELECT customers_id FROM ".$event_tools_db_prefix."customers WHERE customers_email_address = '".$email."';";
$reqs = mysql_query($findu);

if (mysql_num_rows($reqs) == 0) {
 
    // no, do an insert of the user
    $user = "INSERT INTO ".$event_tools_db_prefix."customers (`customers_email_address`, `customers_firstname`, `customers_lastname`, `customers_telephone`, `customers_cellphone`, `customers_create_date`, `customers_x2011_emerg_contact_name`, `customers_x2011_emerg_contact_phone`) VALUES "
        ."('".$email."','".clean_text($_REQUEST["fname"])."','".clean_text($_REQUEST["lname"])."','".clean_text($_REQUEST["phone"])."','".clean_text($_REQUEST["cell"])."',now(),'".clean_text($_REQUEST["econtact"])."','".clean_text($_REQUEST["ephone"])."');";
    //print 'Insert of user: [ '.$user.' ] ';
    mysql_query($user);
    if (mysql_errno() != 0) print "<p>Error in INSERT customers: ".mysql_errno() . ": " . mysql_error() . "</p>";

} else {

    // yes, do an update of the user
    $user = "UPDATE ".$event_tools_db_prefix."customers SET customers_firstname ='".clean_text($_REQUEST["fname"])."', customers_lastname ='".clean_text($_REQUEST["lname"])."', customers_telephone ='".clean_text($_REQUEST["phone"])."', customers_cellphone ='".clean_text($_REQUEST["cell"])."', customers_x2011_emerg_contact_name ='".clean_text($_REQUEST["econtact"])."', customers_x2011_emerg_contact_phone ='".clean_text($_REQUEST["ephone"])."', customers_updated_date=now() WHERE customers_email_address = '".clean_text($email)."'";
    //print 'Replace of user: [ '.$user.' ] ';
    mysql_query($user);
    if (mysql_errno() != 0) print "<p>Error in UPDATE customers: ".mysql_errno() . ": " . mysql_error() . "</p>";

}

// do an insert of the address block
// Right now, it just updates the earliest if there already is one
$findu = "SELECT ".$event_tools_db_prefix."customers.customers_id, address_book_id FROM (".$event_tools_db_prefix."customers LEFT JOIN ".$event_tools_db_prefix."address_book ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."address_book.customers_id ) WHERE customers_email_address = '".$email."';";
//print '[ '.$findu.' ] ';
$reqs = mysql_query($findu);
if (mysql_errno() != 0) print "<p>Error in SELECT LEFT JOIN: ".mysql_errno() . ": " . mysql_error() . "</p>";
//print "<p>found ".mysql_num_rows($reqs)." rows ";
//if (mysql_num_rows($reqs)>0) print "with first: ".mysql_result($reqs,0,"customers_id");
//print "</p>";

$address = "REPLACE INTO ".$event_tools_db_prefix."address_book (`customers_id`, `address_book_id`, `entry_street_address`, `entry_city`, `entry_state`, `entry_postcode`) VALUES "
    ."('".mysql_result($reqs,0,"customers_id")."','".mysql_result($reqs,0,"address_book_id")."','".clean_text($_REQUEST["street"])."','".clean_text($_REQUEST["city"])."','".clean_text($_REQUEST["state"])."','".clean_text($_REQUEST["zip"])."');";
//print '[ '.$address.' ] ';
mysql_query($address);
if (mysql_errno() != 0) print "<p>Error in REPLACE address_book: ".mysql_errno() . ": " . mysql_error() . "</p>";

// and make sure prefix_customers.customers_default_address_id is correct
$customer = "UPDATE ".$event_tools_db_prefix."customers SET customers_default_address_id =".mysql_insert_id()." WHERE customers_id = ".mysql_result($reqs,0,"customers_id")." ;";
//print '[ '.$customer.' ] ';
$repl = mysql_query($customer);
if (mysql_errno() != 0) print "<p>Error in final UPDATE customers: ".mysql_errno() . ": " . mysql_error() . "</p>";

// handle the options 
// setting the value to Y or N for each checkbox
$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_customer_options
    ORDER BY customer_option_order
    ;
";
//echo $query;
$options=mysql_query($query);

$i=0;
$num=mysql_numrows($options);
while ($i < $num) {
    // see is this customer/option pair exists 
    $check = "SELECT customers_id FROM ".$event_tools_db_prefix."eventtools_customer_option_values WHERE customers_id = '".mysql_result($reqs,0,"customers_id")."' AND customer_option_id = '".mysql_result($options,$i,"customer_option_id")."';";
    $result=mysql_query($check);
    if (mysql_numrows($result) == 0) {
        $insert = "INSERT INTO ".$event_tools_db_prefix."eventtools_customer_option_values (`customers_id`, `customer_option_id`, `customer_option_value_value`, `customer_option_value_date`) VALUES "
        ."('".mysql_result($reqs,0,"customers_id")."','".mysql_result($options,$i,"customer_option_id")."','".clean_text($_REQUEST["option_id_".mysql_result($options,$i,"customer_option_id")])."',now());";
        mysql_query($insert);
        if (mysql_errno() != 0) print "<p>Error in INSERT of options: ".mysql_errno() . ": " . mysql_error() . "</p>" .$insert."<p>";
    } else {
        $update = "UPDATE ".$event_tools_db_prefix."eventtools_customer_option_values SET
                    customer_option_value_value = '".clean_text($_REQUEST["option_id_".mysql_result($options,$i,"customer_option_id")])."',
                    customer_option_value_date  = now()
                
                  WHERE
                    customers_id         = '".mysql_result($reqs,0,"customers_id")."' AND
                    customer_option_id   = '".mysql_result($options,$i,"customer_option_id")."'
            ";
        mysql_query($update);
        if (mysql_errno() != 0) print "<p>Error in UPDATE of options: ".mysql_errno() . ": " . mysql_error() . "</p>" .$update."<p>";
    }
    
    $i++;
}

?>

