<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Op Session Set Assignments</title>
    <style type="text/css">
        span.released { background: #ffffff; }
        span.assigned { background: #b0b0ff; }
        span.conflict { background: #ffc0c0; }
        span.filled   { background: #c0ffc0; }
        span.disabled { background: #ff8080; }
        
        td.session { width: 600px; }
    </style>
</head>
<body>
<h1>X2011West Op Session Set Assignments</h1>  
<a href="index.php">Back to main page</a><p/>

<?php

require_once('ops_assign_common.php');

function setbuttons($cycle,$stat,$id,$tag) {
    echo '<table><tr>';
    if ( ($stat == STATUS_RELEASED) || ($stat == STATUS_DISABLED ) )
        echo '<td><form method="get" action="ops_assign_set.php#'.$tag.'"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.$id.'"><input type="submit" name="op" value="A"/></form></td>';
    if ( $stat != STATUS_DISABLED )
        echo '<td><form method="get" action="ops_assign_set.php#'.$tag.'"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.$id.'"><input type="submit" name="op" value="D"/></form></td>';
    if ( ($stat == STATUS_ASSIGNED) || ($stat == STATUS_DISABLED ) )
        echo '<td><form method="get" action="ops_assign_set.php#'.$tag.'"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.$id.'"><input type="submit" name="op" value="R"/></form></td>';
    if ( $stat == STATUS_CONFLICT) // send via force warning
        echo '<td><form method="get" action="ops_assign_warn_jam.php#'.$tag.'"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.$id.'">
              <input type="hidden" name="reason" value="Session time conflicts with another assigned session">
              <input type="submit" name="op" value="F"/></form></td>';
    if ( $stat == STATUS_FULL) // send via force warning
        echo '<td><form method="get" action="ops_assign_warn_jam.php#'.$tag.'"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.$id.'">
              <input type="hidden" name="reason" value="Session is already full">
              <input type="submit" name="op" value="F"/></form></td>';
    echo '</tr></table>';
}

function setfilled($email,$regnum) {
    global $reqnum_by_rqstr, $status_by_rqstr;
    for ($j = 1; $j < 9; $j++) {
        if ($reqnum_by_rqstr[$email][$j] == $regnum) {
            $status_by_rqstr[$email][$j] = STATUS_FULL;
            return;
        }
    }
    echo "Didn't find match for ".$email.'['.$regnum.'] while setting session full. ';
}

function setstatus($id, $status) {  // set status record $id to $status, then also rest of group
    global $event_tools_db_prefix, $cycle;
    $query = "UPDATE ".$event_tools_db_prefix."eventtools_opsreq_req_status
                SET status='".$status."'
                WHERE opsreq_req_status_id = '".$id."'
                ;";
    //echo '<p>'.$query.'<p>';
    mysql_query($query);
    
    // now locate rest of group
    $query="
        SELECT opsreq_group_id, show_name, start_date
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_group_cycle_name = '".$cycle."' AND opsreq_req_status_id = '".$id."'
        ;
    ";
    $result=mysql_query($query);
    $num = mysql_numrows($result);
    $group = mysql_result($result,0,"opsreq_group_id");
    $name = mysql_result($result,0,"show_name");
    $date = mysql_result($result,0,"start_date");
    // echo '<p>'.$name.' '.$date.'<p>';
    
    $query="
        SELECT opsreq_group_id, opsreq_req_status_id
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_group_cycle_name = '".$cycle."' AND opsreq_group_id = '".$group."'
            AND show_name = '".$name."' AND start_date = '".$date."'
        ;
    ";
    $result=mysql_query($query);
    $num = mysql_numrows($result);
    // echo "<p>Changing group of ".$num.'<p>';
    
    // change the statuses
    for ($i = 0; $i < $num; $i++) {
        $query = "UPDATE ".$event_tools_db_prefix."eventtools_opsreq_req_status
                    SET status='".$status."'
                    WHERE opsreq_req_status_id = '".mysql_result($result,$i,"opsreq_req_status_id")."'
                    ;";
        // echo '<p>'.$query.'<p>';
        mysql_query($query);
    }
}

function updatenavigation() {
    global $reqnum_by_rqstr, $reqname_by_rqstr, $strtdate_by_rqstr, $statusid_by_rqstr, $status_by_rqstr;
    global $rqstr_name, $rqstr_group, $rqstr_address, $rqstr_email, $rqstr_group_size, $rqstr_req_size, $rqstr_req_any;
    global $group_user_count;
    global $empty_slots_by_session;
    global $event_tools_db_prefix, $cycle;
    
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_group_cycle_name = '".$cycle."'
        ORDER BY customers_lastname, opsreq_person_email, req_num
        ;
    ";
    
    $result=mysql_query($query);
    $num = mysql_numrows($result);

    $reqnum_by_rqstr = array();  // array of req_num requests [1:8] by rqstr email
    $reqname_by_rqstr = array();  // array of req_name requests [1:8] by rqstr email
    $strtdate_by_rqstr = array();  // array of start_date requests [1:8] by rqstr email
    $statusid_by_rqstr = array();  // array of opsreq_req_status_id requests [1:8] by rqstr email
    $status_by_rqstr = array();  // array of opsreq_req_status_id requests [1:8] by rqstr email
    
    $rqstr_name = array();  // for presentation name by rqstr email
    $rqstr_group = array(); // for group number by rqstr email
    $rqstr_address = array(); // for address (city, state) by rqstr email
    $rqstr_group_size  = array(); // for group size by rqstr email
    $rqstr_req_size  = array(); // for number of requests by rqstr email
    $rqstr_req_any  = array(); // accept any sessions by rqstr email
    
    $rqstr_email = array(); // emails in order of adding
    
    for ($i = 0; $i < $num; ) {
    
        // build for one requestor
        $email = mysql_result($result,$i,"opsreq_person_email");
        $rqstr_email[] = $email;
        $rqstr_name[$email] = mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").'<br/>'.mysql_result($result,$i,"opsreq_person_email");
        $rqstr_group[$email] = mysql_result($result,$i,"opsreq_group_id");
        $rqstr_address[$email] = mysql_result($result,$i,"entry_city").', '.mysql_result($result,$i,"entry_state");
        $rqstr_group_size[$email] = $group_user_count[mysql_result($result,$i,"opsreq_group_id")];
        $rqstr_req_size[$email] = mysql_result($result,$i,"opsreq_number");
        $rqstr_req_any[$email] = mysql_result($result,$i,"opsreq_any");
        
        $names = array();
        $nums = array();
        $strtdate = array();
        $statusid = array();
        $status = array();
    
        for ($j = 1; $j<9; $j++) {
            $nums[$j] = mysql_result($result,$i,"req_num");
            $names[$j] = mysql_result($result,$i,"show_name");
    
            $date = mysql_result($result,$i,"start_date");
            $strtdate[$j] = $date;
    
            $statusid[$j] = mysql_result($result,$i,"opsreq_req_status_id");
            $status[$j] = mysql_result($result,$i,"status");
            
            // check for a date conflict
            for ($k = 1; $k<$j; $k++) {
                if (strcmp( substr($date,0,10), substr($strtdate[$k],0,10) ) == 0) {
                    // found possible conflict, what do we do about it? Try setting status to -3 if not assigned
                    if ($status[$k] == STATUS_ASSIGNED && $status[$j] == STATUS_RELEASED) $status[$j] = STATUS_CONFLICT;
                    if ($status[$j] == STATUS_ASSIGNED && $status[$k] == STATUS_RELEASED) $status[$k] = STATUS_CONFLICT;
                }
            }
            $i++;
        }
        
        $reqnum_by_rqstr[$email] = $nums;
        $reqname_by_rqstr[$email] = $names;
        $strtdate_by_rqstr[$email] = $strtdate;
        $statusid_by_rqstr[$email] = $statusid;
        $status_by_rqstr[$email] = $status;
    }
    
    // query by op session
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_group_cycle_name = '".$cycle."'
        ORDER BY show_name, start_date, req_num, customers_lastname, opsreq_person_email
        ;
    ";
    $result=mysql_query($query);
    $num = mysql_numrows($result);
    
    // scan for full sessions and disable requests
    $empty_slots_by_session = array();
    for ($i=0; $i<$num; $i++ ) {
        if (mysql_result($result,$i,"show_name") != "") {
            // count number of assignments (status = STATUS_ASSIGNED)
            $j = $i;
            $count1 = 0;
            if (mysql_result($result,$j,"status") == STATUS_ASSIGNED) $count1++;
            while ( ($j<$num-1) && (mysql_result($result,$j,"show_name") == mysql_result($result,$j+1,"show_name")) 
                    && (mysql_result($result,$j,"start_date") == mysql_result($result,$j+1,"start_date")) ) {
                $j++;
                if (mysql_result($result,$j,"status") == STATUS_ASSIGNED) $count1++;
            }
            $empty_slots_by_session[mysql_result($result,$i,"show_name").mysql_result($result,$j,"start_date")] = 0+mysql_result($result,$i,"spaces") - $count1;
            //echo "Recomputed empty slots for ".mysql_result($result,$i,"show_name")." as ".$empty_slots_by_session[mysql_result($result,$i,"show_name")]."<br/>";

            // have count, check for over
            if ($count1 >= (0+mysql_result($result,$i,"spaces")) ) {
                // yes, have to set others to 'filled' == STATUS_FULL
                $j = $i;
                if (mysql_result($result,$j,"status") == STATUS_RELEASED) {
                    setfilled(mysql_result($result,$j,"opsreq_person_email"), mysql_result($result,$j,"req_num"));
                }
                while ( ($j<$num-1) && (mysql_result($result,$j,"show_name") == mysql_result($result,$j+1,"show_name")) 
                        && (mysql_result($result,$j,"start_date") == mysql_result($result,$j+1,"start_date")) ) {
                    $j++;
                    if (mysql_result($result,$j,"status") == STATUS_RELEASED) { 
                        setfilled(mysql_result($result,$j,"opsreq_person_email"), mysql_result($result,$j,"req_num"));
                    }
                }
            }
            $i = $j;
        }
    }
    return $result;
}

// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" or "?start=" in the arguments
if (! ($args["cy"]) ) {
    echo 'This page has to be entered from the <a href="ops_assign_group.php">grouping page</a>.<p/>';
    echo "Please click on that link and start there.<p/>";
    return;
} else {
    $cycle = $args["cy"];
}

// here, the cycle name exists, 
echo '<h2>Cycle: '.$cycle.'</h2>';

// Load it

global $opts, $event_tools_db_prefix, $event_tools_href_add_on, $cycle;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

echo '<table><tr>';

echo '<td><form method="get" action="ops_assign_group.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Return to grouping</button></form></td>';

echo '<td><form method="get" action="ops_assign_update.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Go to update requests</button></form></td>';

echo '<td><form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Refresh/update this page</button></form></td>';

echo '<td><form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="from" value="'.$cycle.'">';
echo '<button type="submit">Copy to new cycle:</button>';
echo '<input  name="cy">';
echo '</form></td>';

echo '</tr></table>';


$query="
    SELECT DISTINCT show_name, start_date
    FROM ".$event_tools_db_prefix."eventtools_opsession_name
    ORDER BY show_name, start_date
    ;
";

$result=mysql_query($query);

$i = 0;
$num = mysql_numrows($result);

$sessions = array();

while ($i < $num) {
    $sessions[] = mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date");
    $i++;
}

echo '<form method="get" action="ops_assign_set.php">
    <button type="submit" name="insert" value="y">Add operator to session</button>';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<select name="operator">';
$query="
    SELECT DISTINCT customers_firstname, customers_lastname, opsreq_person_email
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ORDER BY customers_lastname, opsreq_person_email
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);
for ($i = 0; $i < $num; $i++) {
    echo '<option value="'.mysql_result($result,$i,"opsreq_person_email").'">'.mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").' &lt;'.mysql_result($result,$i,"opsreq_person_email").'&gt;'."</option>";
}
echo '</select>';
echo '<select name="session">';
$query="
    SELECT DISTINCT ops_id, show_name, start_date
    FROM ".$event_tools_db_prefix."eventtools_opsession_name
    ORDER BY show_name, start_date
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);
for ($i = 0; $i < $num; $i++) {
    echo '<option value="'.mysql_result($result,$i,"ops_id").'">'.mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date")."</option>";
}
echo '</select>';
echo '</form>';

// start processing tags
if ( $args["from"] ) {
    copy_to_new_cycle($args["from"], $cycle);
}

if ( $args["insert"] ) {
    // insert a request not already present
    
    $ops_id = $args["session"];  

    $query="
        SELECT *
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_group_cycle_name = '".$cycle."'
        AND opsreq_person_email = '".$args["operator"]."'
        ;
    ";
    //echo $query;
    $result=mysql_query($query);
    $num = mysql_numrows($result);
    //echo $num;
    if ($num != "0") {
        // scan for existing 
        $O = TRUE;
        $id = 0;
        for ($i = 0; $i < $num; $i++) {
            if ( mysql_result($result,$i,"ops_id") == $ops_id ) {
                echo "<b>Force assignment of existing request</b><p>";
                setstatus(mysql_result($result,$i,"opsreq_req_status_id"),'1');
                $O = FALSE;
                break;
            } else if ( mysql_result($result,$i,"status") == "0" ) {
                // match the last unassigned or empty request
                $id = mysql_result($result,$i,"opsreq_req_status_id");
            }
        }
        // check if that did the job, or if we need a new one
        if ($O & $id != 0) {
            // have to create one for this. 
            echo '<b>Created status link and force assignment: [opsreq_group_req_link: '.mysql_result($result,0,"opsreq_group_id").' '.mysql_result($result,0,"opsreq_id").']';
            echo '[opsreq_req_status: '.mysql_result($result,0,"ops_id").' '.$lastfree.']</b>';
            $query = "UPDATE ".$event_tools_db_prefix."eventtools_opsreq_req_status
                        SET status='".STATUS_ASSIGNED."', ops_id='".$ops_id."', forced='1'
                        WHERE opsreq_req_status_id = '".$id."'
                        ;";
            //echo '<p>'.$query.'<p>';
            mysql_query($query);
        }
    } else {
        echo '<br/><b>Failed to find operator/session for insert</b><br/>';
    }
    
}

if ( $args["op"] ) {
    //echo "Set status for id ".$args["id"].'<br>';
    $stat = ($args["op"] == 'A') ? '1' : ($args["op"] == 'D' ? '-2' : '0');
    $id =  $args["id"];
    setstatus($id,$stat);
}

// echo "(Loading ...)<p/>";

// get counts of people in groups
global $group_user_count;
$group_user_count = array();

$query="
    SELECT ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id
    FROM ".$event_tools_db_prefix."eventtools_opsreq_group JOIN ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
    ON ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id = ".$event_tools_db_prefix."eventtools_opsreq_group_req_link.opsreq_group_id
    WHERE opsreq_group_cycle_name = '".$cycle."'
    ORDER BY ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id
    ;
";
$result=mysql_query($query);
$num = mysql_numrows($result);
if ($num <= 0) echo "<br/>Query failed: ".$query.'<br/>';

for ($i = 0; $i < $num; ) {
    $count = 1;
    while ( ($i<$num-1) && (mysql_result($result,$i,"opsreq_group_id") == mysql_result($result,$i+1,"opsreq_group_id")) ) {
        $i++;
        $count++;
    }
    $group_user_count[mysql_result($result,$i,"opsreq_group_id")] = $count;
    $i++;
}

// preload navigation

global $reqnum_by_rqstr, $reqname_by_rqstr, $strtdate_by_rqstr, $statusid_by_rqstr, $status_by_rqstr;
global $rqstr_name, $rqstr_group, $rqstr_address, $rqstr_email, $rqstr_group_size, $rqstr_req_size, $rqstr_req_any;
global $empty_slots_by_session;

// set the initial navigation info
$result = updatenavigation();
$num = mysql_numrows($result);

// handle group operations after the temporary 
// status (conflicts, etc) has been set

if ( $args["grp"] ) {
    //cy=gggg&id=7737&pri=1&op=P#s637
    $id =  $args["id"];  // one item; there will be more through the group
    $pri = $args["pri"];
    $query = "SELECT opsreq_person_email, final.req_num FROM
                ((((( ".$event_tools_db_prefix."eventtools_opsreq_req_status seg
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_group_req_link lnk
                    ON seg.opsreq_group_req_link_id = lnk.opsreq_group_req_link_id )
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_req_status final
                    ON seg.ops_id = final.ops_id )
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_group_req_link l2
                    ON l2.opsreq_group_req_link_id = final.opsreq_group_req_link_id )
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_group g1
                    ON lnk.opsreq_group_id = g1.opsreq_group_id )
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsreq_group g2
                    ON l2.opsreq_group_id = g2.opsreq_group_id )
                    LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession_req op
                    ON op.opsreq_id = l2.opsreq_id
                WHERE seg.opsreq_req_status_id = '".$id."' 
                    AND final.status = '0' 
                    AND seg.req_num = final.req_num 
                    AND g1.opsreq_group_cycle_name = g2.opsreq_group_cycle_name
                ;";

    //echo '<p>'.$query.'<p>';
    $rgrp = mysql_query($query);
    //echo "found ".mysql_numrows($rgrp).'<br/>';
    
    // now have the items to set status on
    for ($j = 0; $j<mysql_numrows($rgrp); $j++) {
        $email = mysql_result($rgrp,$j,"opsreq_person_email");
        $reqn = mysql_result($rgrp,$j,"req_num");
        //echo 'Check '.$email.' '.$reqn.' stat '.$status_by_rqstr[$email][$reqn].' to '.$reqname_by_rqstr[$email][$reqn].' '.$strtdate_by_rqstr[$email][$reqn].'<br/>';

        // and set it in use (status 1) if not already set or in conflict
        if ($status_by_rqstr[$email][$reqn] == '0') {
            echo 'Assign '.$email.' to '.$reqname_by_rqstr[$email][$reqn].' '.$strtdate_by_rqstr[$email][$reqn].'<br/>';
            // do the db update
            setstatus($statusid_by_rqstr[$email][$reqn],"1");
        }
    }
    
    // and update status
    $result = updatenavigation();
    $num = mysql_numrows($result);
}

// do a best-fill operation
if ( $args["best"] ) {
    $pri = 0+$args["pri"];

    $users = array();
    $groups = array();
    // make a list of everybody we're trying to place
    foreach ($rqstr_email as $email) {
        if (($status_by_rqstr[$email][$pri] == "0") && ($reqname_by_rqstr[$email][$pri] != "")) {
            // user needs to be assigned
            // see if already handling via group
            if ( $groups[$rqstr_group[$email]]) {
                echo $email." is part of existing group<br/>";
                continue;
            }
            // else we need to process this one
            $users[] = $email;
            $groups[$rqstr_group[$email]] = TRUE;
        }
    }
    echo 'Will attempt to place '.count($users).' requests: ';
    foreach ($users as $u) echo ' '.$u.' '; echo '<br/>';
    
    // loop until can't do anything
    while (TRUE) {
        // find and satisfy requests without a N+1th choice, then continue
        foreach ($users as $key => $email) {
            if ( ($status_by_rqstr[$email][1+$pri] == "0") && ($reqname_by_rqstr[$email][1+$pri] != "") ) {
                // echo "Next choice for ".$email.' is '.$reqname_by_rqstr[$email][1+$pri].'<br/>';
                continue;
            }
            // check space
            if ( $rqstr_group_size[$email] > $empty_slots_by_session[$reqname_by_rqstr[$email][$pri].$strtdate_by_rqstr[$email][$pri]] ) {
                echo 'Skip '.$email.' because group needs '.$rqstr_group_size[$email].' spots but only '.$empty_slots_by_session[$reqname_by_rqstr[$email][$pri].$strtdate_by_rqstr[$email][$pri]].' available, no 2nd choice<br/>';
                continue;
            }
            // found one
            echo 'Assign '.$email.' to '.$reqname_by_rqstr[$email][$pri].' '.$strtdate_by_rqstr[$email][$pri].' (no 2nd)<br/>';
            // do the db update
            setstatus($statusid_by_rqstr[$email][$pri],"1");
            // remove the entry
            unset($users[$key]);
            // update
            $result = updatenavigation();
            $num = mysql_numrows($result); 
            // and restart at top
            continue 2;
        }
        // put in a first where possible (2nd exists at present)
        foreach ($users as $key => $email) {
            if ( ($status_by_rqstr[$email][$pri] != "0") || ($reqname_by_rqstr[$email][$pri] == "") ) {
                // echo "First choice for ".$email.' is '.$reqname_by_rqstr[$email][1+$pri].'<br/>';
                continue;
            }
            if ( $rqstr_group_size[$email] > $empty_slots_by_session[$reqname_by_rqstr[$email][$pri].$strtdate_by_rqstr[$email][$pri]] ) {
                echo 'Assign '.$email.' 2nd choice because group needs '.$rqstr_group_size[$email].' spots in 1st choice but only '.$empty_slots_by_session[$reqname_by_rqstr[$email][$pri].$strtdate_by_rqstr[$email][$pri]].' available<br/>';
                // do the db update
                setstatus($statusid_by_rqstr[$email][$pri+1],"1");
                // remove the entry
                unset($users[$key]);
                // update
                $result = updatenavigation();
                $num = mysql_numrows($result); 
                continue;
            }
            // found one
            //echo 'Check '.$email.' group needs '.$rqstr_group_size[$email].' has '.$empty_slots_by_session[$reqname_by_rqstr[$email][$pri].$strtdate_by_rqstr[$email][$pri]].' available<br/>';
            echo 'Assign '.$email.' to '.$reqname_by_rqstr[$email][$pri].' '.$strtdate_by_rqstr[$email][$pri].' (1st)<br/>';
            // do the db update
            setstatus($statusid_by_rqstr[$email][$pri],"1");
            // remove the entry
            unset($users[$key]);
            // update
            $result = updatenavigation();
            $num = mysql_numrows($result); 
            // and restart at top
            continue 2;
        }
        
        // don't have anything to do, done
        break;
    }
    // assign remaining to 2nd place
    echo "Have ".count($users)." left at end of main pass.<br/>";
    
    // loop to assign those to their next choice
    foreach ($users as $key => $email) {
        if ( ($status_by_rqstr[$email][$pri+1] != "0") || ($reqname_by_rqstr[$email][$pri+1] == "") ) {
            continue;  // this is an error
        }
        if ( $rqstr_group_size[$email] > $empty_slots_by_session[$reqname_by_rqstr[$email][$pri+1].$strtdate_by_rqstr[$email][$pri+1]] ) {
            echo 'Skip '.$email.' because group needs '.$rqstr_group_size[$email].' spots but only '.$empty_slots_by_session[$reqname_by_rqstr[$email][$pri+1].$strtdate_by_rqstr[$email][$pri+1]].' available on '.$reqname_by_rqstr[$email][$pri+1].'<br/>';
            continue; // this is sort-of an error
        }
        echo 'Assign '.$email.' to '.$reqname_by_rqstr[$email][$pri+1].' '.$strtdate_by_rqstr[$email][$pri+1].' '.$rqstr_group_size[$email].'<br/>';
        // do the db update
        setstatus($statusid_by_rqstr[$email][$pri+1],"1");
        // remove the entry
        unset($users[$key]);
        // update
        $result = updatenavigation();
        $num = mysql_numrows($result); 
        // and restart at top
        continue;
    }
    
    // error if can't make it work
    if (count($users) > 0) {
        echo '<b>Error, cannot process requests for: ';
        foreach ($users as $u) echo ' '.$u.' '; 
        echo '</b><br/>';
    }
    
}

// start display

// Create by-operator table with buttons for assigning (from arrays only, not query)
echo '<h3>By Operator, in preference order</h3>';
echo '<table border="1">'."\n";

$tagnum = 1;

foreach ($rqstr_email as $email) {
    $row = $reqname_by_rqstr[$email];
    // count assignments
    $count = 0;
    for ($j = 1; $j<9; $j++) {
        if ( ($status_by_rqstr[$email][$j] == "1") && ($row[$j] != "") ) $count++;
    }
    // display output
    echo '<tr><td><a name="'.'p'.$tagnum.'">'.$rqstr_name[$email].'<br/>'.$rqstr_address[$email];
    if ($group_user_count[$rqstr_group[$email]] > 1) echo '<br/>Group of '.$group_user_count[$rqstr_group[$email]];
    echo '<br/>Up to '.$rqstr_req_size[$email].', has '.$count.', any='.$rqstr_req_any[$email];
    echo '</td>';
    for ($j = 1; $j<9; $j++) {
        echo '<td>';
        if ( ($status_by_rqstr[$email][$j] != "") && ($row[$j] != "") ) {
            $stat = $status_by_rqstr[$email][$j];
            setspan($stat);
            echo '<a name="'.'i'.$statusid_by_rqstr[$email][$j].'"/>';
            echo '<a href="ops_assign_set.php?cy='.$cycle.'#y'.$statusid_by_rqstr[$email][$j].'">';
            echo $row[$j].'</a><br/>'.$strtdate_by_rqstr[$email][$j];
            setbuttons($cycle,$stat,$statusid_by_rqstr[$email][$j],'p'.($tagnum));
            echo '</span>';
        }
        echo '</td>';
    }
    echo "</tr>\n\n";
    $tagnum++;
}

echo '</tr>'."\n";

echo '</table>';

echo '<p/>';

// now do a table of people by op session
echo '<h3>By Session</h3>';
echo '<table border="1">';
$tagnum = 0;

$lowpri = "99";

for ($i=0; $i<$num; ) {
    if (mysql_result($result,$i,"show_name") != "") {
        // count number of assignments (status = 1)
        $j = $i;
        $firstindex = $j;
        $count0 = 0; // waiting assignment
        $count1 = 0; // assigned
        $firstpri = 99;
        $pricnt = 0;
        if ($status_by_rqstr[mysql_result($result,$j,"opsreq_person_email")][mysql_result($result,$j,"req_num")] == "0") {
            if (mysql_result($result,$j,"req_num") < $lowpri) $lowpri = mysql_result($result,$j,"req_num");
            $count0++;
            if ( (mysql_result($result,$j,"req_num") < $firstpri) && (mysql_result($result,$j,"req_num") > 0)) {
                $firstpri = mysql_result($result,$j,"req_num");
                $firstindex = $j;
                $pricnt = 1;
            }
        }
        if (mysql_result($result,$j,"status") == "1") $count1++;
        while ( ($j<$num-1) && (mysql_result($result,$j,"show_name") == mysql_result($result,$j+1,"show_name")) 
                && (mysql_result($result,$j,"start_date") == mysql_result($result,$j+1,"start_date")) ) {
            $j++;
            if ($status_by_rqstr[mysql_result($result,$j,"opsreq_person_email")][mysql_result($result,$j,"req_num")] == "0") {
                if (mysql_result($result,$j,"req_num") < $lowpri) $lowpri = mysql_result($result,$j,"req_num");
                $count0++;
                if ( (mysql_result($result,$j,"req_num") < $firstpri) && (mysql_result($result,$j,"req_num") > 0)) {
                    $firstpri = mysql_result($result,$j,"req_num");
                    $firstindex = $j;
                    $pricnt = 0;
                }
                if (mysql_result($result,$j,"req_num") == $firstpri) $pricnt++;
            }
            if (mysql_result($result,$j,"status") == "1") $count1++;
        }
        
        // make row
        echo '<tr><td class="session">';
        echo '<a name="'.'s'.$tagnum.'">';
        echo mysql_result($result,$i,"show_name").'<br/>'.mysql_result($result,$i,"start_date");
        // include counts
        echo '<br/>';
        echo $count1.'/'.$count0.'/'.mysql_result($result,$i,"spaces");
        // button to add?
        if (($pricnt > 0) && ($pricnt <= (mysql_result($result,$i,"spaces") - $count1))) {
            echo '<form method="get" action="ops_assign_set.php#'.'s'.$tagnum.'">
                  <input type="hidden" name="cy" value="'.$cycle.'">
                  <input type="hidden" name="id" value="'.mysql_result($result,$firstindex,"opsreq_req_status_id").'">
                  <input type="hidden" name="pri" value="'.$firstpri.'">
                  <input type="submit" name="grp" value="P"/></form>';
        }
        echo '</td><td>';
        
        $status = $status_by_rqstr[mysql_result($result,$i,"opsreq_person_email")][mysql_result($result,$i,"req_num")];
        setspan($status);
        echo '<a name="'.'y'.mysql_result($result,$i,"opsreq_req_status_id").'"/>';
        echo '<a href="ops_assign_set.php?cy='.$cycle.'#i'.mysql_result($result,$i,"opsreq_req_status_id").'">';
        echo mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").'<br/>'.mysql_result($result,$i,"opsreq_person_email").'</a>';
        echo '<br/>'.mysql_result($result,$i,"entry_city").', '.mysql_result($result,$i,"entry_state");
        echo '<br/>'.' [Pri: '.mysql_result($result,$i,"req_num").']';
        $count = $group_user_count[$rqstr_group[mysql_result($result,$i,"opsreq_person_email")]];
        if ($count > 1) echo " Group of ".$count;
        setbuttons($cycle,$status,mysql_result($result,$i,"opsreq_req_status_id"),'s'.$tagnum);
        
        while ( ($i<$num-1) && (mysql_result($result,$i,"show_name") == mysql_result($result,$i+1,"show_name")) 
                && (mysql_result($result,$i,"start_date") == mysql_result($result,$i+1,"start_date")) ) {
            $i++;
            echo '</span>';
            echo '</td><td>';
            
            $status = $status_by_rqstr[mysql_result($result,$i,"opsreq_person_email")][mysql_result($result,$i,"req_num")];
            setspan($status);
            echo '<a name="'.'y'.mysql_result($result,$i,"opsreq_req_status_id").'"/>';
            echo '<a href="ops_assign_set.php?cy='.$cycle.'#i'.mysql_result($result,$i,"opsreq_req_status_id").'">';
            echo mysql_result($result,$i,"customers_firstname").' '.mysql_result($result,$i,"customers_lastname").'<br/>'.mysql_result($result,$i,"opsreq_person_email").'</a>';
            echo '<br/>'.mysql_result($result,$i,"entry_city").', '.mysql_result($result,$i,"entry_state");
            echo '<br/>'.' [Pri: '.mysql_result($result,$i,"req_num").']';
            $count = $group_user_count[$rqstr_group[mysql_result($result,$i,"opsreq_person_email")]];
            if ($count > 1) echo " Group of ".$count;
            setbuttons($cycle,$status,mysql_result($result,$i,"opsreq_req_status_id"),'s'.$tagnum);
            
        }
        echo '</span></td></tr>'."\n";
    }
    $i++;
    $tagnum++;
}

echo '</table><p/>';

echo '<form method="get" action="ops_assign_set.php">
      <input type="hidden" name="cy" value="'.$cycle.'">
      <input type="hidden" name="pri" value="'.$lowpri.'">
      <input type="submit" name="best" value="Fill Best Priority '.$lowpri.'"/></form>';

?>
</body>
</html>