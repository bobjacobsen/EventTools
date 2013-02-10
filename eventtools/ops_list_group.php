<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Email List</title>
    

</head>
<body>
<h1>Op Session Email List</h1>  
<a href="index.php">Back to main page</a><p/>

<?php

require_once('ops_assign_common.php');

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" in the arguments
if (! ($args["cy"]) ) {
    echo "This is the starting page for printing email lists for op session assignments.<p/>";
    echo "Please provide a cycle name and press start start. (Your cycle must exist)";
    echo '<form method="get" action="ops_list_group.php">
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
    echo "That cycle doesn't exist.  Click 'Back' and try again.<p/>";
    return;
}


$query="
    SELECT DISTINCT opsreq_person_email
    FROM ".$event_tools_db_prefix."eventtools_ops_group_names
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ORDER BY opsreq_group_id
    ;
";


$result=mysql_query($query);
$num = mysql_numrows($result);

echo "<p>";

for ($i = 0; $i < $num; $i++) {
    echo mysql_result($result,$i,"opsreq_person_email")."<br>";
}

?>
</body>
</html>
