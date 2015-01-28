<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Groups</title>
    

</head>
<body>
<h1>Op Session Groups</h1>  
<a href="index.php">Back to main page</a><p/>

<?php

require_once('ops_assign_common.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" in the arguments
if (! ($args["cy"]) ) {
    echo "This is the starting page for op session assignments.<p/>";
    echo "It's also where you group attendees together when they want the same assignment.<p/>";
    echo "Please provide a cycle name and press start start. If the name exists, we'll load that, otherwise we'll create it.";
    echo '<form method="get" action="ops_assign_group.php">
        Cycle Name: <input  name="cy"></textarea>
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
}

// here, the cycle name exists, 
echo '<h2>Cycle: '.$cycle.'</h2>';

// Try to load it

global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_opsreq_group
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);
if ($num == 0) {
    // no rows found, load
    echo "(Creating ...)<p/>";
    
    // read the original request database
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_opsession_req
        ;
    ";
    $reqs=mysql_query($query);
    $num = mysql_numrows($reqs);
    
    // insert those
    insert_multiple_ops_request_structure($cycle, $reqs);   

}

echo '<form method="get" action="ops_assign_set.php?cy='.$cycle.'">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Continue to assignments</button>';
echo '</form>';

echo "<h2>Setting Groups</h2>";

echo "(Loading ...)<p/>";

// Are there any merge requests?

$merges = array_keys($args, "merge");
if (count($merges) > 0) {
    // yes, check for whether they agree 
    echo 'Starting to merge '.count($merges).' entries';
    echo '<br/>';
    // merges[] are group keys; see if the requests match up
    // there's always at least merges[0]
    $query = "SELECT * FROM (
                    ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
                    JOIN
                    ".$event_tools_db_prefix."eventtools_opsession_req
                    USING (opsreq_id)
            )                    
            WHERE opsreq_group_req_link_id = '".$merges[0]."'
            ;
    ";
    $matchstart=mysql_query($query);
    echo '<br/>Start with '.mysql_result($matchstart,0,"opsreq_person_email").'<br/>';
    
    for ($i = 1; $i < count($merges); $i ++) {
        $query = "SELECT * FROM (
                        ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
                        JOIN
                        ".$event_tools_db_prefix."eventtools_opsession_req
                        USING (opsreq_id)
                )                    
                WHERE opsreq_group_req_link_id = '".$merges[$i]."'
                ;
        ";
        $matchmid=mysql_query($query);
        $goodcheck = TRUE;
        
        echo 'Checking against '.mysql_result($matchmid,0,"opsreq_person_email").'<br/>';
        if (mysql_result($matchmid,0,"opsreq_pri1") != mysql_result($matchstart,0,"opsreq_pri1")) {
            echo "<b>Error: 1st requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri2") != mysql_result($matchstart,0,"opsreq_pri2")) {
            echo "<b>Error: 2nd requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri3") != mysql_result($matchstart,0,"opsreq_pri3")) {
            echo "<b>Error: 3rd requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri4") != mysql_result($matchstart,0,"opsreq_pri4")) {
            echo "<b>Error: 4th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri5") != mysql_result($matchstart,0,"opsreq_pri5")) {
            echo "<b>Error: 5th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri6") != mysql_result($matchstart,0,"opsreq_pri6")) {
            echo "<b>Error: 6th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri7") != mysql_result($matchstart,0,"opsreq_pri7")) {
            echo "<b>Error: 7th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri8") != mysql_result($matchstart,0,"opsreq_pri8")) {
            echo "<b>Error: 8th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri9") != mysql_result($matchstart,0,"opsreq_pri9")) {
            echo "<b>Error: 9th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri10") != mysql_result($matchstart,0,"opsreq_pri10")) {
            echo "<b>Error: 10th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri11") != mysql_result($matchstart,0,"opsreq_pri11")) {
            echo "<b>Error: 11th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
        if (mysql_result($matchmid,0,"opsreq_pri12") != mysql_result($matchstart,0,"opsreq_pri12")) {
            echo "<b>Error: 12th requests don't match</b><br/>\n";
            $goodcheck = FALSE;
        }
    }

    if ($goodcheck) {    
        // do the merge; keys are link numbers
        // create a new group
        $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_group
                    (opsreq_group_cycle_name)
                    VALUES 
                    ('".$cycle."')
                    ;";
        $groups=mysql_query($query);
        $id = mysql_insert_id();

        // update the links
        for ($i = 0; $i < count($merges); $i ++) {
            $query = "UPDATE ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
                        SET opsreq_group_id='".$id."'
                        WHERE opsreq_group_req_link_id = '".$merges[$i]."'
                        ;";
            mysql_query($query);
        }
        echo "<p>Merge complete<p>\n";
    } else {
        echo "<p><b>Skipping merge because requests don't match</b><p>\n";
    }
}

// Create a table form with a check-box for merging
echo 'Check boxes and click "Group" to combine requests, or click the Continue to Assignments Button above.<br/>';

echo '<form method="get" action="ops_assign_group.php?cy='.$cycle.'"><table border="1">'."\n";
echo '<tr><th></th><th></th><th>Name</th><th>Comment</th>'."\n";
// echo '<th>Debug G</th><th>Debug L</th></tr>'."\n";

$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_names
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ORDER BY opsreq_group_id
    ;
";

$result=mysql_query($query);
$num = mysql_numrows($result);

$rows = array();

for ($i = 0; $i < $num; ) {
    $rowstring = "";
    // check for a group
    for ($j = $i+1; $j < $num; $j++) {
        if (mysql_result($result,$j,"opsreq_group_id") != mysql_result($result,$i,"opsreq_group_id") ) break;
    }
    
    // build row
    $rowstring = $rowstring.'<tr><td>';
    for ($k = $i; $k < $j; $k++) {
        $rowstring = $rowstring.'<input type=CHECKBOX name="'.mysql_result($result,$k,"opsreq_group_req_link_id").'" value="merge">';
        if ($k != $j-1) $rowstring = $rowstring.'<hr/>';
    }
    $rowstring = $rowstring.'</td><td>';

    $rowstring = $rowstring.'XXXXXX';  // we'll replace that XXXXXXX with an ^ in some cases below

    $rowstring = $rowstring.'</td><td>';
    for ($k = $i; $k < $j; $k++) {
        $rowstring = $rowstring.mysql_result($result,$k,"customers_firstname").' '.mysql_result($result,$k,"customers_lastname").' '.mysql_result($result,$k,"opsreq_person_email");
        if ($k != $j-1) $rowstring = $rowstring.'<hr/>';
    }
    $rowstring = $rowstring.'</td>';
    
    // and then add the comment
    $rowstring = $rowstring.'<td>';
    for ($k = $i; $k < $j; $k++) {
        $rowstring = $rowstring.mysql_result($result,$k,"opsreq_comment");
        if ($k != $j-1) $rowstring = $rowstring.'<hr/>';
    }
    $rowstring = $rowstring.'</td>';
//     $rowstring = $rowstring.'<td>';
//     for ($k = $i; $k < $j; $k++) {
//         $rowstring = $rowstring.mysql_result($result,$k,"opsreq_group_id").'<br/>';
//     }
//     $rowstring = $rowstring.'</td><td>';
//     for ($k = $i; $k < $j; $k++) {
//         $rowstring = $rowstring.mysql_result($result,$k,"opsreq_group_req_link_id").'<br/>';
//     }
//     $rowstring = $rowstring.'</td>';
    
    $rowstring = $rowstring.'</tr>'."\n";
    
    // query to construct a key for this group
    
    $query="
        SELECT  *
        FROM (".$event_tools_db_prefix."eventtools_opsreq_group
            LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
            ON ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id = ".$event_tools_db_prefix."eventtools_opsreq_group_req_link.opsreq_group_id )
            LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_req_status
            ON ".$event_tools_db_prefix."eventtools_opsreq_group_req_link.opsreq_group_req_link_id = ".$event_tools_db_prefix."eventtools_opsreq_req_status.opsreq_group_req_link_id
        WHERE ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id = ".mysql_result($result,$i,"opsreq_group_id")."
        ;
    ";
    $sess=mysql_query($query);
    $n = min(mysql_numrows($sess),12);
    $key = "";
    for ($k = 0; $k < $n ; $k++) {
        $key=$key.mysql_result($sess,$k,"ops_id")." ";
    }
    $key = $key."/".mysql_result($result,$i,"opsreq_group_id");
    
    $rows[$key] = $rowstring;
    $i = $j;
}

// sort on keys and show rows
ksort($rows);
$lastkey = "";
foreach ($rows as $key => $val) {
    if ($lastkey == substr($key,0,strpos($key,'/')) ) {
        echo str_replace('XXXXXX','^',$val);
    } else {
        echo str_replace('XXXXXX','',$val);
    }
    $lastkey = substr($key,0,strpos($key,'/'));
}

echo '</table>';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Group</button></form>';

?>
</body>
</html>
