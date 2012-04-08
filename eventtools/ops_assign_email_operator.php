<?php

// send emails to operators

require_once('access.php');
require_once('utilities.php');

// for cycle defined
if (! ($args["cy"]) ) {
    echo "This is the page for emailing to the operators.<p/>";
    echo "Please fill in the form and press 'start'. All fields are required. Multiple email addresses can be specified, separated with a comma. Put a dollar sign '$' in 'test' to send for real.";
    echo '<form method="get" action="ops_assign_email_operator.php">
        Cycle Name: <input  name="cy"><br>
        From email address: <input  name="from"><br>
        Bcc email address(es): <input  name="bcc"><br>
        (Test) To email address(es): <input  name="testto"><br>
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
        echo '<tr><td>'.mysql_result($result,$i,"opsreq_group_cycle_name").'</td><td>'.mysql_result($result,$i,1).'</td></tr>';
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
        
        if ($testto=='$')
            $to = mysql_result($result,$i,"opsreq_person_email");
        else
            $to = $testto;

        echo "sending to ".mysql_result($result,$i,"opsreq_person_email").'('.$to.')<p>';
            
        $subject = $event_tools_event_name." Operating Session Assignments";
        
        $headers = "from: ".$from."\nbcc: ".$bcc;
                
        $body = $part1.$sessions."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

