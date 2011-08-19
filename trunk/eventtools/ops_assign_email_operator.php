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
Here are your OPSIG operating session assignments for X2011W.  Please look them over and if there are any problems please reply to this email to let me know and we will try to resolve it.

I’m happy to tell you that 88% of folks got their first choice session; 99% of those who asked for more than 3 sessions got two of their top three choices.

A fair number of you indicated you would attend other sessions if ones you had asked for were not available.  There were about a dozen people who we have assigned sessions to based on this.  Again, if there is a problem with these assignments please reply to this email to let me know.

There are about 20% of the operating slots still open.  About 20 of these slots are during the Advanced Section sessions, including Marenzi, Osborn, Parks, Paul and Silicon Valley Lines.  If you can make one of these sessions please let me know. 

Jim Providenza
X2011W OPSIG Coordinator
------------------------


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
        
        $subject = "X2011 West Operating Session Assignments";
        
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

