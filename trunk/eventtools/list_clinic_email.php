<?php

// provide a list of clinician email addresses;
// does nothing else useful!

echo '<?xml version="1.0"?>'."\n";
echo '<clinics>';

require_once('access.php');
require_once('utilities.php');
require_once('listing.php');
require_once('parsers.php');

// Over-ride globals for on visibility here
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

$where = parse_clinic_query();
$order = parse_order();

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_clinics 
    GROUP BY clinic_presenter_email
    ORDER BY clinic_presenter_email
    ;";


$result=mysql_query($query);

$num=mysql_numrows($result);

$i=0;
$lastmajorkey= "";

while ($i < $num) {
    echo mysql_result($result,$i,"clinic_presenter_email").'<br/>';
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

