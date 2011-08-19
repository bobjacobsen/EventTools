<?php

// send emails to layout owners

require_once('access.php');
require_once('utilities.php');

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$cycle = "110511_3jpz";
echo "Emailing from cycle ".$cycle."<p>";

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments 
    LEFT JOIN ".$event_tools_db_prefix."eventtools_layouts
    ON layout_id = ops_layout_id 
    WHERE opsreq_group_cycle_name = '".$cycle."'
    AND show_name != ''
    AND status = '1'
    ORDER BY show_name, start_date
    ";

//echo $query;
$result=mysql_query($query);
$num=mysql_numrows($result);

$i=0;
$lastmajorkey = mysql_result($result,$i,"show_name").mysql_result($result,$i,"start_date");

$part1 = "
First, let me thank you again for agreeing to host op session(s) for the OPSIG for the NMRA national convention.  Without your participation this event, especially of this magnitude, would just not happen. 
 
In this email you will find the names and email addresses of the convention attendees assigned to your op session(s).  Please feel free to contact them and send them any operating documents or information you may want them to review before they arrive on your doorstep.  Please be aware that there are likely to be some last minute changes in assignments due to the real world - I will let you know of any changes that occur prior the the convention itself.
 
Some of your sessions are not full at this time.  Based on the experience at prior national conventions we expect to fill all operating slots before or during the convention.  If there appears to be problem with filling a particular session I will be in touch with you individually to discuss our options.
 
Please get in touch with me if you have any questions,
 
Jim Providenza
X2011W OPSIG Coordinator
------------------------

";

$sessions = "";
$first = TRUE;

while ($i < $num) {
    $sessions = $sessions.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname")."\n".mysql_result($result,$i,"opsreq_person_email")."\n\n";

    if ( ($i == $num-1) || ($lastmajorkey != mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date")) ) {
        if ($i < $num-1) {
            $lastmajorkey = mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date");
        }
        echo "sending to ".mysql_result($result,$i,"layout_owner_email").' '.mysql_result($result,$i,"show_name").'<p>';
    
        $to = mysql_result($result,$i,"layout_owner_email");
        //$to = "rgj1927@pacbell.net";
        
        $subject = "X2011 West Operating Session Assignments";
        
        $headers = "from: rrjim@aol.com\nbcc: rrjim@aol.com, rgj1927@pacbell.net";
        //$headers = "from: jacobsen@berkeley.edu";
                
        $body = $part1."\n\n".
                daydatetime_from_long_format(mysql_result($result,$i,"start_date"))." session attendees:\n\n".
                $sessions."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

