<?php

// set the mode
$query="SET @@LOCAL.sql_mode = ''";
mysql_query($query);

//if (mysql_errno() != 0) print "<p>Error setting mode: ".mysql_errno() . ": " . mysql_error() . "</p>";

?>