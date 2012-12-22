<?php
// Service routines for creating a request page for 
// op sessions.

// Key parts:
//    regular requests
//    special requests, which if wanted end up as lowest priority
//    other questions and reservations

// create form selection boxes
// for operating sessions
// selections result in things like 23=14  where the first is the op session number
//   and the second is the priority
function create_request_entries($max_pri, $where=NONE, $order=NONE ) {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;

    echo "debug";
    echo $opts['hn'];
    echo $opts['un'];
    echo $opts['pw'];
    
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($order==NONE) $order = "show_name";

    if ($where != NONE) $where = "WHERE ".$where." ";
    else $where = " ";
    
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_opsession_name
        ".$where."
        ORDER BY ".$order."
        ;
    ";
    echo $query;
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo $num;
    
    while ($i < $num) {
        $j = 0;
        echo "<tr>\n";
        $row = mysql_fetch_assoc($result);

        echo '<tr><td><select name=os_'.$row[ops_id].'>';
        $k = 1;
        echo '<option value=""></option>';
        while ($k <= $max_pri) {
            echo '<option value="'.$k.'">'.$k.'</option>';
            $k++;
        }
        echo "</select>\n";
        echo $row["show_name"];
        echo "</td></tr>\n";
        
        $i++;
    }    
}

?>

