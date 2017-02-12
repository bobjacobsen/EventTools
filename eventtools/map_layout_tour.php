<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');

parse_str($_SERVER["QUERY_STRING"], $args);
if($args["tour"]) {
    $tour = $args["tour"];
} else {
    $tour ="L201";
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
echo '    <title>X2011West Layout Tour '.$tour.' Map</title>';

echo '    <link href="tours.css" rel="stylesheet" type="text/css" />';

echo '</head>';
echo '<body>';
echo '<h2>X2011West Layout Tour '.$tour.' Map</h2>';
echo '<a href="index.php">Back to main page</a>';
echo '<p>';

echo '<form method="get" action="map_layout_tour.php">';
echo '    <button type="submit">Map tour:</button>Number:';
echo '    <input name="tour" type="text" size="6" maxlength="5" value="'.$tour.'" />';
echo '</form>';

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
        FROM ".$event_tools_db_prefix."eventtools_layout_tour_with_layouts
        WHERE number = \"".$tour."\"
        ORDER BY layout_tour_link_order;
    ";
//echo $query;
$result=mysql_query($query);

// build map without convention center
echo '<table border="1"><tr><td>';
echo '<img src="http://maps.google.com/maps/api/staticmap?size=400x400';
$i=0;
$num=mysql_numrows($result);
while ($i < $num) {
    echo '&markers=color:blue|label:'.($i+1).'|';
    echo mysql_result($result,$i,"layout_street_address");
    echo ',';
    echo mysql_result($result,$i,"layout_city");
    echo ',';
    echo mysql_result($result,$i,"layout_state");
    echo mysql_result($result,$i,"layout_postcode");

    $i++;
}
echo '&sensor=false">';
echo '</td>';

$c_addr = "1230+J+Street";
$c_city = "Sacramento";
$c_state = "CA";
$c_postcode = "95814";

// build map with convention center
echo '<td>';
echo '<img src="http://maps.google.com/maps/api/staticmap?size=400x400';
$i=0;
$num=mysql_numrows($result);
echo '&markers=color:red|label:C|'.$c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;
while ($i < $num) {
    echo '&markers=color:blue|label:'.($i+1).'|';
    issueAddress($result,$i);

    $i++;
}
echo '&sensor=false">';
echo '</td></tr></table>';

mysql_close();    

// show listing
$where="number = \"".$tour."\"";
format_all_layout_tours_as_8table($where);

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
echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;
echo '&amp;daddr=';
issueAddress($result,0);
echo '&amp;hl=en&amp;&amp;mra=ls&amp;g=';
issueAddress($result,0);
echo '&amp;ie=UTF8&amp;" style="color:#0000FF;text-align:left" target="_blank">View Larger Map</a>';

echo '<table><tr><td>';
echo '<iframe width="800" height="600" frameborder="1" scrolling="no" marginheight="10" marginwidth="10" src="http://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=';

echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;

echo '&amp;hl=en&amp;mra=ls&amp;g=';

issueAddress($result,0);

echo '&amp;daddr=';

issueAddress($result,0);

echo '&amp;ie=UTF8&amp;hl=en&amp;&amp;mra=ls&amp;">';
echo '</iframe>';

echo '</td></tr></table>';

// layout to layout

while ($i < ($num-1)) {
    echo "<hr>\n";

    echo 'From '.
        mysql_result($result,$i,"layout_street_address").", ".
        mysql_result($result,$i,"layout_city")." ".
        mysql_result($result,$i,"layout_state")." ".
        mysql_result($result,$i,"layout_postcode")." (".
        mysql_result($result,$i,"layout_owner_firstname")." ".
        mysql_result($result,$i,"layout_owner_lastname")." ".
        mysql_result($result,$i,"layout_name").") ".
        '<br/>To '.
        mysql_result($result,$i+1,"layout_street_address").", ".
        mysql_result($result,$i+1,"layout_city")." ".
        mysql_result($result,$i+1,"layout_state")." ".
        mysql_result($result,$i+1,"layout_postcode")." (".
        mysql_result($result,$i+1,"layout_owner_firstname")." ".
        mysql_result($result,$i+1,"layout_owner_lastname")." ".
        mysql_result($result,$i+1,"layout_name").") ".
        ':<br/>';

    echo '<a href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';
    issueAddress($result,$i);
    echo '&amp;daddr=';
    issueAddress($result,$i+1);
    echo '&amp;hl=en&amp;&amp;mra=ls&amp;g=';
    issueAddress($result,$i+1);
    echo '&amp;ie=UTF8&amp;" style="color:#0000FF;text-align:left" target="_blank">View Larger Map</a>';

    echo '<table><tr><td>';
    echo '<iframe width="800" height="600" frameborder="1" scrolling="no" marginheight="10" marginwidth="10" src="http://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=';

    issueAddress($result,$i);

    echo '&amp;hl=en&amp;mra=ls&amp;g=';

    issueAddress($result,$i+1);

    echo '&amp;daddr=';
    
    issueAddress($result,$i+1);

    echo '&amp;ie=UTF8&amp;hl=en&amp;&amp;mra=ls&amp;">';
    echo '</iframe>';
    
    echo '</td></tr></table>';
    $i++;
}

// back to convention center
echo "<hr>\n";

echo '<p>From '.
    mysql_result($result,$num-1,"layout_street_address").", ".
    mysql_result($result,$num-1,"layout_city")." ".
    mysql_result($result,$num-1,"layout_state")." ".
    mysql_result($result,$num-1,"layout_postcode")." (".
    mysql_result($result,$num-1,"layout_owner_firstname")." ".
    mysql_result($result,$num-1,"layout_owner_lastname")." ".
    mysql_result($result,$num-1,"layout_name").") ".
    '<br/>To Convention Hotel'.
    ':<br/>';
echo '<a href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=';
issueAddress($result,$num-1);
echo '&amp;daddr=';
echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;
echo mysql_result($result,$num-1,"layout_postcode");
echo '&amp;hl=en&amp;&amp;mra=ls&amp;g=';
echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;
echo '&amp;ie=UTF8&amp;" style="color:#0000FF;text-align:left" target="_blank">View Larger Map</a>';

echo '<table><tr><td>';
echo '<iframe width="800" height="600" frameborder="1" scrolling="no" marginheight="10" marginwidth="10" src="http://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=';

issueAddress($result,$num-1);

echo '&amp;hl=en&amp;mra=ls&amp;g=';

echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;

echo '&amp;daddr=';

echo $c_addr.','.$c_city.'+'.$c_state.'+'.$c_postcode;

echo '&amp;ie=UTF8&amp;hl=en&amp;&amp;mra=ls&amp;">';
echo '</iframe>';

echo '</td></tr></table>';

?>

</body>
</html>
