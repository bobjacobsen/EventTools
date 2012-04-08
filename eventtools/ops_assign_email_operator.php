<?php

// send emails to operators

require_once('access.php');
require_once('utilities.php');

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$cycle = "110511_3jpz";
echo "Emailing from cycle ".$cycle."<p>";

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments 
    WHERE opsreq_group_cycle_name = '".$cycle."'
    AND show_name != ''
    AND status = '1'
    ORDER BY opsreq_group_cycle_name
    ";

$result=mysql_query($query);
$num=mysql_numrows($result);

$i=0;
$lastmajorkey = mysql_result($result,$i,"opsreq_person_email");

$part1 = "
Here are your operating session assignments for ".$event_tools_event_name.".  Please look them over and if there are any problems please reply to this email to let me know and we will try to resolve it.

Our Registrar, Larry Altbaum will be sending you additional information regarding other activities including the Saturday dinner.

Jim Providenza
ProRail 2012 Scheduler
----------------------

";

$sessions = "";

while ($i < $num) {
    $sessions = $sessions.mysql_result($result,$i,"show_name")."\n".daydatetime_from_long_format(mysql_result($result,$i,"start_date"))."\n\n";
    
    if ( ($i == $num-1) || ($lastmajorkey != mysql_result($result,$i+1,"opsreq_person_email")) ) {
        if ($i < $num-1) {
            $lastmajorkey = mysql_result($result,$i+1,"opsreq_person_email");
        }
        
        echo "sending to ".mysql_result($result,$i,"opsreq_person_email").'<p>';
    
        $to = mysql_result($result,$i,"opsreq_person_email");
        //$to = "rgj1927@pacbell.net";
        
        $subject = $event_tools_event_name." Operating Session Assignments";
        
        $headers = "from: rrjim@aol.com\nbcc: rrjim@aol.com, rgj1927@pacbell.net";
        //$headers = "from: jacobsen@berkeley.edu";
                
        $body = $part1.$sessions."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

