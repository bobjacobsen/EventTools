<?php

// send emails to operators

require_once('access.php');
require_once('utilities.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// for cycle defined
$default_text = '
Here are your operating session assignments for '.$event_tools_event_name.'.  Please look them over and if there are any problems please reply to this email to let me know and we will try to resolve it.

As always, there is a possibility that cancellations will force us to make adjustments to session assignments.  If we need to change one of your assignments, I will let you know.

Our Registrar will be sending you additional information regarding other activities.

The '.$event_tools_event_name.' Committee

----------------------
';

global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// see if there is text defined
$query="
    SELECT user_text_value
    FROM ".$event_tools_db_prefix."eventtools_user_text
    WHERE user_text_key = 'ops_assign_email_operator_sample_text'
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);

if ($num == 0) {
    // first run, store default - we do it this way because it's very long
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_user_text (user_text_key, user_text_value) VALUES ('ops_assign_email_operator_sample_text', '".str_replace("'", "''", $default_text)."');");
} else if ($num == 1) {
    $default_text = mysql_result($result,0,0);
} else {
    echo "Error, found ".$num." texts and expected 1";
}


if ( ($args["savetext"]) ) {
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_user_text (user_text_key, user_text_value) VALUES ('ops_assign_email_operator_sample_text', '".str_replace("'", "''", $args["content"])."') ON DUPLICATE KEY UPDATE user_text_value = '".str_replace("'", "''", $args["content"])."';");
    $default_text = $args["content"];
    echo "<b>Email text saved</b><br>";    
} 

if ( ($args["send"]) && (! $args["cy"])  ) {
    echo "<b>You have to specify a cycle to send email</b><br>";    
}

if ( (! $args["send"]) || (! $args["cy"])  ) {
    // --------------------------- format the raw page -------------------------------
    echo "This is the page for emailing to the operators. Their specific layout assignments will be appended to the email.<p/>";
    echo "Please fill in the form and press 'start'. All fields are required. Multiple email addresses can be specified, separated with a comma. Put just dollar sign '$' in 'test' to send for real, otherwise where you want test emails sent.";
    echo '<form method="get" action="ops_assign_email_operator.php">
        Cycle Name: <input  name="cy" size="32"><br>
        From email address: <input  name="from" value="'.$event_tools_registrar_email_address.'"> (Best if this is on the same domain as the web site)<br>
        Reply-to email address: <input  name="reply" value="'.$event_tools_registrar_email_address.'"> (This is who will get return messages)<br>
        Bcc email address(es): <input  name="bcc" value="'.$event_tools_registrar_email_address.'"> (These people get copies when mail is sent)<br>
        (Test) To email address(es): <input  name="testto" value="'.$event_tools_registrar_email_address.'"><br>
        Subject: <input  name="subject" value="'.$event_tools_event_name.' Operating Session Assignments" size="72"><br>
        Message content:<br>
        <textarea  name="content" rows="20" cols="70">'.$default_text.'</textarea><br>
        <input name="noassignments" id="noassignments" type="checkbox">Just send email text, omit including individual assignments</input> (but you still need a cycle name to identify operators)<br>
        <button type="submit" name="savetext" value="savetext">Save Text (doesn'."'".'t send mail)</button><br>
        <button type="submit" name="send" value="send">Send Emails (doesn'."'".'t save text changes)</button>
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
            FROM ( 
            ".$event_tools_db_prefix."eventtools_opsession_req LEFT JOIN ".$event_tools_db_prefix."customers
            ON ".$event_tools_db_prefix."eventtools_opsession_req.opsreq_person_email = ".$event_tools_db_prefix."customers.customers_email_address
            ) 
            ".$where." ORDER BY customers_lastname
            ;
        ";

    $result=mysql_query($query);
    $num=mysql_numrows($result);
    $i=0;
    
    echo '<h3>Operators</h3><p>If you select a specific operator below, only that person will get email</p><select name="operator" id="operator">';
    echo '<option value="(ALL)">(ALL)</option>';
    while ($i < $num) {
        echo '<option value="'.mysql_result($result,$i,"opsreq_person_email").'">'.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").'</option>';
        $i++;
    }
    echo '</select></form>';

    // ------------------------------------------------------------------------------------------------------------------
    return;
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

if (!($args["operator"]==NONE || $args["operator"]=="" || $args["operator"]=="(ALL)")) {
    $where = "AND opsreq_person_email = '".$args["operator"]."' ";
} else {
    $where = "";
}

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments 
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ".$where."
    AND show_name != ''
    AND status = '1'
    ORDER BY opsreq_group_cycle_name
    ";

$result=mysql_query($query);
$num=mysql_numrows($result);
//echo $query;
//echo '['.$num.']';
$i=0;
$lastmajorkey = mysql_result($result,$i,"opsreq_person_email");

$part1 = $args["content"]."\n\n";

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
            
        $subject = $args["subject"];
        
        $headers = "from: ".$from."\nreply-to: ".$reply."\nbcc: ".$bcc;
                
        $body = $part1;
        if ($args["noassignments"] != "on") $body = $body.$sessions;
        $body = $body."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        //echo '<hr>'.$body.'<hr>';
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

