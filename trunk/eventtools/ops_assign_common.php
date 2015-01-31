<?php

define('STATUS_RELEASED',  "0");  // no assignment
define('STATUS_ASSIGNED',  "1");  // normal assignment
define('STATUS_DISABLED', "-2"); // assignment manually forbidden
define('STATUS_CONFLICT', "-3"); // has time conflict with other assignment
define('STATUS_FULL',     "-4"); // session is known to be full

function session_title_from_query($ops_id) {
    global $event_tools_db_prefix;

    $query = "SELECT show_name, start_date
                FROM ".$event_tools_db_prefix."eventtools_opsession_name
                WHERE ops_id = '".$ops_id."'
                ;";
    $result=mysql_query($query);
    return mysql_result($result,0,"show_name").' '.mysql_result($result,0,"start_date");
}

function setspan($stat) {
    if ($stat == STATUS_RELEASED) echo '<span class="released" title="Released">';
    if ($stat == STATUS_ASSIGNED) echo '<span class="assigned" title="Assigned">';
    if ($stat == STATUS_DISABLED) echo '<span class="disabled" title="Disabled">';
    if ($stat == STATUS_CONFLICT) echo '<span class="conflict" title="Conflict">';
    if ($stat == STATUS_FULL)     echo '<span class="filled"   title="Filled">';
}

function insert_multiple_ops_request_structure($cycle, $reqs) {
    $num = mysql_numrows($reqs);
    
    // create the group entries, one for each
    for ($i = 0; $i < $num; $i ++) {
        // skip if no selections
        if (   (mysql_result($reqs,$i,"opsreq_pri1") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri2") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri3") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri4") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri5") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri6") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri7") <= "1")
            && (mysql_result($reqs,$i,"opsreq_pri8") <= "1") 
            && (mysql_result($reqs,$i,"opsreq_pri9") <= "1") 
            && (mysql_result($reqs,$i,"opsreq_pri10") <= "1") 
            && (mysql_result($reqs,$i,"opsreq_pri11") <= "1") 
            && (mysql_result($reqs,$i,"opsreq_pri12") <= "1") 
            ) {
                echo "Skip ".mysql_result($reqs,$i,"opsreq_person_email")."<br/>";
                continue;
        }
        
        // now insert
        insert_one_ops_request_structure($cycle, $reqs, $i);
    }
}

function insert_one_ops_request_structure($cycle, $reqs, $i) {
    // $cycle - cycle name string
    // $reqs, $i - includes the ops_req row that's being handled
    global $event_tools_db_prefix;

    $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_group
                (opsreq_group_cycle_name)
                VALUES 
                ('".$cycle."')
                ;";
    $result=mysql_query($query);
    $id = mysql_insert_id();   // inserted ID
    
    // add a link entry pointing to this group
    $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
                (opsreq_group_id, opsreq_id)
                VALUES 
                ('".$id."','".mysql_result($reqs,$i,"opsreq_id")."')
                ;";
    $result=mysql_query($query);
    $id = mysql_insert_id();   // inserted ID
    
    // add the request items
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','1','".mysql_result($reqs,$i,"opsreq_pri1")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','2','".mysql_result($reqs,$i,"opsreq_pri2")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','3','".mysql_result($reqs,$i,"opsreq_pri3")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','4','".mysql_result($reqs,$i,"opsreq_pri4")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','5','".mysql_result($reqs,$i,"opsreq_pri5")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','6','".mysql_result($reqs,$i,"opsreq_pri6")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','7','".mysql_result($reqs,$i,"opsreq_pri7")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','8','".mysql_result($reqs,$i,"opsreq_pri8")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','9','".mysql_result($reqs,$i,"opsreq_pri9")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','10','".mysql_result($reqs,$i,"opsreq_pri10")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','11','".mysql_result($reqs,$i,"opsreq_pri11")."');");
    mysql_query("INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status (opsreq_group_req_link_id, req_num, ops_id) VALUES ('".$id."','12','".mysql_result($reqs,$i,"opsreq_pri12")."');");

}

function copy_to_new_cycle($from, $cycle) {
    global $event_tools_db_prefix;

    // first, delete the "to" from $cycle
    // mysql_query("DELETE FROM ".$event_tools_db_prefix."eventtools_opsreq_group WHERE opsreq_group_cycle_name = '".$cycle."'");
    
    // first, check if it exists, and fail if it does
    $query="
        SELECT opsreq_group_id
        FROM ".$event_tools_db_prefix."eventtools_opsreq_group
        WHERE opsreq_group_cycle_name = '".$cycle."'
        ;
    ";
    $result=mysql_query($query);
    if ( $result && mysql_numrows($result) > 0) {
        echo "<h2>Can't copy to cycle that already exists, showing existing ".$cycle." cycle contents</h2>";
        return;
    }
    
    // then copy the groups and links
    // (this is slow, with lots of queries, but its only done occasionally)
    $query="
        SELECT opsreq_group_id
        FROM ".$event_tools_db_prefix."eventtools_opsreq_group
        WHERE opsreq_group_cycle_name = '".$from."'
        ;
    ";
    $result=mysql_query($query);
    $num = mysql_numrows($result);

    for ($i = 0; $i < $num; $i++) {
        // loop over each group, first making a new group
        $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_group
                    (opsreq_group_cycle_name)
                    VALUES 
                    ('".$cycle."')
                    ;";
        $groups=mysql_query($query);
        $id = mysql_insert_id();
        // then retrieving and copying the links
        $query="
            SELECT *
            FROM ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
            WHERE opsreq_group_id = ".mysql_result($result,$i,"opsreq_group_id")."
            ;
        ";
        $resL=mysql_query($query);
        $numL = mysql_numrows($resL);
        
        // now create those links
        for ($j = 0; $j < $numL; $j++) {
            $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
                        (opsreq_group_id, opsreq_id)
                        VALUES 
                        ('".$id."','".mysql_result($resL,$j,"opsreq_id")."')
                        ;";
            $resQ=mysql_query($query);
            $link = mysql_insert_id();   // inserted link ID
            
            // for each of those, retrieve and copy the status row
            $query="
                SELECT *
                FROM ".$event_tools_db_prefix."eventtools_opsreq_req_status
                WHERE opsreq_group_req_link_id = ".mysql_result($resL,$j,"opsreq_group_req_link_id")."
                ;
            ";
            $resS=mysql_query($query);
            $numS = mysql_numrows($resS);
            for ($k = 0; $k < $numS; $k++) {
                $query = "INSERT INTO ".$event_tools_db_prefix."eventtools_opsreq_req_status
                            (opsreq_group_req_link_id, req_num, ops_id, status)
                            VALUES 
                            ('".$link."','".mysql_result($resS,$k,"req_num")."','".mysql_result($resS,$k,"ops_id")."','".mysql_result($resS,$k,"status")."')
                            ;";
                $resQ=mysql_query($query);
            }
        }
    }
}    

?>
