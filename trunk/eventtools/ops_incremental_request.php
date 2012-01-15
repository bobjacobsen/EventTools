<?php
// this is PHP to log
// the contents of the $_REQUEST array called out by
// the values array.

echo "doing request";
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
?>

