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

if (! ($args["cy"]) ) {
    // --------------------------- format the raw page -------------------------------
    echo "This is the page for emailing to the operators. Their specific layout assignments will be appended to the email.<p/>";
    echo "Please fill in the form and press 'start'. All fields are required. Multiple email addresses can be specified, separated with a comma. Put just dollar sign '$' in 'test' to send for real, otherwise where you want test emails sent.";
    echo '<form method="get" action="ops_assign_email_operator.php">
        Cycle Name: <input  name="cy" size="32"><br>
        From email address: <input  name="from" value="'.$event_tools_registrar_email_address.'"><br>
        Bcc email address(es): <input  name="bcc" value="'.$event_tools_registrar_email_address.'"><br>
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
            
        $subject = $event_tools_event_name." Operating Session Assignments";
        
        $headers = "from: ".$from."\nbcc: ".$bcc;
                
        $body = $part1.$sessions."\n\n\n";
        
        mail($to,$subject,$body,$headers);
        //echo '<hr>'.$body.'<hr>';
        
        $sessions = "";
    }
    
    $i++;
}

mysql_close();    

echo "</clinics>\n";
?>

