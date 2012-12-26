<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');

parse_str($_SERVER["QUERY_STRING"], $args);
if($args["layout"]) {
    $layout = $args["layout"];
} else {
    $layout ="";
}

function issueAddress($result,$i){
    echo mysql_result($result,$i,"layout_street_address");
    echo ',';
    echo mysql_result($result,$i,"layout_city");
    echo '+';
    echo mysql_result($result,$i,"layout_state");
    echo '+';
    echo mysql_result($result,$i,"layout_postcode");
}

echo '<head>';
echo '	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '    <title>'.$event_tools_event_name.' Map To '.$layout.'</title>';

echo '    <link href="tours.css" rel="stylesheet" type="text/css" />';

echo '</head>';
echo '<body>';
echo '<h2>'.$event_tools_event_name.' Map To '.$layout.'</h2>';
echo '<a href="index.php">Back to main page</a>';
echo '<p>';

echo '<form method="get" action="map_one_layout.php">';
echo '    <button type="submit">Map</button>Layout Name:';
echo '    <input name="layout" type="text" size="20" maxlength="20" value="'.$layout.'" />';
echo '</form>';

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
        FROM ".$event_tools_db_prefix."eventtools_layouts
        WHERE layout_name LIKE \"%".$layout."%\";
    ";
echo $query;
$result=mysql_query($query);

global $event_tools_central_addr;
global $event_tools_central_city;
global $event_tools_central_state;
global $event_tools_central_postcode;

mysql_close();    

// show directions
$i=0;
$num=mysql_numrows($result);

// to 1st layout

echo "\n";

echo '<p>From Convention Hotel<br>'.
    'To '.
    mysql_result($result,0,"layout_street_address").", ".
    mysql_result($result,0,"layout_city")." ".
    mysql_result($result,0,"layout_state")." ".
    mysql_result($result,0,"layout_postcode")." (".
    mysql_result($result,0,"layout_owner_firstname")." ".
    mysql_result($result,0,"layout_owner_lastname")." ".
    mysql_result($result,0,"layout_name").")".
    ':<br/>';
echo '<a href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';
echo $event_tools_central_addr.','.$event_tools_central_city.'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;
echo '&amp;daddr=';
issueAddress($result,0);
echo '&amp;hl=en&amp;&amp;mra=ls&amp;g=';
issueAddress($result,0);
echo '&amp;ie=UTF8&amp;" style="color:#0000FF;text-align:left" target="_blank">View Larger Map</a>';

echo '<table><tr><td>';
echo '<iframe width="800" height="600" frameborder="1" scrolling="no" marginheight="10" marginwidth="10" src="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';

echo $event_tools_central_addr.','.$event_tools_central_city.'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;

echo '&amp;daddr=';

issueAddress($result,0);

echo '&amp;t=h&amp;output=embed">';
echo '</iframe>';

echo '</td></tr></table>';


// back to convention center
echo "<hr>\n";

echo '<p>From '.
    mysql_result($result,0,"layout_street_address").", ".
    mysql_result($result,0,"layout_city")." ".
    mysql_result($result,0,"layout_state")." ".
    mysql_result($result,0,"layout_postcode")." (".
    mysql_result($result,0,"layout_owner_firstname")." ".
    mysql_result($result,0,"layout_owner_lastname")." ".
    mysql_result($result,0,"layout_name").") ".
    '<br/>To Convention Hotel'.
    ':<br/>';
echo '<a href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';
issueAddress($result,$num-1);
echo '&amp;daddr=';
echo $event_tools_central_addr.','.$event_tools_central_city.'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;
echo mysql_result($result,$num-1,"layout_postcode");
echo '&amp;hl=en&amp;&amp;mra=ls&amp;g=';
echo $event_tools_central_addr.','.$event_tools_central_city.'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;
echo '&amp;ie=UTF8&amp;" style="color:#0000FF;text-align:left" target="_blank">View Larger Map</a>';

echo '<table><tr><td>';
echo '<iframe width="800" height="600" frameborder="1" scrolling="no" marginheight="10" marginwidth="10" src="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';

issueAddress($result,0);

echo '&amp;daddr=';

echo $event_tools_central_addr.','.$event_tools_central_city.'+'.$event_tools_central_state.'+'.$event_tools_central_postcode;

echo '&amp;t=h&amp;output=embed">';
echo '</iframe>';

echo '</td></tr></table>';
?>

</body>
</html>
