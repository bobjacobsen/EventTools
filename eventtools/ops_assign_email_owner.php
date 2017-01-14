<?php

// send emails to layout owners

require_once('access.php');
require_once('utilities.php');
require_once('options_utilities.php');

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
    // --------------------------- format the raw page -------------------------------
    echo "This is the page for emailing to the owners. The list of people assigned to their layout will be appended to the email.<p/>";
    echo "Please fill in the form and press 'start'. All fields are required. Multiple email addresses can be specified, separated with a comma. Put just dollar sign '$' in 'test' to send for real, otherwise where you want test emails sent.";
    echo '<form method="get" action="ops_assign_email_owner.php">
        Cycle Name: <input  name="cy"><br>
        From email address: <input  name="from" value="'.$event_tools_registrar_email_address.'"> (Best if this is on the same domain as the web site)<br>
        Reply-to email address: <input  name="reply" value="'.$event_tools_registrar_email_address.'"> (This is who will get return messages)<br>
        Bcc email address(es): <input  name="bcc" value="'.$event_tools_registrar_email_address.'"> (These people get copies when mail is sent)<br>
        (Test) To email address(es): <input  name="testto" value="'.$event_tools_registrar_email_address.'"><br>
        Message content:<br>
        <textarea  name="content" rows="20" cols="70">'.$default_text.'</textarea><br>
        <button type="submit">Start</button>
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

    $query="
        SELECT *
            FROM  
            ".$event_tools_db_prefix."eventtools_opsession_name
            ".$where." ORDER BY show_name
            ;
        ";

    $result=mysql_query($query);
    $num=mysql_numrows($result);
    $i=0;
    
    echo '<h3>Operators</h3><p>If you select a specific session below, only that owner will get email</p><select name="session" id="session">';
    echo '<option value="(ALL)">(ALL)</option>';
    while ($i < $num) {
        echo '<option value="'.mysql_result($result,$i,"ops_id").'">'.mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date").' '.mysql_result($result,$i,"presenting_time").'</option>';
        $i++;
    }
    echo '</select></form>';
    //echo $query;
    return;
    // ------------------------------------------------------------------------------------------------------------------
} else {
    $cycle = $args["cy"];
    $from = $args["from"];
    $reply = $args["reply"];
    $bcc = $args["bcc"];
    $testto = $args["testto"];
}

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

echo "Emailing from cycle ".$cycle."<p>";

if (!($args["session"]==NONE || $args["session"]=="" || $args["session"]=="(ALL)")) {
    $where = "AND ops_id = '".$args["session"]."' ";
} else {
    $where = "";
}

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments 
    LEFT JOIN ".$event_tools_db_prefix."eventtools_layouts
    ON layout_id = ops_layout_id
    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession_req
    USING ( opsreq_person_email )
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ".$where."
    AND show_name != ''
    AND status = '1'
    ORDER BY show_name, start_date
    ";

//echo $query;
$result=mysql_query($query);
$num=mysql_numrows($result);

$i=0;
$lastmajorkey = mysql_result($result,$i,"show_name").mysql_result($result,$i,"start_date");

$part1 = $args["content"]."\n\n";

$sessions = "";
$first = TRUE;

// get the list of extras
$queryExtras="
    SELECT *
        FROM ( 
        ".$event_tools_db_prefix."eventtools_customer_options
        ) 
        ORDER BY customer_option_order 
        ;
    ";
//echo $queryExtras;
$resultExtras=mysql_query($queryExtras);
$numExtras= mysql_numrows($resultExtras);

// loop over attendee rows, breaking when next is a different majorkey (name and date)
while ($i < $num) {
    // accumulate the individual attendee information
    $sessions = $sessions.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname")."\n";
    $sessions = $sessions.'  Email: '.mysql_result($result,$i,"opsreq_person_email");
        
    // accumulate all the customer options that have gotten a Y answer as CSV string
    $options = "  Options Selected: ";
    $optQuery = options_select_statement()." WHERE ( opsreq_person_email = '".mysql_result($result,$i,"opsreq_person_email")."' ) ;";
    echo $optQuery;
    $resultOptions = mysql_query($optQuery);
    $numOptions = mysql_numrows($resultOptions);
    if ($numOptions > 1) echo "Didn't expect more than one match";
    $j = 0;
    $more = False;
    while ($j < $numExtras) {
        if ( mysql_result($resultOptions,0,"value".$j) == 'Y' && mysql_result($resultExtras,$j,"customer_option_session_report_name")!='') { // expecting just 1
            if ($more) $options = $options.", ";
            $more = True;
            $options = $options." ".mysql_result($resultExtras,$j,"customer_option_session_report_name");
        }
        $j++;
    }
    $options = $options."\n";

    $sessions = $sessions.' Phone: '.mysql_result($resultOptions,0,"customers_telephone");
    $sessions = $sessions.' Cell: '.mysql_result($resultOptions,0,"customers_cellphone");

    if ($options != "") $sessions = $sessions."\n".$options;
    $sessions = $sessions."\n";
    
    if ( ($i == $num-1) || ($lastmajorkey != mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date")) ) {
        if ($i < $num-1) {
            $lastmajorkey = mysql_result($result,$i+1,"show_name").mysql_result($result,$i+1,"start_date");
        }
    
        if ($testto=='$')
            $to = mysql_result($result,$i,"layout_owner_email");
        else
            $to = $testto;
        
        echo "Sending to ".mysql_result($result,$i,"layout_owner_email").' '.mysql_result($result,$i,"show_name").'('.$to.')<p>';
        
        $subject = "Operating Session Assignments";
        
        $headers = "from: ".$from."\nreply-to: ".$reply."\nbcc: ".$bcc;
                
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

?>

