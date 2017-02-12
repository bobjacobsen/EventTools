<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Tours by Layout and Time</title>
    

</head>
<body>
<h1>X2011West Tours by Layout and Time</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$dates = array(
    "2011-07-01 10:00", "2011-07-01 14:00", "2011-07-01 23:00",
    "2011-07-02 10:00", "2011-07-02 14:00", "2011-07-02 23:00",
    "2011-07-03 10:00", "2011-07-03 14:00", "2011-07-03 23:00",
    "2011-07-04 10:00", "2011-07-04 14:00", "2011-07-04 23:00",
    "2011-07-05 10:00", "2011-07-05 14:00", "2011-07-05 23:00",
    "2011-07-06 10:00", "2011-07-06 14:00", "2011-07-06 23:00",
    "2011-07-07 10:00", "2011-07-07 14:00", "2011-07-07 23:00",
    "2011-07-08 10:00", "2011-07-08 14:00", "2011-07-08 23:00",
    "2011-07-09 10:00", "2011-07-09 14:00", "2011-07-09 23:00");
$k = 0;

while ($k < count($dates)) { 
    echo "<h2 class=\"et-lt-clinics-h2\">".$dates[$k]."</h2>\n";
    
    $query="
        select *
        from ".$event_tools_db_prefix."eventtools_clinics
        where start_date like '".$dates[$k]."%'
        order by clinic_location, start_date
        ;
    ";

    $result=mysql_query($query);
    $k++;
    
    
    $i = 0;
    $num = mysql_numrows($result);
    $lastmajorkey = "";
    
    $starttimes = array("08:00:00", "09:30:00", "11:00:00", "13:00:00", "14:30:00", "16:00:00", "17:00:00", "19:00:00", "20:30:00", "22:00:00");
    
    
    echo "<table border=\"1\" class=\"et-lt-clinics-table\">\n";
    
    $j = 0;
    echo "<tr class=\"et-lt-clinics-tr-head\"><th class=\"et-lt-clinics-tr-head-th-loc\">Location</th>";
    while ($j < count($starttimes)) { echo "<th class=\"et-lt-clinics-tr-head-th-time\">".$starttimes[$j++]."</th>"; }
    echo "</tr>\n";
    
    // loop over locations, using SQL query order
    while ($i < $num) {
    
        // search for new location
        if ($lastmajorkey != mysql_result($result,$i,"clinic_location")) {
            $lastmajorkey = mysql_result($result,$i,"clinic_location");
            echo "<tr class=\"et-lt-clinics-tr-loc\">\n";
            echo "  <th class=\"et-lt-clinics-tr-loc-th-loc\">".htmlspecialchars(mysql_result($result,$i,"clinic_location"))."</th>\n";
            $lasti = $i;
            
            $j = 0;
            // loop over times, creating a row
            while ($j < count($starttimes)) {        
                if ($starttimes[$j] == substr(mysql_result($result,$i,"start_date"), -8)) {
                    $name = htmlspecialchars(mysql_result($result,$i,"name"));
                    $i++;
                } else {
                    $name = "";
                }
                $j++;
                echo "  <td class=\"et-lt-clinics-tr-loc-td-clinic\"><span class=\"et-lt-clinics-tr-loc-span-clinic\">".$name."</span></td>\n";
                if ($i >= $num) break 2;
    
            }
            
            echo "</tr>\n";
            if ($lasti == $i) {
                echo "unexpected count ".$i." at end, didnt match times?";
                $i++;
            }
        } else {
            echo "didnt match as expected; duplicate time?";
            $i++;
        }
    }
    
    // done, clean up
    
    echo "</tr>\n";
    echo "</table>\n";

}

mysql_close();    

?>
</body>
</html>
