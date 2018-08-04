<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('google_staticmap_key.php');

// present a map of all (enabled) layout locations
//
// minstatus argument is minimum status to display

parse_str($_SERVER["QUERY_STRING"], $args);
if($args["minstatus"] !="") {
    $minstatus = $args["minstatus"];
} else {
    $minstatus = 60;
}

function issueAddress($result,$i){
    echo mysql_result($result,$i,"layout_street_address");
    echo ', ';
    echo mysql_result($result,$i,"layout_city");
    echo ', ';
    echo mysql_result($result,$i,"layout_state");
    echo ' ';
    echo mysql_result($result,$i,"layout_postcode");
}

echo '<head>';
echo '	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '    <title>'.$event_tools_event_name.' Layouts Map</title>';

echo '    <link href="tours.css" rel="stylesheet" type="text/css" />';

echo '</head>';
echo '<body>';
echo '<h2>'.$event_tools_event_name.' Layouts Map</h2>';
echo '<a href="index.php">Back to main page</a>';
echo '<p>';

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
        FROM ".$event_tools_db_prefix."eventtools_layouts
        WHERE layout_status_code >= \"".$minstatus."\"
        ORDER BY layout_name;
    ";
// echo $query;
$result=mysql_query($query);

global $event_tools_central_addr;
global $event_tools_central_city;
global $event_tools_central_state;
global $event_tools_central_postcode;;

$height = 600;
$width = 600;

// build map with convention center
echo '<img src="http://maps.google.com/maps/api/staticmap?size='.$height.'x'.$width.' ';
$i=0;
$num=mysql_numrows($result);
echo '&markers=color:red|label:C|'.$event_tools_central_addr.','.$event_tools_central_city
            .'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;
while ($i < $num) {
    if (mysql_result($result,$i,"layout_street_address")!="" && mysql_result($result,$i,"layout_city") !="" && mysql_result($result,$i,"layout_state") != "" && mysql_result($result,$i,"layout_postcode") != "") {
        echo '&markers=color:blue|';
        issueAddress($result,$i);
    }
    $i++;
}
echo '&key='.$google_staticmap_key.'">';

mysql_close();    

?>

</body>
</html>
