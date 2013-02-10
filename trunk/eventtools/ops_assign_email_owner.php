<?php

// send emails to layout owners

require_once('access.php');
require_once('utilities.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// for cycle defined
$default_text = '
First, let me thank you again for agreeing to host op session(s).  Without your participation this event, especially of this magnitude, would just not happen. 
  
In this email you will find the names and email addresses of the convention attendees assigned to your op session(s).  Please feel free to contact them and send them any operating documents or information you may want them to review before they arrive on your doorstep.  Please be aware that there are likely to be some last minute changes in assignments due to the real world - we will let you know of any changes that occur prior the the convention itself.
  
Some of your sessions are not full at this time.  Based on the experience at prior ops events we expect to fill all operating slots.  If there appears to be problem with filling a particular session we will be in touch with you individually to discuss our options.

Please get in touch with us if you have any questions,

The '.$event_tools_event_name.' Committee

----------------------
';

if (! ($args["cy"]) ) {
    echo "This is the page for emailing to the owners.<p/>";
    echo "Please fill in the form and press 'start'. All fields are required. Multiple email addresses can be specified, separated with a comma. Put just dollar sign '$' in 'test' to send for real, otherwise where you want test emails sent.";
    echo '<form method="get" action="ops_assign_email_owner.php">
        Cycle Name: <input  name="cy"><br>
        From email address: <input  name="from" value="'.$event_tools_registrar_email_address.'"><br>
        Bcc email address(es): <input  name="bcc" value="'.$event_tools_registrar_email_address.'"><br>
        (Test) To email address(es): <input  name="testto" value="'.$event_tools_registrar_email_address.'"><br>
        Message content:<br>
        <textarea  name="content" rows="20" cols="70">'.$default_text.'</textarea><br>
        <button type="submit">Start</button>
        </form>
    ';
    
    // display existing cycles & number of assignments)
    echo '<h3>Existing cycles</h3><table><tr><th>Cycle Name</th><th>N Assigned</th></tr>';
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    $query="
        SELECT opsreq_group_cycle_name, SUM(status) 
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE status = 1
        GROUP BY opsreq_group_cycle_name
        ORDER BY  opsreq_group_cycle_name
        ;
    ";
    $result=mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        echo '<tr><td><a href="?cy='.mysql_result($result,$i,"opsreq_group_cycle_name").'">'.mysql_result($result,$i,"opsreq_group_cycle_name").'</a></td><td>'.mysql_result($result,$i,1).'</td></tr>';
    }
    echo '</table>';
    return;
} else {
    $cycle = $args["cy"];
    $from = $args["from"];
    $bcc = $args["bcc"];
    $testto = $args["testto"];
}

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

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

$part1 = $args["content"];

$sessions = "";
$first = TRUE;

while ($i < $num) {
    $sessions = $sessions.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname")."\n".mysql_result($result,$i,"opsreq_person_email")."\n\n";

    if ( ($i == $num-1) || ($lastmajorkey != mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date")) ) {
        if ($i < $num-1) {
            $lastmajorkey = mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date");
        }
    
        if ($testto=='$')
            $to = mysql_result($result,$i,"layout_owner_email");
        else
            $to = $testto;
        
        echo "sending to ".mysql_result($result,$i,"layout_owner_email").' '.mysql_result($result,$i,"show_name").'('.$to.')<p>';
        
        $subject = "Operating Session Assignments";
        
        $headers = "from: ".$from."\nbcc: ".$bcc;
                
        $body = $part1."\n\n".
                daydatetime_from_long_format(mysql_result($result,$i,"start_date"))." session attendees:\n\n".
                $sessions."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        echo '<hr>'.$body.'<hr>';
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

