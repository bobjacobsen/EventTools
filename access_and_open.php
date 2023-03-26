<?php

// A generalization of access that also opens the database.

// To be used in two places:

// As a direct replacement of
//    global $opts, $event_tools_db_prefix;
//    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
//    @mysql_select_db($opts['db']) or die( "Unable to select database");

// At the top of phpMyEdit files to get the database open

// This is specifically needed when doing INSERT, REPLACE or UPDATE operations,
// as it sets a mote that handles migration of '' as a decimal value.
// Display pages don't need it as such.

include_once('mysql2i.class.php'); // migration step

require_once('access.php');

global $opts, $event_tools_db_prefix;
// Open the database.
// Stored handle to $opts[’dbh’] so that open version is used by phpMyEdit
$opts[’dbh’] = mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// Set a local mode to handle migration of '' as a decimal value
$query="SET @@LOCAL.sql_mode = ''";
mysql_query($query);

// Debug for errors
if (mysql_errno() != 0) print "<p>Error setting mode: ".mysql_errno() . ": " . mysql_error() . "</p>";

?>
