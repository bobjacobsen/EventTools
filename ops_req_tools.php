<?php
// Service routines for creating a request page for
// op sessions.
include_once('mysql2i.class.php'); // migration step

// Key parts:
//    regular requests
//    special requests, which if wanted end up as lowest priority
//    other questions and reservations

// create form selection boxes
// for operating sessions
// selections result in things like 23=14  where the first is the op session number
//   and the second is the priority
function create_request_entries($max_pri, $where=NULL, $order=NULL ) {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;
    global $event_tools_ops_session_assign_by_layout;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    if ($order==NULL) $order = "show_name, start_date";

    if ($where != NULL) $where = "WHERE ".$where." ";
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
    $last_show_name = "";

    while ($i < $num) {
        $row = mysql_fetch_assoc($result);
        // if by layout, don't show duplicates
        if ( (! $event_tools_ops_session_assign_by_layout ) || ($row["show_name"] != $last_show_name)) {

            echo '<tr><td><select name=v_'.$row[ops_id].'>';
            $k = 1;
            echo '<option value=""></option>';
            while ($k <= $max_pri) {
                echo '<option value="'.$k.'">'.$k.'</option>';
                $k++;
            }
            echo "</select>\n";
            echo $row["show_name"];
            if (! $event_tools_ops_session_assign_by_layout ) {
                echo " ".$row["start_date"];
            }
            echo "</td></tr>\n";
        }
        $last_show_name = $row["show_name"];
        $i++;
    }
}

// create form selection boxes
// for options
//
function create_option_entries() {
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_customer_options
        ORDER BY customer_option_order
        ;
    ";
    //echo $query;
    $result=mysql_query($query);

    $i=0;
    $num=mysql_numrows($result);
    while ($i < $num) {
        create_option_entry(mysql_result($result,$i,"customer_option_id"),mysql_result($result,$i,"customer_option_long_name"));
        $i++;
    }
}
function create_option_entry($id, $long) {
    echo '<tr><td colspan=2><input type="checkbox" value="Y" name="option_id_'.$id.'">&nbsp;'."\n";
    echo $long;
    echo "</td></tr>\n";
}

?>

