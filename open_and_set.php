<?php
include_once('mysql2i.class.php'); // migration step

// Open the database and set a local mode to handle migration of '' as a decimal value
// stores to $opts[’dbh’] so that open version is used by phpMyEdit
global $opts, $event_tools_db_prefix;
$opts[’dbh’] = mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// set the mode
$query="SET @@LOCAL.sql_mode = ''";
mysql_query($query);

// Debug for errors
if (mysql_errno() != 0) print "<p>Error setting mode: ".mysql_errno() . ": " . mysql_error() . "</p>";

?>
