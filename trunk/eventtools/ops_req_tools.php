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
    //echo $query;
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo $num;
    
    while ($i < $num) {
        $j = 0;
        echo "<tr>\n";
        $row = mysql_fetch_assoc($result);

        echo '<tr><td><select name=v_'.$row[ops_id].'>';
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

// create form selection boxes
// for options
// 
function create_option_entries() {
    global $event_tools_op_session_opt1_name, $event_tools_op_session_opt1_long_name;
    global $event_tools_op_session_opt2_name, $event_tools_op_session_opt2_long_name;
    global $event_tools_op_session_opt3_name, $event_tools_op_session_opt3_long_name;
    global $event_tools_op_session_opt4_name, $event_tools_op_session_opt4_long_name;
    global $event_tools_op_session_opt5_name, $event_tools_op_session_opt5_long_name;
    global $event_tools_op_session_opt6_name, $event_tools_op_session_opt6_long_name;
    global $event_tools_op_session_opt7_name, $event_tools_op_session_opt7_long_name;
    global $event_tools_op_session_opt8_name, $event_tools_op_session_opt8_long_name;
    
    create_option_entry($event_tools_op_session_opt1_name, $event_tools_op_session_opt1_long_name, 'opt1');
    create_option_entry($event_tools_op_session_opt2_name, $event_tools_op_session_opt2_long_name, 'opt2');
    create_option_entry($event_tools_op_session_opt3_name, $event_tools_op_session_opt3_long_name, 'opt3');
    create_option_entry($event_tools_op_session_opt4_name, $event_tools_op_session_opt4_long_name, 'opt4');
    create_option_entry($event_tools_op_session_opt5_name, $event_tools_op_session_opt5_long_name, 'opt5');
    create_option_entry($event_tools_op_session_opt6_name, $event_tools_op_session_opt6_long_name, 'opt6');
    create_option_entry($event_tools_op_session_opt7_name, $event_tools_op_session_opt7_long_name, 'opt7');
    create_option_entry($event_tools_op_session_opt8_name, $event_tools_op_session_opt8_long_name, 'opt8');
}
function create_option_entry($short, $long, $var) {
    if ($short == NONE) return;
    if ($short == '') return;
    echo '<tr><td colspan=2><input type="checkbox" name="'.$var.'">&nbsp;'."\n";
    echo $long;
    echo "</td></tr>\n";
}

?>

